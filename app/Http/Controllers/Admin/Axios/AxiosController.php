<?php

namespace App\Http\Controllers\Admin\Axios;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\PurchaseItem;

class AxiosController extends Controller
{
    /**
     * productSearchNameSku
     *
     * @param  mixed $query
     * @return void
     */
    public function productSearchNameSku($query)
    {
        return Product::query()
            ->where('status', 'active')
            ->where('name', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->orWhere('barcode', 'like', "%{$query}%")
            ->get(['id', 'name', 'sku', 'price', 'is_variant']);
    }
    public function productStockSearchNameSku($query)
    {
        $results = ProductStock::query()
            ->with('product', 'attribute', 'attributeItem')
            ->whereHas('product', function ($q) use ($query) {
                $q->where('status', 'active')
                  ->where(function ($q2) use ($query) {
                      $q2->where('name', 'like', "%{$query}%")
                         ->orWhere('sku', 'like', "%{$query}%")
                         ->orWhere('barcode', 'like', "%{$query}%");
                  });
            })
            ->orderBy('id')
            ->get();

        // Group stock items by product
        $grouped = $results->groupBy('product_id');

        $filtered = collect();

        foreach ($grouped as $productId => $stocks) {
            $product = $stocks->first()->product;

            if ($product && $product->is_batch_product) {
                // Batch product: take only the first stock record
                $filtered->push($stocks->first());
            } else {
                // Non-batch product: take all stock records
                $filtered = $filtered->merge($stocks);
            }
        }

        return $filtered->values(); // reindex
    }


    /**
     * purchaseItemDelete
     *
     * @param  mixed $query
     * @return void
     */
    public function purchaseItemDelete($query)
    {
        PurchaseItem::query()->findOrFail($query)->delete();

        return true;
    }
}
