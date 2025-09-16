<?php

namespace App\Http\Controllers\Api\v100\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\SaleReturnRequestResource;
use App\Http\Resources\SaleReturnResource;
use App\Models\Invoice;
use App\Models\SaleReturnRequest;
use App\Services\Sale\SaleReturnRequestServices;
use App\Services\Warehouse\WarehouseService;
use App\Traits\ApiReturnFormatTrait;
use DB;
use Illuminate\Http\Request;

class InvoiceReturnController extends Controller
{
    use ApiReturnFormatTrait;
    protected $saleReturnRequestServices;
    protected $warehouseService;

    public function __construct(SaleReturnRequestServices $saleReturnRequestServices, WarehouseService $warehouseService)
    {
        $this->saleReturnRequestServices = $saleReturnRequestServices;
        $this->warehouseService = $warehouseService;
    }
    public function index(){
//        if(request()->route()->getPrefix() == 'api/v100/customer'){
//            $customer = Customer::find(auth()->user()->id);
//        }

        $returnable_invoice = Invoice::with('warehouse')
            ->orderBy('id', 'DESC')
            ->where(function ($query){
                $query->where('status', 'paid')
                    ->orWhere('status', 'partially_paid');
            })
            ->when(auth()->guard('api_customer')->check(), function ($query) {
                $query->where('customer_id', auth()->guard('api_customer')->user()->id);
            })
            ->select('invoices.*')->paginate(10);
        return $this->responseWithSuccess('Returnable Invoice List',$returnable_invoice);
    }
    public function returnRequests(){
        $sale_returns = SaleReturnRequestResource::collection($this->saleReturnRequestServices->returnRequestList());
        return $this->responseWithSuccess('Return Request List',$sale_returns);
    }
    public function returnRequestShow($id){
        $returnAbleInvoice = $this->saleReturnRequestServices->getReturnableSale($id);

        if(!$returnAbleInvoice){
            return $this->responseWithError('Return Request Not Found');
        }

        $data = [
            'warehouses' => $this->warehouseService->getActiveData(),
            'sales' => $returnAbleInvoice,
            'warehouse' => $returnAbleInvoice->warehouse_id
                ? $this->warehouseService->getWareHouse($returnAbleInvoice->warehouse_id)
                : $this->warehouseService->defaultWareHouse()
        ];

        return $this->responseWithSuccess('Return Request',$data);
    }

    public function storeRequests(Request $request){

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'invoice_id'    => 'required|numeric',
            'return_date'   => 'required|date_format:Y-m-d',
            'return_note'   => 'nullable|string',
            'total'         => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError($validator->errors()->first(), $validator->errors(),422);
        }
        $get_old_return_request = SaleReturnRequest::where('invoice_id', $request->invoice_id)
            ->where('status', SaleReturnRequest::STATUS_PENDING)
            ->first();

        if ($get_old_return_request) {
            return $this->responseWithError(__t('return_request_already_created'),[],500);

        }

        try {
            $sale_return = $this->saleReturnRequestServices->store($request);
            return $this->responseWithSuccess('Return Request Created', new SaleReturnResource($sale_return));
        } catch (\Exception $e) {

            return $this->responseWithError('Something went wrong', $e->getMessage());
        }
    }
    public function getRequests($id)
    {
        try {

            return $this->responseWithSuccess('Product Return Request Details', SaleReturnRequest::query()
            ->with('invoice', 'saleReturnRequestItems')
            ->findOrFail($id));
        } catch (\Exception $e) {

            return $this->responseWithError('Something went wrong', $e->getMessage());
        }
    }

}
