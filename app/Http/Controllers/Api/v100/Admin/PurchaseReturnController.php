<?php

namespace App\Http\Controllers\Api\v100\Admin;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Services\Purchase\PurchaseReturnServices;
use App\Http\Resources\PurchaseReceiveOrReturnResource;
use App\Http\Resources\PurchaseReceiveOrReturnShowResource;

class PurchaseReturnController extends Controller
{
    use ApiReturnFormatTrait;
    public function __construct(PurchaseReturnServices $services)
    {
        $this->services = $services;
    }
    public function storePurchaseReturn(Request $request, Purchase $purchase)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'return_date' => 'required|date_format:Y-m-d',
            'return_note' => 'required|string',
            'product_stock_id' => 'required',
            'total' => 'required|numeric',
            'product_id.*' => 'required|exists:products,id',
            'return_quantity.*' => 'nullable|numeric',
            'return_price.*' => 'nullable|numeric',
            'return_sub_total.*' => 'nullable|numeric'
        ]);
        if ($validator->fails()) {
            return $this->responseWithError('Invalid data send', $validator->errors(),422);
        }

        try {
             $this->services->store($request, $purchase);
             return $this->responseWithSuccess(__t('purchase_return_successful'));

        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }
    public function purchaseReturnList()
    {
        try{
            // dd($this->services->all());
        $returns = PurchaseReceiveOrReturnResource::collection($this->services->all())->response()->getData(true);
        return $this->responseWithSuccess(__('Return List'), $returns);

       } catch(\Exception $e) {
        return $this->responseWithError('Something went wrong', $e->getMessage());

       }
    }
    public function returnShow($id)
    {
        $purchase_return =PurchaseReturn::query()
        ->with('purchase', 'purchaseReturnItems')
        ->findOrFail($id);
        return $this->responseWithSuccess(__('Return Details'), new PurchaseReceiveOrReturnShowResource($purchase_return));

    }
    public function returnDelete($id)
    {
        try {

            $purchaseReturn = PurchaseReturn::query()->findOrFail($id);
            $purchaseReturn->purchaseReturnItems()->delete();
            $purchaseReturn->delete();
            return $this->responseWithSuccess(__t('purchase_return_delete_successful'));
        } catch (\Exception $e) {

            if ($e->getCode() == 23000) {
           return $this->responseWithError(__t('purchase_return_already_use'));

            } else {
                return $this->responseWithError('Something went wrong', $e->getMessage());

            }
        }

    }
}
