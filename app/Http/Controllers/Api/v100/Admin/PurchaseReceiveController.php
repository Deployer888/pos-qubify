<?php

namespace App\Http\Controllers\Api\v100\Admin;

use App\Http\Resources\PurchaseReceiveOrReturnShowResource;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\PurchaseReceive;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Services\Purchase\PurchaseReceiveServices;
use App\Http\Resources\PurchaseReceiveOrReturnResource;

class PurchaseReceiveController extends Controller
{
    use ApiReturnFormatTrait;
    public function __construct(PurchaseReceiveServices $services)
    {
        $this->services = $services;

    }
    public function storePurchasesReceive(Request $request, Purchase $purchase)
    {

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            'total' => 'required|numeric',
            'product_stock_id' => 'required',
            'receive_quantity.*' => 'nullable|numeric|min:0',
            'receive_price.*' => 'required|numeric',
            'receive_sub_total.*' => 'nullable|numeric'
        ]);
        if ($validator->fails()) {
            return $this->responseWithError('Invalid data send', $validator->errors(),422);
        }
        $store = $this->services->store($request, $purchase);

        try {
            if ($store) {
                return $this->responseWithSuccess(__t('purchase_receive_successful'));

            } else {
                return $this->responseWithError(__t('purchase_receive_failed'));

            }
        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }

    }
    public function receives()
    {
        try{
            $receives = PurchaseReceiveOrReturnResource::collection($this->services->all())->response()->getData(true);
            return $this->responseWithSuccess(__('Receive List'), $receives);

           } catch(\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

           }

    }
    public function receiveShow($id)
    {
        $purchase_receive = PurchaseReceive::query()
                ->with('purchase', 'purchaseItemReceives')
                ->findOrFail($id);
        return $this->responseWithSuccess(__('Receive Details'), new PurchaseReceiveOrReturnShowResource($purchase_receive));


    }
    public function receiveDelete($id)
    {
        try {

            $purchaseReceive = PurchaseReceive::query()->findOrFail($id);
            $purchaseReceive->purchaseItemReceives()->delete();
            $purchaseReceive->delete();

            return $this->responseWithSuccess(__t('purchase_receive_delete_successful'));
        } catch (\Exception $e) {

            if ($e->getCode() == 23000) {
            return $this->responseWithError(__t('purchase_receive_already_use'), $e->getMessage());

            } else {
            return $this->responseWithError('Something went wrong', $e->getMessage());

            }
        }
    }
}
