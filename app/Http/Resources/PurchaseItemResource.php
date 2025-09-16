<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $variant_info = null;
        if($this->product->is_variant != null && $this->product->is_variant == 1 && isset($this->productStock))
        {
            $variant_info = (optional(optional($this->productStock)->attribute)->name ?? '') . ':' . (optional(optional($this->productStock)->attributeItem)->name ?? '');

        }
          return [
            'id' => $this->id,
            'product' => [
                'id' => $this->product->id,
                'sku' => $this->product->sku,
                'name' => $this->product->name,
                'variant_info' => $variant_info,
                'stock_id' => $this->product_stock_id,
            ],
            'quantity' => $this->quantity,
            'price' => make2decimal($this->price),
            'note' => $this->note,
            'sub_total' => make2decimal($this->sub_total),
            'receive_items' => $this->receiveItems,
            'return_items' => $this->returnItem,
            'stock_quantity' => $this->calculateStockQuantity($this),
            'available_quantity' => $this->calculateAvailableQuantity($this),
            'stock_available_quantity' => $this->calculateStockAvailableQuantity($this)

        ];
    }
    public function calculateStockAvailableQuantity($purchaseItem){
        $stockAvailableQty = 0;
        $stockQty = $this->calculateStockQuantity($purchaseItem);
        $availAbleQty = $this->calculateAvailableQuantity($purchaseItem);

        if ($stockQty > $availAbleQty){
            $stockAvailableQty = $availAbleQty;
            }else{
            $stockAvailableQty = $stockQty;
            }
            return $stockAvailableQty;

    }
    public function calculateStockQuantity($purchaseItem){
      return optional($purchaseItem->product)->warehouseStock($purchaseItem->purchase->warehouse->id);
    }
    public function calculateAvailableQuantity($purchaseItem){
     return $purchaseItem->receiveItems->sum('quantity') -
     optional($purchaseItem->returnItem)->sum('quantity');
    }
}
