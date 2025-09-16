<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseReceiveOrReturnResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'purchase_number' => $this->purchase ? $this->purchase->purchase_number : null,
            'purchase_number_link' => route('admin.purchases.show',$this->id),
            'supplier_name' => $this->purchase ? $this->purchase->supplier->full_name : null,
            'warehouse_name' => $this->purchase ? $this->purchase->warehouse->name : null,
            'total' => $this->total,
            'receive_date' => $this->whenLoaded('purchaseItemReceives', function () {
                return $this->receive_date;
            }),
            'return_date' => $this->whenLoaded('purchaseReturnItems', function () {
                return $this->return_date;
            }),
            'total_received_product' => $this->whenLoaded('purchaseItemReceives', function () {
                return $this->purchaseItemReceives->count();
            }),
            'total_returned_product' => $this->whenLoaded('purchaseReturnItems', function () {
                return $this->purchaseReturnItems->count();
            })
        ];
    }
}
