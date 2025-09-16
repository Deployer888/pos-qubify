<?php

namespace App\Services\Product;

use App\Models\DraftInvoiceItem;
use App\Models\InvoiceItem;
use App\Models\ProductStockHistory;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseItemReceive;
use App\Models\PurchaseReceive;
use App\Services\Purchase\PurchaseServices;
use App\Traits\ProductStockHistoryTrait;
use Illuminate\Support\Facades\DB;
use Throwable;
use App\Models\Product;
use App\Models\ProductStock;
use App\Services\BaseService;
use Illuminate\Validation\Rule;

/**
 * ProductStockService
 */
class ProductStockService extends BaseService
{
    use ProductStockHistoryTrait;
    /**
     * __construct
     *
     * @param mixed $model
     * @return void
     */
    public function __construct(ProductStock $model)
    {
        parent::__construct($model);
    }

    /**
     * getProductStock
     *
     * @param mixed $id
     * @return void
     */
    public function getProductStock($id)
    {
        return $this->model::where('product_id', $id)->get();
    }

    /**
     * variantStockUpdate
     *
     * @param mixed $data
     * @param mixed $id
     * @return void
     */
    public function variantStockUpdate(array $data, $id)
    {
        DB::beginTransaction();
        try {
            // Product
            $product = Product::find($id);
            $update_stock = 0;
            $old_stocks = $this->getProductStock($id);
            if(blank($old_stocks) && isset($data['supplier_id'])) {
                $this->insertPurchaseStock($data, $id);
            }
            // Delete old data
//            $product_invoice_stock_ids = InvoiceItem::where('product_id', $id)->pluck('product_stock_id')->toArray();
//            $product_draft_invoice_stock_ids = DraftInvoiceItem::where('product_id', $id)->pluck('product_stock_id')->toArray();
//
//            $used_stock_ids = array_unique(array_merge($product_invoice_stock_ids, $product_draft_invoice_stock_ids));
//            dd($used_stock_ids);
//            $this->model::where('product_id', $id)->delete();
            // Store new stock
            foreach ($data['warehouse_stock'] as $item) {
                if (isset($item['warehouse'])) {
                    if (isset($item['quantity'])) {
                        foreach ($item['quantity'] as $key => $value) {
                            foreach ($value as $key2 => $value2) {
//                                $request->validate([
//                                    'sku'       => [Rule::unique('product_stocks')->ignore($item['product_stock_id'][$key][$key2])],
//                                    'barcode'   => [Rule::unique('product_stocks')->ignore($item['product_stock_id'][$key][$key2])],
//                                ]);
//                                $stock = new ProductStock();

                                $stock = ProductStock::where('product_id', $id)
                                    ->where('warehouse_id', $item['warehouse'])
                                    ->where('attribute_id', $key)
                                    ->where('attribute_item_id', $key2)
                                    ->first();
                                if (blank($stock)) {
                                    $stock = new ProductStock();
                                }

                                $stock->product_id = $id;
                                $stock->warehouse_id = $item['warehouse'];
                                $stock->attribute_id = $key;
                                $stock->attribute_item_id = $key2;

                                $stock->price                   = $item['price'][$key][$key2];
                                $stock->customer_buying_price   = $item['customer_buying_price'][$key][$key2];

                                $stock->quantity = $item['adjust_type'] == "Add"
                                    ? $item['stock'][$key][$key2] + $value2
                                    : ($item['adjust_type'] == "Subtract"
                                        ? $item['stock'][$key][$key2] - $value2
                                        : $item['stock'][$key][$key2]);
                                $stock->save();

                                $inOrOut = $item['adjust_type'] == "Add" ? ProductStockHistory::TYPE_IN : ProductStockHistory::TYPE_OUT;
                                $this->productStockHistoryCreate(
                                    $stock->id,
                                    $stock->warehouse_id,
                                    $stock->product_id,
                                    ProductStock::class,
                                    $stock->product_id,
                                    $stock->quantity,
                                    $inOrOut,
                                    ProductStockHistory::ACTION_FROM_STOCK_UPDATE
                                );

                                // Update stock
                                $update_stock += $item['adjust_type'] == "Add"
                                    ? $item['stock'][$key][$key2] + $value2
                                    : ($item['adjust_type'] == "Subtract"
                                        ? $item['stock'][$key][$key2] - $value2
                                        : $item['stock'][$key][$key2]);
                            }
                        }
                    }
                }
            }
            $product->stock = $update_stock;
            $product->stock_alert_quantity = $data['alert_quantity'];
            $product->save();
            DB::commit();
            return true;
        } catch (Throwable $th) {
            DB::rollBack();
            return false;
        }
    }


    /**
     * normalStockUpdate
     *
     * @param mixed $data
     * @param mixed $id
     * @return void
     */
    public function normalStockUpdate(array $data, $id)
    {
        DB::beginTransaction();
        try {
            // Product
            $product = Product::find($id);
            $update_stock = 0;
            $old_stocks = $this->getProductStock($id);
            if(blank($old_stocks) && isset($data['supplier_id'])) {
                $this->insertPurchaseStock($data, $id);
            }
            // Delete old data
//            $this->model::where('product_id', $id)->delete();
            // Store new stock
            foreach ($data['warehouse_stock'] as $item) {
                if (isset($item['warehouse'])) {

                    $stock = ProductStock::where('product_id', $id)
                        ->where('warehouse_id', $item['warehouse'])
                        ->first();
                    if (blank($stock)) {
                        $stock = new ProductStock();
                    }
                    $stock->product_id = $id;
                    $stock->warehouse_id = $item['warehouse'];

                    $stock->price                   = $item['price'];
                    $stock->customer_buying_price   = $item['customer_buying_price'];
//                    $stock->sku                     = $item['sku'];
//                    $stock->barcode                 = $item['barcode'];

                    $stock->quantity = $item['adjust_type'] == "Add"
                        ? $item['stock'] + $item['quantity']
                        : ($item['adjust_type'] == "Subtract" ? $item['stock'] - $item['quantity'] : $item['stock']);
                    $stock->save();

                    // Update stock
                    $update_stock += $item['adjust_type'] == "Add"
                        ? $item['stock'] + $item['quantity']
                        : ($item['adjust_type'] == "Subtract" ? $item['stock'] - $item['quantity'] : $item['stock']);
                }
            }

            $product->stock = $update_stock;
            $product->stock_alert_quantity = $data['alert_quantity'];
            $product->save();

            $inOrOut = $item['adjust_type'] == "Add" ? ProductStockHistory::TYPE_IN : ProductStockHistory::TYPE_OUT;
            $this->productStockHistoryCreate(
                $stock->id,
                $stock->warehouse_id,
                $stock->product_id,
                ProductStock::class,
                $stock->product_id,
                $stock->quantity,
                $inOrOut,
                ProductStockHistory::ACTION_FROM_STOCK_UPDATE
            );

            DB::commit();
            return true;

        } catch (Throwable $th) {
            DB::rollBack();
            return false;
        }
    }
    protected function insertPurchaseStock($data, $id)
    {
        $product = Product::find($id);
        foreach ($data['warehouse_stock'] as $item) {
            if (isset($item['warehouse'])) {
                if(gettype($item['quantity']) == 'array'){
                    $total_qty = 0;
                    //total quantity
                    foreach ($item['quantity'] as $qtyarray) {
                        foreach ($qtyarray as $qty) {
                            $total_qty += $qty;
                        }
                    }
                    $purchase               = Purchase::create([
                        'purchase_number' => $data['supplier_id'] . date('His') . rand(3, 4),
                        'supplier_id'     => $data['supplier_id'],
                        'warehouse_id'    => $item['warehouse'],
                        'date'            => now(),
                        'notes'           => 'In house purchase',
                        'total'           => $product->price * $total_qty,
                        'status'          => Purchase::STATUS_CONFIRMED,
                        'received'        => 1,
                        'created_by'    => auth()->user()->id,
                    ]);
                    $purchaseItem           = PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'product_id'  => $id,
                        'quantity'    => $total_qty,
                        'price'       => $product->price,
                        'total'       => $product->price * $total_qty,
                        'created_by'    => auth()->user()->id,
                    ]);
                    $purchaseReceive        = PurchaseReceive::create([
                        'purchase_id'   => $purchase->id,
                        'receive_date'  => now(),
                        'total'         => $purchase->total,
                        'created_by'    => auth()->user()->id,
                    ]);
                    $purchaseReceiveItem    = PurchaseItemReceive::create([
                        'purchase_receive_id'       => $purchaseReceive->id,
                        'purchase_item_id'          => $purchaseItem->id,
                        'product_id'                => $id,
                        'quantity'                  => $total_qty,
                        'price'                     => $product->price,
                        'sub_total'                 => $product->price * $total_qty,
                    ]);



                }else{
                    $purchase               = Purchase::create([
                        'purchase_number' => $data['supplier_id'] . date('His') . rand(3, 4),
                        'supplier_id'     => $data['supplier_id'],
                        'warehouse_id'    => $item['warehouse'],
                        'date'            => now(),
                        'notes'           => 'In house purchase',
                        'total'           => $product->price * $item['quantity'],
                        'status'          => Purchase::STATUS_CONFIRMED,
                        'received'        => 1,
                        'created_by'    => auth()->user()->id,
                    ]);
                    $purchaseItem           = PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'product_id'  => $id,
                        'quantity'    => $item['quantity'],
                        'price'       => $product->price,
                        'total'       => $product->price * $item['quantity'],
                        'created_by'    => auth()->user()->id,
                    ]);
                    $purchaseReceive        = PurchaseReceive::create([
                        'purchase_id'   => $purchase->id,
                        'receive_date'  => now(),
                        'total'         => $purchase->total,
                        'created_by'    => auth()->user()->id,
                    ]);
                    $purchaseReceiveItem    = PurchaseItemReceive::create([
                        'purchase_receive_id'       => $purchaseReceive->id,
                        'purchase_item_id'          => $purchaseItem->id,
                        'product_id'                => $id,
                        'quantity'                  => $item['quantity'],
                        'price'                     => $product->price,
                        'sub_total'                 => $product->price * $item['quantity'],
                    ]);
                }
            }
        }
    }


    /**
     * storePurchaseItems
     *
     * @param  mixed $request
     * @return PurchaseServices
     */
    public function storePurchaseItems($request): PurchaseServices
    {
        if (isset($request->product_id) && is_array($request->product_id)) {
            $products = [];
            foreach ($request->product_id as $key => $product_id) {
                $products[] = [
                    'purchase_id' => $this->model->id,
                    'product_id'  => $product_id,
                    'quantity'    => $request->quantity[$key],
                    'price'       => $request->price[$key],
                    'note'        => $request->product_note[$key],
                    'sub_total'   => $request->sub_total[$key],
                    'created_by'  => auth()->id(),
                    'updated_by'  => auth()->id(),
                ];
            }

            $this->model->purchaseItems()->insert($products);
        }

        return $this;
    }

    public function updateByStock($data,$id){
        $total_qty = 0;
        try {
            $product = Product::find($id);
//            if($product->variant == 1) {
    //            $this->variantStockUpdate($data,$id);
//            }else{
                foreach ($data['stock_id'] as $key => $value) {
                    $product_stock              = ProductStock::find($value);
                    $product_stock->quantity    = $data['quantity'][$key];
                    $product_stock->save();

                    $total_qty += $data['quantity'][$key];

                    //todo: add history

                }

//            }
            $product->stock = $total_qty;
            $product->save();
            return true;

            } catch (Throwable $th) {
                return false;
            }
    }
}
