<?php

namespace App\Http\Controllers\Admin\Warehouse;

use App\DataTables\WarehouseDataTable;
use App\DataTables\WarehouseStockHistoryDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\WarehouseRequest;
use App\Models\ProductStockHistory;
use App\Models\PurchaseItemReceive;
use App\Models\PurchaseReceive;
use App\Models\PurchaseReturnItem;
use App\Models\Warehouse;
use App\Services\Utils\FileUploadService;
use App\Services\Warehouse\WarehouseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;

class WarehousesController extends Controller
{
    protected $warehouseService;
    protected $fileUploadService;

    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
        $this->fileUploadService = app(FileUploadService::class);

        $this->middleware(['permission:List Warehouse'])->only(['index']);
        $this->middleware(['permission:Add Warehouse'])->only(['create']);
        $this->middleware(['permission:Edit Warehouse'])->only(['edit']);
        $this->middleware(['permission:Delete Warehouse'])->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(WarehouseDataTable $dataTable)
    {
        set_page_meta(__('custom.warehouse'));
        return $dataTable->render('admin.warehouses.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        set_page_meta(__('custom.add_warehouse'));
        return view('admin.warehouses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WarehouseRequest $request)
    {
        $data = $request->validated();

        if (isset($data['barcode_image']) && $data['barcode_image'] != null) {
            $data['barcode_image'] = $this->fileUploadService->uploadBase64($data['barcode_image'],Warehouse::FILE_STORE_PATH,'',$data['barcode']);
        }
        if (isset($data['is_default']) && $data['is_default']) {
            Warehouse::where('is_default', true)->update(['is_default' => false]);
        } else {
            $data['is_default'] = false;
        }
        if ($this->warehouseService->createOrUpdate($data)) {
            flash(__('warehouse_created_successfully'))->success();
        } else {
            flash(__('warehouse_create_failed'))->error();
        }

        return redirect()->route('admin.warehouses.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Warehouse $warehouse)
    {
        $warehouse_details = $warehouse->load(
            'product_stocks.product.category',
            'product_stocks.product.manufacturer',
            'product_stocks.product.weight_unit',
            'product_stocks.attribute',
            'product_stocks.attributeItem',
        );

        set_page_meta($warehouse->name);

        return view('admin.warehouses.show', compact('warehouse_details'));
    }

    public function showPdf(Warehouse $warehouse)
    {
        $warehouse_details = $warehouse->load('product_stocks.product.category', 'product_stocks.product.manufacturer');

        set_page_meta($warehouse->name);

        $pdf = PDF::loadView('admin.warehouses.show-pdf', compact('warehouse_details'));
        return $pdf->download($warehouse->name.'_details' . '.pdf');

        return view('admin.warehouses.show-pdf', compact('warehouse_details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $warehouse = $this->warehouseService->get($id);

        set_page_meta(__('custom.edit_warehouse'));
        return view('admin.warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WarehouseRequest $request, $id)
    {
        $data = $request->validated();

        $warehouse = $this->warehouseService->get($id);

        if (isset($data['barcode_image']) && $data['barcode_image'] != null) {
            $data['barcode_image'] = $this->fileUploadService->uploadBase64($data['barcode_image'],Warehouse::FILE_STORE_PATH, $warehouse->barcode_image, $data['barcode']);
        }

        if (isset($data['is_default']) && $data['is_default']) {
            Warehouse::where('is_default', true)->update(['is_default' => false]);
        } else {
            $data['is_default'] = false;
        }

        if ($this->warehouseService->createOrUpdate($data, $id)) {
            flash(__('custom.warehouse_update_successfully'))->success();
        } else {
            flash(__('custom.warehouse_update_failed'))->error();
        }

        return redirect()->route('admin.warehouses.index');
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
            if ($this->warehouseService->delete($id)) {
                flash(__('custom.warehouse_delete_successfully'))->success();
            } else {
                flash(__('custom.warehouse_delete_failed'))->error();
            }
        } catch (\Throwable $th) {
            flash(__('custom.this_record_already_used'))->warning();
        }

        return redirect()->route('admin.warehouses.index');
    }
    public function barcodeDownload($id)
    {
        $product = $this->warehouseService->get($id);
        if(!$product) abort(404);

        if(Storage::exists(Warehouse::FILE_STORE_PATH .'/' . $product->barcode_image)){
            $file = Storage::disk(config('filesystems.default'))->download(Warehouse::FILE_STORE_PATH .'/' . $product->barcode_image);
            return $file;
        }else{
            flash(__('custom.barcode_not_found'))->error();
            return redirect()->back();
        }
    }

    public function showStorageStoreAndOut(Request $request, $id)
    {
//        dd($request->all());
        set_page_meta('Warehouse Storage Store And Out');
//        $warehouse = $this->warehouseService->get($id, ['purchases.purchaseReceives.purchaseItemReceives','purchases.purchaseItems', 'invoices']);
//        //todo::need to show history
//        $history = ProductStockHistory::with(['product'])
//            ->where('warehouse_id', $id)->orderBy('id', 'desc')->get();

        $datatable = new WarehouseStockHistoryDatatable($id);

        return $datatable->render('admin.warehouses.show-storage-store-and-out');

//        return view('admin.warehouses.show-storage-store-and-out', compact('history'));
    }

}
