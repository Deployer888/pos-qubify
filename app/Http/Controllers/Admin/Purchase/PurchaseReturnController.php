<?php

namespace App\Http\Controllers\Admin\Purchase;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\DataTables\PurchaseReturnDataTable;
use App\Services\Purchase\PurchaseReturnServices;

class PurchaseReturnController extends Controller
{
    /**
     * __construct
     *
     * @param  mixed $services
     * @return void
     */
    public function __construct(PurchaseReturnServices $services)
    {
        $this->services = $services;

        $this->middleware(['permission:Return Purchase'])->only(['purchaseReturn']);
        $this->middleware(['permission:Purchase Return List'])->only(['purchaseReturnList']);
    }

    /**
     * purchaseReturn
     *
     * @param  mixed $purchase
     * @return void
     */
    public function purchaseReturn(Purchase $purchase)
    {
        set_page_meta(__t('return') . ' ' . __t('purchase'));

        return view('admin.purchase.return', [
            'purchase' => $purchase->load('purchaseItems.product.stock')
        ]);
    }

    /**
     * storePurchaseReturn
     *
     * @param  mixed $request
     * @param  mixed $purchase
     * @return RedirectResponse
     */
    public function storePurchaseReturn(Request $request, Purchase $purchase): RedirectResponse
    {

        $this->services
            ->validate($request)
            ->store($request, $purchase);

        flash(__t('purchase_return_successful'))->success();

        return redirect()->route('admin.purchases.index');
    }

    /**
     * purchaseReturnList
     *
     * @param  mixed $dataTable
     * @return void
     */
    public function purchaseReturnList(PurchaseReturnDataTable $dataTable)
    {
        set_page_meta(__t('purchase_return_list'));

        return $dataTable->render('admin.purchase.return_list');
    }

    /**
     * returnShow
     *
     * @param  mixed $id
     * @return void
     */
    public function returnShow($id)
    {
        set_page_meta(__t('show') . ' ' . __t('purchase_return'));

        return view('admin.purchase.return_show', [
            'purchase_return' => PurchaseReturn::query()
                ->with('purchase', 'purchaseReturnItems')
                ->findOrFail($id)
        ]);
    }

    /**
     * returnDelete
     *
     * @param  mixed $id
     * @return void
     */
    public function returnDelete($id)
    {
        try {

            $purchaseReturn = PurchaseReturn::query()->findOrFail($id);
            $purchaseReturn->purchaseReturnItems()->delete();
            $purchaseReturn->delete();

            flash(__t('purchase_return_delete_successful'))->success();
        } catch (\Exception $e) {

            if ($e->getCode() == 23000) {
                flash(__t('purchase_return_already_use'))->error();
            } else {
                flash($e->getMessage())->error();
            }
        }

        return redirect()->route('admin.purchases.return.list');
    }
}
