<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SalesItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // dd('tes');
        return [
            "id"=> $this->id,
            "invoice_id"=> $this->invoice_id,
            "product_id"=> $this->product_id,
            "product_stock_id"=> $this->product_stock_id,
            "product_name"=> $this->product_name,
            "attribute_id" => $this->attribute_id,
            "attribute_item_id" => $this->attribute_item_id,
            "sku"=> $this->sku,
            "quantity"=> $this->quantity,
            "price"=> $this->price,
            "tax"=> $this->tax,
            "discount"=> $this->discount,
            "discount_type"=> $this->discount_type,
            "sub_total"=> $this->sub_total,
            "sales_return_items"=> $this->salesReturnItems
        ];
    }
}
