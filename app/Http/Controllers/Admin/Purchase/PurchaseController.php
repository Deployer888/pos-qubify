<?php

namespace App\Http\Controllers\Admin\Purchase;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\PurchaseDataTable;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PurchaseRequest;
use App\Services\Purchase\PurchaseServices;

class PurchaseController extends Controller
{
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

        $this->middleware(['permission:List Purchase'])->only(['index']);
        $this->middleware(['permission:Add Purchase'])->only(['create']);
        $this->middleware(['permission:Edit Purchase'])->only(['edit']);
        $this->middleware(['permission:Show Purchase'])->only(['show']);
        $this->middleware(['permission:Delete Purchase'])->only(['destroy']);
        $this->middleware(['permission:Cancel Purchase'])->only(['cancelPurchase']);
        $this->middleware(['permission:Confirm Purchase'])->only(['confirmPurchase']);
    }

    /**
     * index
     *
     * @param  mixed $dataTable
     * @return void
     */
    public function index(PurchaseDataTable $dataTable)
    {
        set_page_meta(__t('purchases'));

        return $dataTable->render('admin.purchase.index');
    }

    /*
     * This function is worked for
     * purchase create page render
     * */

    public function create()
    {
        set_page_meta(__t('add') . ' ' . __t('purchases'));
        return view('admin.purchase.create', $this->services->createCredentials());
    }

    /*
     * This function is worked for
     * Product Purchase Request Save
     * */

    public function store(PurchaseRequest $request): RedirectResponse
    {

        if ($this->services->store($request)) {
            flash(__t('purchase_create_successful'))->success();
        } else {
            flash(__t('purchase_create_failed'))->error();
        }

        return redirect()->route('admin.purchases.index');
    }


    /**
     * show
     *
     * @param  mixed $purchase
     * @return void
     */
    public function show(Purchase $purchase)
    {
        set_page_meta(__t('view') . ' ' . __t('purchases') . ':' . $purchase->purchase_number);

        return view('admin.purchase.show', [
            'purchase' => $purchase->load(['supplier', 'warehouse', 'purchaseItems.product'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Purchase $purchase)
    {
//        return $this->services->get($purchase->id,['purchaseItems.product','purchaseItems.productStock.attribute','purchaseItems.productStock.attributeItem']);
        set_page_meta(__t('edit') . ' ' . __t('purchases'));
        return view( 'admin.purchase.edit',
//            [
//            'purchase' => $this->services->get($purchase->id,['purchaseItems.product','purchaseItems.productStock.attribute','purchaseItems.productStock.attributeItem'])
//            ]
            $this->services->setModel($purchase)->editCredentials()
        );
    }

    /**
     * This function is worker for
     * Purchase request Update
     */

    public function update(PurchaseRequest $request, Purchase $purchase): RedirectResponse
    {

        $update = $this->services->setModel($purchase)->update($request);

        if ($update) {
            flash(__t('purchase_update_successful'))->success();
        } else {
            flash(__t('purchase_update_failed'))->error();
        }

        return redirect()->route('admin.purchases.index');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Purchase $purchase): RedirectResponse
    {
        try {

            $purchase->purchaseItems()->delete();
            $purchase->purchaseReceives()->delete();
            $purchase->delete();

            flash(__t('purchase_delete_successful'))->success();
        } catch (\Exception $e) {

            if ($e->getCode() == 23000) {
                flash(__t('purchase_already_use'))->error();
            } else {
                flash($e->getMessage())->error();
            }
        }

        return redirect()->route('admin.purchases.index');
    }

    /*
     * This function is work for purchase cancel page render
     *
     * */

    public function cancelPurchase(Purchase $purchase)
    {
        set_page_meta(__t('cancel') . ' ' . __t('purchases'));

        return view('admin.purchase.cancel', ['purchase' => $purchase]);
    }

    /*
     * This function work for cancel information store
     *
     * */

    public function storeCancelPurchase(Request $request, Purchase $purchase): RedirectResponse
    {
        $request->validate([
            'date' => 'required|date:Y-m-d',
            'note' => 'required|string'
        ]);

        $purchase->update([
            'status' => Purchase::STATUS_CANCEL,
            'cancel_date' => $request->date,
            'cancel_by' => auth()->id(),
            'cancel_note' => $request->note,
        ]);

        flash(__t('purchase_cancel_successful'))->success();

        return redirect()->route('admin.purchases.index');
    }

    /*
     * This function work for purchase confirm
     *
     * */

    public function confirmPurchase(Purchase $purchase): RedirectResponse
    {
        $purchase->update(['status' => Purchase::STATUS_CONFIRMED]);

        flash(__t('purchase_confirm_successful'))->success();

        return redirect()->route('admin.purchases.index');
    }
}
