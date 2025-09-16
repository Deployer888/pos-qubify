<?php

namespace App\Services\Purchase;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\ProductStock;
use App\Services\BaseService;
use App\Models\PurchaseReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * PurchaseReturnServices
 */
class PurchaseReturnServices extends BaseService
{
    protected $product_stock, $product;

    /**
     * __construct
     *
     * @param  mixed $model
     * @param  mixed $stock
     * @param  mixed $product
     * @return void
     */
    public function __construct(PurchaseReturn $model, ProductStock $stock, Product $product)
    {
        $this->model = $model;
        $this->product_stock = $stock;
        $this->product = $product;
    }

    /**
     * validate
     *
     * @param  mixed $request
     * @return PurchaseReturnServices
     */
    public function validate($request): PurchaseReturnServices
    {
        $request->validate([
            'return_date' => 'required|date_format:Y-m-d',
            'return_note' => 'required|string',
            'product_stock_id' => 'required',
            'total' => 'required|numeric',
            'product_id.*' => 'required|exists:products,id',
            'return_quantity.*' => 'nullable|numeric|lte:receive_quantity.*',

            'return_price.*' => 'nullable|numeric',
            'return_sub_total.*' => 'nullable|numeric'
        ],
        [
            'return_quantity.*.lte' => 'The return quantity must be less than or equal to the corresponding receive quantity.',
        ]);


        return $this;
    }

    /**
     * store
     *
     * @param  mixed $request
     * @param  mixed $purchase
     * @return void
     */
    public function store($request, $purchase)
    {

        DB::transaction(function () use ($request, $purchase) {

            $this->storePurchaseReturn($request, $purchase)
                ->storePurchaseReturnItem($request)
                ->stockUpdate($request, $purchase);
        });
    }

    /**
     * storePurchaseReturn
     *
     * @param  mixed $request
     * @param  mixed $purchase
     * @return PurchaseReturnServices
     */
    private function storePurchaseReturn($request, $purchase): PurchaseReturnServices
    {
        $this->model = $this->model
            ->newQuery()
            ->create([
                'purchase_id' => $purchase->id,
                'return_date' => $request->return_date,
                'note' => $request->return_note,
                'total' => $request->total,
            ]);

        return $this;
    }

    /**
     * storePurchaseReturnItem
     *
     * @param  mixed $request
     * @return PurchaseReturnServices
     */
    private function storePurchaseReturnItem($request): PurchaseReturnServices
    {
        if (isset($request->product_id) && is_array($request->product_id)) {
            $products = [];
            foreach ($request->product_id as $key => $product_id) {
                if ($request->return_quantity[$key]) {
                    $products[] = [
                        'purchase_return_id' => $this->model->id,
                        'product_id' => $product_id,
                        'product_stock_id' => $request->product_stock_id[$key],
                        'purchase_item_id' => $request->purchase_item_id[$key],
                        'quantity' => $request->return_quantity[$key],
                        'price' => $request->return_price[$key],
                        'sub_total' => $request->return_sub_total[$key],
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ];
                }
            }

            $this->model->purchaseReturnItems()->insert($products);
        }

        return $this;
    }

    /**
     * stockUpdate
     *
     * @param  mixed $request
     * @param  mixed $purchase
     * @return void
     */
    private function stockUpdate($request, $purchase)
    {
        // dd($request->product_stock_id);
        if (isset($request->product_stock_id) && is_array($request->product_stock_id)) {
            foreach ($request->product_stock_id as $key => $product_stock_id) {
                if ($request->return_quantity[$key]) {

                    $stock = $this->getStock($product_stock_id, $purchase);
                    // dd($stock,$product_stock_id,$purchase);
                    if ($stock) {
                        $stock->update([
                            'quantity' => $stock->quantity - $request->return_quantity[$key]
                        ]);
                    }else{
                        abort(404);
                    }

                    $productStock = $this->product->newQuery()->where('id', $request->product_id[$key])->first();
                    $productStock->update([
                        'stock' => $productStock->stock - $request->return_quantity[$key]
                    ]);
                }
            }
        }
    }

    /**
     * getStock
     *
     * @param  mixed $product
     * @param  mixed $purchase
     * @return void
     */
    public function getStock($product_stock_id, $purchase)
    {

        if ($purchase->warehouse_id){
            $defaultWarehouse = $purchase->warehouse_id;
        }else{
            $defaultWarehouse = Warehouse::query()->where('is_default', true)->first();
            if($defaultWarehouse) {
                $defaultWarehouse = $defaultWarehouse->id;
            }else{
                throw ValidationException::withMessages(['message' => __('Select a warehouse first')]);
            }
        }

        return $this->product_stock->newQuery()
            ->where('id', $product_stock_id)
            ->where('warehouse_id', $defaultWarehouse)
            ->first();
    }
    public function all(){
        return $this->model->with(['purchase.supplier', 'purchase.warehouse', 'purchaseReturnItems'])
        ->newQuery()
        ->select('purchase_returns.*')
        ->leftJoin('purchases', 'purchase_returns.purchase_id', '=', 'purchases.id')
        ->orderByDesc('purchases.purchase_number') // Change 'asc' to 'desc' for descending order
        ->paginate(10);
    }
}
