<?php

namespace App\Http\Controllers\Api\v100\Admin;

use DB;
use App\Models\Invoice;
use App\Models\SaleReturn;
use Illuminate\Http\Request;
use App\Models\SaleReturnRequest;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Services\Sale\SaleReturnServices;
use App\Http\Resources\SaleReturnResource;
use App\Services\Warehouse\WarehouseService;
use App\Services\Sale\SaleReturnRequestServices;
use App\Http\Resources\SaleReturnRequestResource;
use App\Http\Resources\SaleReturnCreatableResource;
use App\Http\Resources\SaleReturnCreateDetailsResource;

class ProductSaleReturnController extends Controller
{
    use ApiReturnFormatTrait;
    protected $warehouseService;
    protected $saleReturnRequestServices;

    public function __construct(SaleReturnRequestServices $saleReturnRequestServices,SaleReturnServices $saleReturnServices, WarehouseService $warehouseService)
    {
        $this->saleReturnRequestServices = $saleReturnRequestServices;
        $this->services = $saleReturnServices;
        $this->warehouseService = $warehouseService;
    }
    public function index(){
        $sale_return_list = SaleReturnResource::collection(SaleReturn::with(['invoice', 'invoice.customerInfo', 'saleReturnItems'])->newQuery()->select('sale_returns.*')->paginate(10))->response()->getData(true);
        return $this->responseWithSuccess('Sale Return List',$sale_return_list);
    }
    public function returnRequests(){
        $sale_return_request_list = SaleReturnRequestResource::collection($this->saleReturnRequestServices->returnRequestList())->response()->getData(true);
        return $this->responseWithSuccess('Sale Return Request List',$sale_return_request_list);
    }
    public function returnRequestShow($id)
    {
        $sale_return = SaleReturnRequest::query()
        ->with('invoice', 'saleReturnRequestItems')
        ->findOrFail($id);
        return $this->responseWithSuccess('Sale return request details', new SaleReturnResource($sale_return));



    }
    public function returnRequestAccept($id)
    {
      try {
        if($this->saleReturnRequestServices->returnRequestAccept($id)){
            return $this->responseWithSuccess(__t('sales_return_request_accept_successful'));
        }else{
            return $this->responseWithError('Something went wrong');

        }
      } catch(\Exception $e){
        return $this->responseWithError('Something went wrong', $e->getMessage());

      }

    }
    public function returnRequestReject($id)
    {
        try {
            if($this->saleReturnRequestServices->returnRequestReject($id)){
                return $this->responseWithSuccess(__t('sales_return_request_reject_successful'));
            }else{
                return $this->responseWithError('Something went wrong');

            }
          } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

          }

    }
    public function getSaleReturns()
    {
       $invoices = Invoice::with('warehouse')
                   ->orderBy('id', 'DESC')
                    ->where(function ($query){
                        $query->where('status', 'paid')
                            ->orWhere('status', 'partially_paid');
                    })
                    ->select('invoices.*')->paginate(10);
     $sale_return_creatable_list = SaleReturnCreatableResource::collection($invoices)->response()->getData(true);
     return $this->responseWithSuccess('Sale Return Creatable List',$sale_return_creatable_list);



    }
    public function create($sale_id)
    {
        try {
            $returnAbleInvoice = $this->services->getReturnableSale($sale_id);
            $sale_return_create_details = [];
            $sale_return_create_details = [
               'warehouses' => $this->warehouseService->getActiveData(),
               'sales' => $returnAbleInvoice,
               'warehouse' => $returnAbleInvoice->warehouse_id
                   ? $this->warehouseService->getWareHouse($returnAbleInvoice->warehouse_id)
                   : $this->warehouseService->defaultWareHouse()
            ];
            return $this->responseWithSuccess('Sale return create details',new SaleReturnCreateDetailsResource($sale_return_create_details));
        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong',$e->getMessage());
        }


    }
    public function storeRequests(Request $request){

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'invoice_id'    => 'required|numeric',
            'return_date'   => 'required|date_format:Y-m-d',
            'return_note'   => 'nullable|string',
            'total'         => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->responseWithError('Invalid data send', $validator->errors(),422);
        }

        try {
            $sale_return = $this->services->store($request);
            return $this->responseWithSuccess('Return Request Created', new SaleReturnResource($sale_return));
        } catch (\Exception $e) {

            return $this->responseWithError('Something went wrong', $e->getMessage());
        }
    }
    public function show($id)
    {


            $sale_return = SaleReturn::query()
                ->with('invoice', 'saleReturnItems')
                ->findOrFail($id);
            return $this->responseWithSuccess('Sale return details', new SaleReturnResource($sale_return));


    }

}
