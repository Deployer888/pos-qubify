<?php

namespace App\Http\Controllers\Admin\Product;

use App\Models\Product;
use App\Models\Warehouse;
use App\Services\Product\ProductStockService;
use App\Services\Warehouse\WarehouseService;
use File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use App\Services\Brand\BrandService;
use App\Http\Requests\ProductRequest;
use App\Services\Product\ProductService;
use App\Services\Attribute\AttributeService;
use App\Services\WeightUnit\WeightUnitService;
use App\Services\Product\ProductCategoryService;
use App\Services\Manufacturer\ManufacturerService;
use App\Services\MeasurementUnit\MeasurementUnitService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;
class ProductsController extends Controller
{
    protected $productCategoryService;
    protected $brandService;
    protected $manufacturerService;
    protected $weightUnitService;
    protected $measurementUnitService;
    protected $attributeService;
    protected $productService;
    protected $warehouseService;
    protected $productStockService;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        ProductCategoryService $productCategoryService,
        BrandService $brandService,
        ManufacturerService $manufacturerService,
        WeightUnitService $weightUnitService,
        MeasurementUnitService $measurementUnitService,
        AttributeService $attributeService,
        ProductService $productService,
        WarehouseService    $warehouseService,
        ProductStockService $productStockService
    ) {
        $this->productCategoryService = $productCategoryService;
        $this->brandService = $brandService;
        $this->manufacturerService = $manufacturerService;
        $this->weightUnitService = $weightUnitService;
        $this->measurementUnitService = $measurementUnitService;
        $this->attributeService = $attributeService;
        $this->productService = $productService;
        $this->warehouseService = $warehouseService;
        $this->productStockService = $productStockService;


        $this->middleware(['permission:List Product'])->only(['index']);
        $this->middleware(['permission:Add Product'])->only(['create']);
        $this->middleware(['permission:Edit Product'])->only(['edit']);
        $this->middleware(['permission:Delete Product'])->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductDataTable $dataTable)
    {
        set_page_meta(__('custom.products'));
        return $dataTable->render('admin.products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $skuSetting = $this->productService->skuSettings();
        $categories = $this->productCategoryService->getActiveData(null,'subCategory')->where('parent_id', null);
        $brands = $this->brandService->getActiveData();
        $manufacturers = $this->manufacturerService->getActiveData();
        $weight_units = $this->weightUnitService->get();
        $measurement_units = $this->measurementUnitService->get();
        $barcode = generateBarcode();
        $attributes = $this->attributeService->getActiveData();

        set_page_meta(__('custom.add_product'));
        return view(
            'admin.products.create',
            compact(
                'categories',
                'brands',
                'manufacturers',
                'weight_units',
                'measurement_units',
                'barcode',
                'attributes',
                'skuSetting'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {


        $data = $request->validated();

        $storedProduct = $this->productService->createOrUpdate($data);

        $defaultWarehouse = Warehouse::where('is_default', 1)->first();

        if (!$defaultWarehouse) {
            flash(__('custom.default_warehouse_not_found'))->error();
            return redirect()->back();
        }

        if ($data['is_variant']) {
            $this->createVariantStock($storedProduct->id, $data['attribute_data'], $defaultWarehouse->id);
        } else {
            $this->createNormalStock($storedProduct->id, $defaultWarehouse->id);
        }

        if ($storedProduct) {
            flash(__('custom.product_created_successfully'))->success();
        } else {
            flash(__('custom.product_create_failed'))->error();
        }

        if ($request->is_submit_with_stock) {
            return redirect()->route('admin.product-stocks.edit', $storedProduct->id);
        }

        return redirect()->route('admin.products.index');
    }

    private function createVariantStock($productId, $attributeData, $warehouseId)
    {
        $warehouse_stock = [];

        foreach ($attributeData as $attribute) {
            foreach ($attribute['attribute_items'] as $item) {
                $attribute_id = $attribute['attribute'];
                $attribute_item_id = $item;

                if (!isset($warehouse_stock[$warehouseId])) {
                    $warehouse_stock[$warehouseId] = [
                        "warehouse" => $warehouseId,
                        "stock" => [],
                        "quantity" => [],
                        "price" => [],
                        "customer_buying_price" => [],
                        "adjust_type" => null
                    ];
                }

                $warehouse_stock[$warehouseId]["stock"][$attribute_id][$attribute_item_id] = "0";
                $warehouse_stock[$warehouseId]["quantity"][$attribute_id][$attribute_item_id] = "0";
                $warehouse_stock[$warehouseId]["price"][$attribute_id][$attribute_item_id] = "0";
                $warehouse_stock[$warehouseId]["customer_buying_price"][$attribute_id][$attribute_item_id] = "0";
            }
        }

        $warehouse_stock = array_values($warehouse_stock);

        $this->productStockService->variantStockUpdate([
            "is_variant" => "1",
            "alert_quantity" => "",
            "warehouse_stock" => $warehouse_stock,
            "supplier_id" => "1"
        ], $productId);
    }

    private function createNormalStock($productId, $warehouseId)
    {
        $this->productStockService->normalStockUpdate([
            "is_variant" => "0",
            "alert_quantity" => "",
            "warehouse_stock" => [
                [
                    "warehouse" => $warehouseId,
                    "stock" => "0",
                    "quantity" => "0",
                    "price" => "0",
                    "customer_buying_price" => "0",
                    "adjust_type" => null
                ]
            ],
            "supplier_id" => "1"
        ], $productId);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        set_page_meta(__('custom.show_product_details'));
        $product_details = $product->load('category', 'manufacturer', 'weight_unit', 'allStock');
        $warehouses = Warehouse::query()->pluck('name', 'id')->toArray();

        return view('admin.products.show', compact('product_details', 'warehouses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $product = $this->productService->get($id, ['attributes']);
        // dd($product);
        if (!$product) abort(404);

        $categories = $this->productCategoryService->getActiveData(null,'subCategory')->where('parent_id', null);
        $brands = $this->brandService->getActiveData();
        $manufacturers = $this->manufacturerService->getActiveData();
        $weight_units = $this->weightUnitService->get();
        $measurement_units = $this->measurementUnitService->get();
        $barcode = $product->barcode ? $product->barcode : generateBarcode();
        $attributes = $this->attributeService->getActiveData();


        // Process attribute
        $old_attribute_data = [];

        if ($product->attributes) {
            foreach ($product->attributes as $item) {
                $old_attribute_data[$item->attribute_id][] = $item->attribute_item_id;
            }
        }
        $old_attribute_data = json_encode($old_attribute_data);


        set_page_meta(__('custom.edit_product'));
        return view(
            'admin.products.edit',
            compact(
                'product',
                'categories',
                'brands',
                'manufacturers',
                'weight_units',
                'measurement_units',
                'barcode',
                'attributes',
                'product',
                'old_attribute_data'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $data = $request->validated();

        if ($this->productService->createOrUpdate($data, $id)) {
            flash(__('custom.product_updated_successfully'))->success();
        } else {
            flash(__('custom.product_update_failed'))->error();
        }

        return redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if ($this->productService->delete($id)) {
                flash(__('custom.product_deleted_successfully'))->success();
            } else {
                flash(__('custom.product_delete_failed'))->error();
            }
        } catch (\Throwable $th) {
            flash(__('custom.this_record_already_used'))->warning();
        }

        return redirect()->route('admin.products.index');
    }

    /**
     * barcodeDownload
     *
     * @param  mixed $id
     * @return void
     */
    public function barcodeDownload($id)
    {
        $product = $this->productService->get($id);
        if(!$product) abort(404);

        if(Storage::exists('product_barcodes/' . $product->barcode_image)){
            $file = Storage::disk(config('filesystems.default'))->download('product_barcodes/' . $product->barcode_image);
            return $file;
        }else{
            flash(__('custom.barcode_not_found'))->error();
            return redirect()->back();
        }
    }

    /**
     * barcodeDownloadZip
     *
     * @return void
     */
    public function barcodeDownloadZip()
    {

        $zip      = new ZipArchive;
        $fileName = 'barcodes.zip';
        if ($zip->open(public_path($fileName), ZipArchive::CREATE) == TRUE) {
            if (request()->filled('product_ids')){
                $product_ids = explode(',', request('product_ids'));
                $products = Product::query()->findMany($product_ids);
            }else{
                $products = Product::query()->get();
            }
            $products_barcode_images = [];
            foreach ($products as $product){
                $products_barcode_images[] = $product->barcode_image;
            }
            $files = Storage::allFiles('product_barcodes');
            foreach ($files as $key => $value) {
                if (in_array(basename($value), $products_barcode_images)){
                    $relativeName = basename($value);
                    $zip->addFile(Storage::disk(config('filesystems.default'))->path($value), $relativeName);
                }else{
                    $zip->deleteName(basename($value));
                }
            }
            $zip->close();
        }
        return response()->download(public_path('barcodes.zip'), 'barcodes.zip')->deleteFileAfterSend(true);;
//        return redirect(asset($fileName));
    }


    // ............HANDLE API REQUEST

    /**
     * getByBarcode
     *
     * @param  mixed $barcode
     * @return void
     */
    public function getByBarcode($barcode)
    {
        $product = $this->productService->getByBarcode($barcode);

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }
    public function getProductStockByBarcode($barcode)
    {
        $product = $this->productService->getProductStockByBarcode($barcode);

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * getByCategory
     *
     * @param  mixed $category_id
     * @return void
     */
    public function getByCategory($category_id)
    {
        if ($category_id == 'all') {
            $products = $this->productService->get(null, ['warehouseStockQty']);
        } else {
            $products = $this->productService->getByCategory($category_id, ['warehouseStockQty']);
        }

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
    public function getProductStockByCategory($category_id, $warehouse_id)
    {
        if ($category_id == 'all') {
            $warehouse  = $this->warehouseService->get($warehouse_id);
            $products   = $this->productService->wareHouseWiseAllProductStocks(['product','attribute','attributeItem'], $warehouse);
        } else {
            $products   = $this->productService->getProductStockByCategory($category_id, $warehouse_id, ['product','attribute','attributeItem']);
        }

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
    /**
     * productSkuSearch
     *
     * @param  mixed $q
     * @return void
     */
    public function productSkuSearch($q)
    {
        /*$select = ['id', 'sku'];*/
        $products = $this->productService->getProductBySearch($q, '*', ['warehouseStockQty']);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * productSearchByNameSku
     *
     * @param  mixed $q
     * @return void
     */
    public function productSearchByNameSku($q)
    {
        /*$select = ['id', 'sku', 'name', 'price', 'warehouseStockQty'];*/
        $products = $this->productService->getProductByNameSku($q, '*', ['warehouseStockQty']);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
    public function productStockSearchByNameSku($q,$warehouse_id)
    {
        $products = $this->productService->getProductStockByNameSku($q, $warehouse_id, '*', ['product','attribute','attributeItem']);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
    public function productSearchByWarehouse($q)
    {
        $warehouse = $this->warehouseService->get($q);
        $products = $this->productService->wareHouseWiseProducts('warehouseStockQty', $warehouse);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
    public function productStockSearchByWarehouse($id)
    {
        $warehouse = $this->warehouseService->get($id);
        $products =  $this->productService->wareHouseWiseAllProductStocks(['product','attribute','attributeItem'], $warehouse);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
    public function productQtyUpdate($id)
    {
       try {
            $product = $this->productService->get($id, ['attributes.attribute',
            'attributes.attribute_item', 'weight_unit']);
    //    $warehouses = $this->warehouseService->getActiveData();
    //    $old_stocks = $this->productStockService->getProductStock($id);
    //    dd($product,$warehouses,$old_stocks);
        return view('admin.products.qty_update', [
            'product'       => $product,
        //     'warehouses'    => $warehouses,
        //    'old_stocks'    => $old_stocks
        ]);
       }catch(\Exception $e) {
       }
    }
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,csv',
        ]);

        try {
            $import = new ProductImport($this->productService, $this->productStockService);
            Excel::import($import, $request->file('import_file'));

            flash(__('custom.import_success'))->success();

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Collect all row-specific error messages
            $messages = [];

            foreach ($e->errors() as $msg) {
                $messages[] = $msg; // Already includes row info from ProductImport
            }

            return redirect()->route('admin.products.index')
                ->withErrors($messages);

        } catch (\Exception $e) {
            // Log or flash unexpected exceptions
            flash(__('custom.import_failed') . ': ' . $e->getMessage())->error();
        }

        return redirect()->route('admin.products.index');
    }

}
