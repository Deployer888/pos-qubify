<?php

namespace App\Http\Controllers\Api\v100\Admin;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Http\Resources\PurchaseResource;
use App\Http\Requests\API\PurchaseRequest;
use App\Services\Purchase\PurchaseServices;
use App\Http\Resources\PurchaseShowResource;

class PurchaseController extends Controller
{
    use ApiReturnFormatTrait;

    protected $services;

    /**
     * __construct
     *
     * @param  mixed $purchaseServices
     * @return void
     */
    public function __construct(PurchaseServices $purchaseServices)
    {
        $this->services = $purchaseServices;


    }
    public function index(){

            $purchases = PurchaseResource::collection(Purchase::with(['supplier:id,first_name,last_name,email,phone', 'warehouse:id,name', 'purchaseItems'])->newQuery()->select('purchases.*')->orderByDesc('purchase_number')->paginate(10))->response()->getData(true);
            return $this->responseWithSuccess('Purchase List',$purchases);


    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PurchaseRequest $request)
    {

        try{
            if ($this->services->store($request)) {
                return $this->responseWithSuccess(__t('purchase_create_successful'));
            } else {
                return $this->responseWithError(__t('purchase_create_failed'));
            }
        } catch(\Exception $e)
        {
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        $purchase = $purchase->load(['supplier', 'warehouse', 'purchaseItems.product','purchaseItems.receiveItems','purchaseItems.returnItem']);
        // $purchase = $purchase->load(['purchaseItems.product.stock']);

        return $this->responseWithSuccess('Purchase Details', new PurchaseShowResource($purchase));
        // return $this->responseWithSuccess('Purchase Details', $purchase);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PurchaseRequest $request, Purchase $purchase)
    {

        try {
            $update = $this->services->setModel($purchase)->update($request);
            if ($update) {
                return $this->responseWithSuccess(__t('purchase_update_successful'));
            } else {
                return $this->responseWithError(__t('purchase_update_failed'));


            }
        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        try {

            $purchase->purchaseItems()->delete();
            $purchase->delete();
            return $this->responseWithSuccess(__t('purchase_delete_successful'));
        } catch (\Exception $e) {

            if ($e->getCode() == 23000) {
            return $this->responseWithError(__t('purchase_already_use'), $e->getMessage());

            } else {
            return $this->responseWithError('Something went wrong', $e->getMessage());

            }
        }

    }

    /*
     * This function is work for purchase cancel page render
     *
     * */


    /*
     * This function work for cancel information store
     *
     * */

    public function storeCancelPurchase(Request $request, Purchase $purchase)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'date' => 'required|date:Y-m-d',
            'note' => 'required|string'
        ]);
        if ($validator->fails()) {
            return $this->responseWithError('Invalid data send', $validator->errors(),422);
        }
      try {
        $purchase->update([
            'status' => Purchase::STATUS_CANCEL,
            'cancel_date' => $request->date,
            'cancel_by' => auth()->id(),
            'cancel_note' => $request->note,
        ]);
        return $this->responseWithSuccess(__t('purchase_cancel_successful'));

      } catch(\Exception $e){
        return $this->responseWithError('Something went wrong', $e->getMessage());

      }

    }

    /*
     * This function work for purchase confirm
     *
     * */

    public function confirmPurchase(Purchase $purchase)
    {
        try {
        $purchase->update(['status' => Purchase::STATUS_CONFIRMED]);
        return $this->responseWithSuccess(__t('purchase_confirm_successful'));

        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }
}
