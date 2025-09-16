<?php

namespace App\Http\Controllers\Admin\Purchase;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\PurchaseReceive;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\DataTables\PurchaseReceiveDataTable;
use App\Services\Purchase\PurchaseReceiveServices;

class PurchaseReceiveController extends Controller
{
    /*
     * This Construct load thi controller services PurchaseReceiveServices
     *
     * */

    public function __construct(PurchaseReceiveServices $services)
    {
        $this->services = $services;

        $this->middleware(['permission:Purchase Receive List'])->only(['purchasesReceive']);
    }

    /*
     * This function work for Purchase Receive data render
     *
     * */

    public function purchasesReceive(Purchase $purchase)
    {
        set_page_meta(__t('receive') . ' ' . __t('purchases') . ':' . $purchase->purchase_number);

        return view('admin.purchase.receive', [
            'purchase' => $purchase->load(['supplier', 'warehouse', 'purchaseItems.product'])
        ]);
    }

    /*
     * This function work for purchase receive store
     *
     * */

    public function storePurchasesReceive(Request $request, Purchase $purchase): RedirectResponse
    {
        $store = $this->services->validate($request)->store($request, $purchase);

        if ($store) {
            flash(__t('purchase_receive_successful'))->success();
        } else {
            flash(__t('purchase_receive_failed'))->error();
        }

        return redirect()->route('admin.purchases.index');
    }

    /**
     * receives
     *
     * @param  mixed $dataTable
     * @return void
     */
    public function receives(PurchaseReceiveDataTable $dataTable)
    {
        set_page_meta(__t('purchase_receive_list'));

        return $dataTable->render('admin.purchase.receive_list');
    }

    /**
     * receiveShow
     *
     * @param  mixed $id
     * @return void
     */
    public function receiveShow($id)
    {
        return view('admin.purchase.receive_show', [
            'purchase_receive' => PurchaseReceive::query()
                ->with('purchase', 'purchaseItemReceives')
                ->findOrFail($id)
        ]);
    }

    /**
     * receiveDelete
     *
     * @param  mixed $id
     * @return void
     */
    public function receiveDelete($id)
    {
        try {

            $purchaseReceive = PurchaseReceive::query()->findOrFail($id);
            $purchaseReceive->purchaseItemReceives()->delete();
            $purchaseReceive->delete();

            flash(__t('purchase_receive_delete_successful'))->success();
        } catch (\Exception $e) {

            if ($e->getCode() == 23000) {
                flash(__t('purchase_receive_already_use'))->error();
            } else {
                flash($e->getMessage())->error();
            }
        }

        return redirect()->route('admin.purchases.receive-list');
    }
}
