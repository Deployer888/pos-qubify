<?php

namespace App\Traits;

use App\Models\ProductStockHistory;
use Illuminate\Support\Facades\Auth;

trait ProductStockHistoryTrait
{

    protected function productStockHistoryCreate(
        $product_stock_id,
        $warehouse_id,
        $product_id,
        $from_type,
        $from_id,
        $quantity,
        $type,
        $action_from
    ) {
        try {
//            $productStockHistory                    = new ProductStockHistory();
//
            $data = [
                'product_stock_id'  => $product_stock_id,
                'warehouse_id'      => $warehouse_id,
                'product_id'        => $product_id,
                'from_type'         => $from_type,
                'from_id'           => $from_id,
                'quantity'          => $quantity,
                'type'              => $type,
                'action_from'       => $action_from
            ];

                ProductStockHistory::create($data);

//            $productStockHistory->product_stock_id  = $product_stock_id;
//            $productStockHistory->warehouse_id      = $warehouse_id;
//            $productStockHistory->product_id        = $product_id;
//            $productStockHistory->from_type         = $from_type;
//            $productStockHistory->from_id           = $from_id;
//            $productStockHistory->quantity          = $quantity;
//            $productStockHistory->type              = $type;
//            $productStockHistory->action_from       = $action_from;
//            $productStockHistory->created_by        = Auth::user()->id;
//            $productStockHistory->save();

            return true;
        } catch (\Exception $e) {
            logger($e);
            return false;
        }
    }
}
