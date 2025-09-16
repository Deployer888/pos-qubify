<?php

namespace App\Http\Controllers\Customer\ReturnRequest;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SaleReturnRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Warehouse\WarehouseService;
use App\DataTables\SaleReturnRequestDataTable;
use App\Services\Sale\SaleReturnRequestServices;
use App\DataTables\SaleReturnRequestCreateDataTable;
use App\Notifications\GlobalNotification;

class ProductReturnController extends Controller
{
    protected $warehouseService;

    public function __construct(SaleReturnRequestServices $saleReturnServices, WarehouseService $warehouseService)
    {
        $this->services             = $saleReturnServices;
        $this->warehouseService     = $warehouseService;
    }

    public function index(SaleReturnRequestDataTable $dataTable)
    {
        set_page_meta(__t('return_request_list'));

        return $dataTable->render('customer.return-request.return-request-list');
    }

    public function createList(SaleReturnRequestCreateDataTable $dataTable)
    {

        set_page_meta(__t('product_return'));

        return $dataTable->render('customer.return-request.return-request-createable-list');
    }

    public function create($sale_id)
    {
        set_page_meta(__t("return") . ' ' . __t('products'));

        $returnAbleInvoice = $this->services->getReturnableSale($sale_id);

        return view('customer.return-request.return-request', [
            'warehouses' => $this->warehouseService->getActiveData(),
            'sales' => $returnAbleInvoice,
            'warehouse' => $returnAbleInvoice->warehouse_id
                ? $this->warehouseService->getWareHouse($returnAbleInvoice->warehouse_id)
                : $this->warehouseService->defaultWareHouse()
        ]);
    }

    public function store(Request $request)
    {
        $request->all();
        $get_old_return_request = SaleReturnRequest::where('invoice_id', $request->invoice_id)
            ->where('status', SaleReturnRequest::STATUS_PENDING)
            ->first();

        if ($get_old_return_request) {
            flash(__t('return_request_already_created'))->warning();
            return redirect()->route('customer.products-return-request.createable_list');
        }

        $return_invoice = $this->services->validate($request)->store($request);
        flash(__t('sales_return_successful'))->success();

        return redirect()->route('customer.products-return-request.createable_list');
    }

    public function show($id)
    {

        set_page_meta(__t('show') . ' ' . __t('return_request'));
        return view('customer.return-request.return-show', [
            'sale_return' => SaleReturnRequest::query()
                ->with('invoice', 'saleReturnRequestItems')
                ->findOrFail($id)
        ]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
