<?php

namespace App\Http\Controllers\Admin\Sale;

use App\DataTables\SaleReturnRequestDataTable;
use App\Http\Controllers\Controller;
use App\Models\SaleReturnRequest;
use App\Services\Sale\SaleReturnRequestServices;
use App\Services\Warehouse\WarehouseService;


class SaleReturnRequestController extends Controller
{
    protected $warehouseService;
    /**
     * __construct
     *
     * @param  mixed $saleReturnRequestServices
     * @return void
     */
    public function __construct(SaleReturnRequestServices $saleReturnRequestServices, WarehouseService $warehouseService)
    {
        $this->services         = $saleReturnRequestServices;
        $this->warehouseService = $warehouseService;

        $this->middleware(['permission:Show Sale Return'])->only(['index']);
    }
    public function returnRequestList(SaleReturnRequestDataTable $dataTable)
    {
        set_page_meta(__t('sale_return_request_list'));

        return $dataTable->render('admin.sales.return_request_list');
    }
    public function returnRequestShow($id)
    {
        set_page_meta(__t('show') . ' ' . __t('sale_return_request'));

        return view('admin.sales.return_request_show', [
            'sale_return' => SaleReturnRequest::query()
                ->with('invoice', 'saleReturnRequestItems')
                ->findOrFail($id)
        ]);
    }
    public function returnRequestAccept($id)
    {
        if ($this->services->returnRequestAccept($id)) {
            flash(__t('sales_return_request_accept_successful'))->success();
        } else {
            flash(__('something_went_wrong'))->error();
        }


        return redirect()->back();
    }
    public function returnRequestReject($id)
    {
        $this->services->returnRequestReject($id);

        flash(__t('sales_return_request_reject_successful'))->success();

        return redirect()->back();
    }
}
