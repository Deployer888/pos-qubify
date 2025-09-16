<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $supplier = $this['supplier'];
        $purchases = $this['purchases'];
        $products = $this['products'];
        return [
            'id' => $supplier->id,
            'full_name' => $supplier->full_name,
            'email' => $supplier->email,
            'phone' => $supplier->phone,
            'company' => $supplier->company,
            'designation' => $supplier->designation,
            'address_line_1' => $supplier->address_line_1 ?? '',
            'address_line_2' => $supplier->address_line_2,
            'city' => new CitiesResource($supplier->systemCity),
            'state' => new StatesResource($supplier->systemState),
            'country' => new CountriesResource($supplier->systemCountry),
            'zipcode' => $supplier->zipcode,
            'short_address' => $supplier->short_address,
            'supplier_status' => ucfirst($supplier->status),
            'avatar' => $supplier->avatar,
            'avatar_url' => $supplier->avatar_url,
            'purchases' => PurchaseHistoryResource::collection($purchases),
            'products' => $products
        ];
    }
}
