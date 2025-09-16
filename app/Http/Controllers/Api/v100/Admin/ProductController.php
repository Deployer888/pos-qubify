<?php

namespace App\Http\Controllers\Api\v100\Admin;


use DB;
use ZipArchive;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Brand\BrandService;
use App\Traits\ApiReturnFormatTrait;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use App\Services\Product\ProductService;
use App\Http\Requests\API\ProductRequest;
use App\Services\Attribute\AttributeService;
use App\Services\Warehouse\WarehouseService;
use App\Http\Resources\ProductCreateResource;
use App\Services\Product\ProductStockService;
use App\Http\Resources\ProductDetailsResource;
use App\Services\WeightUnit\WeightUnitService;
use App\Services\Product\ProductCategoryService;
use App\Http\Resources\ProductStockUpdateResource;
use App\Services\Manufacturer\ManufacturerService;
use App\Services\MeasurementUnit\MeasurementUnitService;

class ProductController extends Controller
{
    use ApiReturnFormatTrait;
    protected $warehouseService;
    protected $productService;
    protected $productStockService;
    protected $productCategoryService;
    protected $brandService;
    protected $manufacturerService;
    protected $weightUnitService;
    protected $measurementUnitService;
    protected $attributeService;



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
    }
    public function index(){
        $products = ProductResource::collection(Product::with(['category:id,name', 'manufacturer:id,name', 'weight_unit:id,name'])->newQuery()->select('products.*')->paginate(10))->response()->getData(true);
        return $this->responseWithSuccess('Product List',$products);
    }
    public function warehouseAndStockWiseProducts(Request $request){
        $warehouses = $this->warehouseService->pluck();
        $warehouse = count($warehouses) > 1 && \request('warehouse')
            ? $this->warehouseService->getWareHouse(\request('warehouse'))
            : $this->warehouseService->firstWarehouse();

       if(!$warehouse) {
           return $this->responseWithError('Warehouse not found', [], 404);
       }

        $productStocks = $this->productService->wareHouseWiseAllProductStocks(['product','attribute:id,name','attributeItem:id,name,attribute_id,color'], $warehouse);

        return $this->responseWithSuccess('Warehouse And Stock Wise Product List', $productStocks);
    }
    public function productStockSearchNameSku($query)
    {
        $results = ProductStock::query()
        ->with('product', 'attribute', 'attributeItem')
        ->whereHas('product', function ($q) use ($query) {
            $q->where('status', 'active')
              ->where(function ($q2) use ($query) {
                  $q2->where('name', 'like', "%{$query}%")
                     ->orWhere('sku', 'like', "%{$query}%")
                     ->orWhere('barcode', 'like', "%{$query}%");
              });
        })
        ->orderBy('id')
        ->get();

        // Group stock items by product
        $grouped = $results->groupBy('product_id');

        $filtered = collect();

        foreach ($grouped as $productId => $stocks) {
            $product = $stocks->first()->product;

            if ($product && $product->is_batch_product) {
                // Batch product: take only the first stock record
                $filtered->push($stocks->first());
            } else {
                // Non-batch product: take all stock records
                $filtered = $filtered->merge($stocks);
            }
        }


        return $this->responseWithSuccess('Matched Products Details',  $filtered->values());
    }
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

        $product_create_info =[
            'categories'=>$categories,
                'brands'=>$brands,
                'manufacturers'=>$manufacturers,
                'weight_units'=>$weight_units,
                'measurement_units'=>$measurement_units,
                'barcode'=>$barcode,
                'attributes'=>$attributes,
                'skuSetting'=>$skuSetting
        ];
        return $this->responseWithSuccess('Products create info', new ProductCreateResource($product_create_info));

    }
    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        if(isset($data['attribute_data'])){
            $data['attribute_data'] = json_decode($data['attribute_data'], true);
        }
        try {
            $storedProduct = $this->productService->createOrUpdate($data);

            if ($storedProduct) {
              return $this->responseWithSuccess(__('custom.product_created_successfully'),new ProductResource($storedProduct));

            } else {
               return $this->responseWithError(__('custom.product_create_failed'));

            }
        } catch(\Exception $e){
            logger($e);
           return $this->responseWithError('Something went wrong',$e->getMessage());
        }


    }
    public function update(ProductRequest $request, $id)
    {
        $data = $request->validated();
        if(isset($data['attribute_data'])){
            $data['attribute_data'] = json_decode($data['attribute_data'], true);
        }
      try {
            if ($this->productService->createOrUpdate($data, $id)) {
                $updatedProduct = $this->productService->get($id);
                return $this->responseWithSuccess(__('custom.product_updated_successfully'),new ProductResource($updatedProduct));
            } else {
                return $this->responseWithError(__('custom.product_update_failed'));

            }
      } catch(\Exception $e){
        return $this->responseWithError('Something went wrong',$e->getMessage());

      }


    }
    public function show($id)
    {
        try {
            $product = $this->productService->get($id,['attributes']);
            if(!$product)
            return $this->responseWithError('Not found',[],404);

            $product_details = $product->load('category', 'manufacturer', 'weight_unit', 'allStock');
            $warehouses = Warehouse::query()->pluck('name', 'id')->toArray();
            $skuSetting = $this->productService->skuSettings();
            $categories = $this->productCategoryService->getActiveData(null,'subCategory')->where('parent_id', null);
            $brands = $this->brandService->getActiveData();
            $manufacturers = $this->manufacturerService->getActiveData();
            $weight_units = $this->weightUnitService->get();
            $measurement_units = $this->measurementUnitService->get();
            $barcode = generateBarcode();
            $attributes = $this->attributeService->getActiveData();
            $old_attribute_data = [];

        if ($product->attributes) {
            foreach ($product->attributes as $item) {
                $old_attribute_data[$item->attribute_id][] = $item->attribute_item_id;
            }
        }
        $old_attribute_data = json_encode($old_attribute_data);
            $product_with_warehouses = [
                'product_create_info'=>[
                    'categories'=>$categories,
                    'brands'=>$brands,
                    'manufacturers'=>$manufacturers,
                    'weight_units'=>$weight_units,
                    'measurement_units'=>$measurement_units,
                    'barcode'=>$barcode,
                    'attributes'=>$attributes,
                    'skuSetting'=>$skuSetting
                ],
                'product' => $product_details,
                'warehouses' => $warehouses,
                'old_attribute_data' => $old_attribute_data
            ];

            return $this->responseWithSuccess('Product Details',new ProductDetailsResource($product_with_warehouses));
        } catch(\Exception $e){
        return $this->responseWithError('Something went wrong',$e->getMessage());

        }


    }
    public function productQtyUpdate($id)
    {
        try{

        $product = $this->productService->get($id, ['attributes.attribute',
            'attributes.attribute_item', 'weight_unit']);
        if(!$product)
         return $this->responseWithError('Not found',[],404);

        // $warehouses = $this->warehouseService->getActiveData();
        // $old_stocks = $this->productStockService->getProductStock($id);
        // $product_details = [
        //     'product'       => $product,
        //     'warehouses'    => $warehouses,
        //     'old_stocks'    => $old_stocks
        // ];
        return $this->responseWithSuccess('Product Stock Update Details', new ProductStockUpdateResource($product));
        } catch(\Exception $e){
        return $this->responseWithError('Something went wrong',$e->getMessage());

        }
    }
    public function getDownloadLink($id){
        try{
            $product = $this->productService->get($id);
            if(!$product)
            return $this->responseWithError('Not found',[],404);
            $download_link = url('/').'/api/v100'. "/admin/products/$product->id/barcode-download";

            return $this->responseWithSuccess(['download_link' => $download_link]);
        } catch(\Exception $e)
        {
        return $this->responseWithError('Something went wrong',$e->getMessage());

        }

    }
    public function downloadBarcode($id){

        $product = $this->productService->get($id);
        if(!$product)
        {
          return $this->responseWithError('Product not found',[],404);
        }
        if(Storage::exists('product_barcodes/' . $product->barcode_image)){
            $file = Storage::disk(config('filesystems.default'))->path('product_barcodes/' . $product->barcode_image);
            return response()->download(
                $file,
                $product->barcode_image,
                ['Content-Type' => 'image/png'],
            );
        }else{
            return $this->responseWithError(__('custom.barcode_not_found'));
        }
    }
    public function getAllDownloadLink(){

        $download_link = url('/').'/api/v100'. "/admin/products/barcodes-download";

        return $this->responseWithSuccess(['download_link' => $download_link]);
    }
    public function downloadAllBarcode()
    {
     try{
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
        return response()->download(public_path('barcodes.zip'), 'barcodes.zip')->deleteFileAfterSend(true);
     }catch(\Exception $e)
     {
     return $this->responseWithError('Something went wrong',$e->getMessage());

     }

//        return redirect(asset($fileName));
    }
}
