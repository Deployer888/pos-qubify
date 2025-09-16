<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseReceiveOrReturnShowResource extends JsonResource
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
            'purchase_number' => $this->purchase->purchase_number,
            'supplier_name' => $this->purchase->supplier->full_name,
            'supplier_phone' => $this->purchase->supplier->phone,
            'warehouse_name' => $this->purchase->warehouse->name,
            'company' => $this->purchase->company,
            'receive_date' => $this->whenLoaded('purchaseItemReceives', function () {
                return $this->receive_date;
            }),
            'return_date' => $this->whenLoaded('purchaseReturnItems', function () {
                return $this->return_date;
            }),
            'total' =>make2decimal($this->total),
          //  'purchase_return_items' => PurchaseReturnItemResource::collection($this->purchaseReturnItems),
            'address_line_1' => optional($this->purchase)->address_line_1,
            'address_line_2' => optional($this->purchase)->address_line_2,
            'country' => optional(optional($this->purchase)->systemCountry)->name,
            'state' => optional(optional($this->purchase)->systemState)->name,
            'city' => optional(optional($this->purchase)->systemCity)->name,
            'short_address' => optional($this->purchase)->short_address,
            'received_items' => $this->whenLoaded('purchaseItemReceives', function () {
                return $this->purchaseItemReceives->load('product:id,name,sku');
            }),
            'returned_items' => $this->whenLoaded('purchaseReturnItems', function () {
                return $this->purchaseReturnItems->load('product:id,name,sku');
            }),


            // Add other fields as needed
        ];
    }


}
