<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Mail\InvoiceSend;
use App\Mail\StockAlertSend;
use App\Models\SystemSettings;
use App\Models\User;
use App\Services\Supplier\SupplierService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Product\ProductService;
use App\Http\Requests\ProductStockRequest;
use App\Services\Warehouse\WarehouseService;
use App\Services\Product\ProductStockService;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class ProductStocksController extends Controller
{
    protected $productService;
    protected $warehouseService;
    protected $productStockService;
    protected $supplierService;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        ProductService      $productService,
        WarehouseService    $warehouseService,
        ProductStockService $productStockService,
        SupplierService     $supplierService
    )
    {
        $this->productService = $productService;
        $this->warehouseService = $warehouseService;
        $this->productStockService = $productStockService;
        $this->supplierService = $supplierService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        set_page_meta(__('custom.low_product_stock'));

        return view('admin.product_stocks.low-stocks',
            resolve(ProductService::class)->lowStockList());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = $this->productService->get($id, ['attributes.attribute',
            'attributes.attribute_item', 'weight_unit']);

        if (!$product) abort(404);

//        $skuSetting     = $this->productService->stockSkuSettings();
//        $barcode        = generateBarcode();

        $warehouses     = $this->warehouseService->getActiveData();
        $old_stocks     = $this->productStockService->getProductStock($id);
        $suppliers      = $this->supplierService->getActiveData();
        set_page_meta(__('custom.update_product_stock'));
        return view('admin.product_stocks.edit', compact('product', 'warehouses', 'old_stocks','suppliers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductStockRequest $request, $id)
    {
        $data = $request->all();

        $product = $this->productService->get($id);
        if (!$product) abort(404);

        if ($product->is_variant) {
            // dd($data);
            $result = $this->productStockService->variantStockUpdate($data, $id);
        } else {
            $result = $this->productStockService->normalStockUpdate($data, $id);
        }

//        if ($result) {
//            flash(__('custom.product_stock_update_successfully'))->success();
//        } else {
//            flash(__('custom.product_stock_update_failed'))->error();
//        }

        return redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateByStock(Request $request,$id){
        $data = $request->all();
        $product = $this->productService->get($id);
        if (!$product) abort(404);
        $result = $this->productStockService->updateByStock($data, $id);
        if ($result) {
            flash(__('custom.product_stock_update_successfully'))->success();
        } else {
            flash(__('custom.product_stock_update_failed'))->error();
        }
        return redirect()->route('admin.products.index');
    }

    /**
     * @return bool|void
     */
    public function sendAlertQuantityOnEmail()
    {
        try {

            $systemSetting = SystemSettings::query()
                ->where('settings_key', 'stock_alert_mail_getter')
                ->first();

            $mailerRoles = Role::query()
                ->whereIn('id', $systemSetting->settings_value)
                ->pluck('name')
                ->toArray();


            $users = User::query()
                ->where('status', User::STATUS_ACTIVE)
                ->whereHas('roles', function ($q) use ($mailerRoles){
                    $q->whereIn('name', $mailerRoles);
                })->pluck('email');

            $lowStockList = resolve(ProductService::class)->lowStockList();

            foreach ($users as $userEmail) {

                Mail::to($userEmail)->send(new StockAlertSend($lowStockList));

                sleep(2);
            }

            return true;

        } catch (\Exception $e) {

            return false;
        }
    }
}
