<?php

namespace App\Services\Sale;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\SaleReturn;
use App\Models\ProductStock;
use App\Services\BaseService;
use App\Models\SaleReturnItems;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\ProductStockHistory;
use App\Traits\ProductStockHistoryTrait;
use Illuminate\Validation\ValidationException;

/**
 * SaleReturnServices
 */
class SaleReturnServices extends BaseService
{
    use ProductStockHistoryTrait;
    public $invoice, $saleReturnItems, $productStock, $product;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        Invoice $invoice,
        SaleReturn $saleReturn,
        SaleReturnItems $saleReturnItems,
        ProductStock $productStock,
        Product $product
    ) {
        $this->invoice = $invoice;
        $this->model = $saleReturn;
        $this->saleReturnItems = $saleReturnItems;
        $this->productStock = $productStock;
        $this->product = $product;
    }

    /**
     * getReturnableSale
     *
     * @param  mixed $invoice_id
     * @return void
     */
    public function getReturnableSale($invoice_id)
    {
        return $this->invoice
            ->newQuery()
            ->with('items', 'items.salesReturnItems')
            ->findOrFail($invoice_id);
    }

    /**
     * validate
     *
     * @param  mixed $request
     * @return SaleReturnServices
     */
    public function validate($request): SaleReturnServices
    {
        $request->validate([
            'invoice_id' => 'required|numeric',
            'return_date' => 'required|date_format:Y-m-d',
            'return_note' => 'nullable|string',
            'total' => 'required|numeric'
        ]);

        return $this;
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store($request)
    {
        DB::transaction(function () use ($request) {
            $data = $this->storeSaleReturn($request);
            $this->storeSaleReturnItems($request);
            $this->stockUpdate($request, $data->model->id);
        });
    }

    /**
     * storeSaleReturn
     *
     * @param  mixed $request
     * @return void
     */
    private function storeSaleReturn($request)
    {
     
        $this->model = $this->model
            ->newQuery()
            ->create([
                'invoice_id' => $request->invoice_id,
                'return_date' => $request->return_date,
                'return_note' => $request->return_note,
                'return_total_amount' => $request->total,
                'items_info' => $this->buildItemsObject($request),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

        return $this;
    }

    /**
     * buildItemsObject
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    private function buildItemsObject($request): JsonResponse
    {
        $items = [];

        foreach ($request->return_qty as $key => $return_qty) {
            if ($return_qty) {
                $items[] = [
                    'invoice_items_id' => $request->invoice_details_id[$key],
                    'product_id' => $request->product_id[$key],
                    'product_stock_id' => $request->product_stock_id[$key],
                    'product_name' => $request->product_name[$key],
                    'product_sku' => $request->product_sku[$key],
                    'price' => $request->price[$key],
                    'discount' => $request->discount[$key],
                    'discount_type' => $request->discount_type[$key],
                    'return_qty' => $return_qty,
                    'return_price' => $request->return_price[$key],
                    'return_sub_total' => $request->return_sub_total[$key],
                ];
            }
        }

        return response()->json($items);
    }

    /**
     * storeSaleReturnItems
     *
     * @param  mixed $request
     * @return void
     */
    private function storeSaleReturnItems($request)
    {
        $items_for_table = [];
        foreach ($request->return_qty as $key => $return_qty) {
            if ($return_qty) {
                $items_for_table[] = [
                    'sale_return_id' => $this->model->id,
                    'invoice_item_id' => $request->invoice_details_id[$key],
                    'product_id' => $request->product_id[$key],
                    'product_stock_id' => $request->product_stock_id[$key],
                    'product_name' => $request->product_name[$key],
                    'return_qty' => $return_qty,
                    'return_price' => $request->return_price[$key],
                    'return_sub_total' => $request->return_sub_total[$key],
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ];
            }
        }

        $this->saleReturnItems->newQuery()->insert($items_for_table);

        return $this;
    }

    /**
     * stockUpdate
     *
     * @param  mixed $request
     * @return void
     */
    private function stockUpdate($request, $id = null)
    {
        $w_id = request('warehouse_id') ? request('warehouse_id') : optional(Warehouse::query()->where('is_default', true)->first())->id;
        if (!$w_id) {
            throw ValidationException::withMessages(['message' => __('Select a warehouse first')]);
        }

        if (isset($request->product_stock_id) && is_array($request->product_stock_id)) {
            foreach ($request->product_stock_id as $key => $product_stock_id) {
                if ($request->return_qty[$key]) {
                    $stock = $this->getStock($product_stock_id, $w_id);
                    if ($stock && $stock->warehouse_id == $w_id) {
                        $stock->update([
                            'quantity' => $stock->quantity + $request->return_qty[$key]
                        ]);
                    } else {
                        $product = $this->product->newQuery()->with('allStock')->findOrFail($request->product_id[$key]);
                        if ($product->is_variant == 0) {
                            $this->productStock->newQuery()->create([
                                'product_id'    => $request->product_id[$key],
                                'warehouse_id'  => $w_id,
                                'quantity'      => $request->return_qty[$key],
                                //                                'attribute_id'      => $request->return_qty[$key],
                                //                                'attribute_item_id'      => $request->return_qty[$key],
                                'created_by'    => auth()->id(),
                                'updated_by'    => auth()->id(),
                            ]);
                        } else {
                            foreach ($product->allStock as $p_stock) {
                                $this->productStock->newQuery()->create([
                                    'product_id'            => $request->product_id[$key],
                                    'warehouse_id'          => $w_id,
                                    'attribute_id'          => $p_stock->attribute_id,
                                    'attribute_item_id'     => $p_stock->attribute_item_id,
                                    'created_by'            => auth()->id(),
                                    'updated_by'            => auth()->id(),
                                ]);
                            }
                            $stock = $this->productStock->newQuery()
                                ->where('warehouse_id', $w_id)
                                ->where('product_id', $request->product_id[$key])
                                ->where('attribute_id', $request->attribute_id[$key])
                                ->where('attribute_item_id', $request->attribute_item_id[$key])
                                ->first();

                            $stock->update([
                                'quantity' => $request->return_qty[$key]
                            ]);
                        }
                    }

                    $productStock = $this->product->newQuery()->where('id', $request->product_id[$key])->first();
                    $this->productStockHistoryCreate(
                        $stock->id,
                        $stock->warehouse_id,
                        $stock->product_id,
                        SaleReturn::class,
                        $id,
                        $request->return_qty[$key],
                        ProductStockHistory::TYPE_IN,
                        ProductStockHistory::ACTION_FROM_INVOICE_RETURN
                    );
                    $productStock->update([
                        'stock' => $productStock->stock + $request->return_qty[$key]
                    ]);
                }
            }
        }
    }


    /**
     * getStock
     *
     * @param  mixed $product
     * @return void
     */
    public function getStock($product_stock_id, $warehouse_id = null)
    {

        if ($warehouse_id) {
            $defaultWarehouse = $warehouse_id;
        } elseif (request('warehouse_id')) {
            $defaultWarehouse = request('warehouse_id');
        } else {
            $defaultWarehouse = Warehouse::query()->where('is_default', true)->first();
            if ($defaultWarehouse) {
                $defaultWarehouse = $defaultWarehouse->id;
            } else {
                throw ValidationException::withMessages(['message' => __('Select a warehouse first')]);
            }
        }
        return $this->productStock->newQuery()
            ->where('id', $product_stock_id)
            ->where('warehouse_id', $defaultWarehouse)
            ->first();
        //
        //        return $this->productStock
        //            ->newQuery()
        //            ->where('product_id', $product)
        //            ->where('warehouse_id', $defaultWarehouse)
        //            ->first();
    }
}
