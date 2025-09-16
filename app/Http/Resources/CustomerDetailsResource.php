<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $customer = $this['customer'];
        $invoices = $this['invoices'];
        $products = $this['products'];
        $not_paid_invoices = $this['not_paid_invoices'];
        return [
            'id' => $customer->id,
            'full_name' => $customer->full_name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'company' => $customer->company,
            'designation' => $customer->designation,
            'b_first_name' => $customer->b_first_name,
            'b_last_name' => $customer->b_last_name,
            'b_email' => $customer->b_email,
            'b_phone' => $customer->b_phone,
            'is_billing_same' => $customer->billing_same,
            'address_line_1' => $customer->address_line_1 ?? '',
            'address_line_2' => $customer->address_line_2,
            'city' => new CitiesResource($customer->systemCity),
            'state' => new StatesResource($customer->systemState),
            'country' => new CountriesResource($customer->SystemCountry),
            'zipcode' => $customer->zipcode,
            'short_address' => $customer->short_address,
            'b_address_line_1' => $customer->b_address_line_1 ?? '',
            'b_address_line_2' => $customer->b_address_line_2,
            'b_city' => new CitiesResource($customer->b_city_data),
            'b_state' => new StatesResource($customer->b_state_data),
            'b_country' => new CountriesResource($customer->b_country_data),
            'b_zipcode' => $customer->b_zipcode,
            'b_short_address' => $customer->b_short_address,
            'customer_status' => ucfirst($customer->status),
            'is_verified' => $customer->is_verified,
            'avatar' => $customer->avatar,
            'avatar_url' => $customer->avatar_url,
            'status' => $customer->status,
            'invoices' => CustomerInvoiceResource::collection($invoices),
            'products' => $products,
            'not_paid_invoices' => CustomerInvoiceResource::collection($not_paid_invoices),
        ];
    }
}
