<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleReturnCreateDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $sales = $this['sales'];
        $warehouse = $this['warehouse'];
        $customer = (object) $sales->customer;

        $billing_info = (object) $sales->billing_info;
        $shipping_info = (object) $sales->shipping_info;
        // dd($billing_info,$shipping_info);
        return [
            'sale_number' => make8digits($sales->id),
            'sale_date' => custom_date($sales->date) ,
            'customer_name' => optional($customer)->full_name,
            'customer_phone' => optional($customer)->phone,
            'customer_email' => optional($customer)->email ,
            'warehouse' => $warehouse->name,
            'billing_info' => new BillingInfoResource($billing_info),
            'shipping_info' => new ShippingInfoResource($shipping_info),
            'return_date' => date('Y-m-d'),
            'warehouses' => $this['warehouses'],
            'items' =>  SalesItemResource::collection($sales->items)
        ];
    }
}
