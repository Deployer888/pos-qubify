<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DropdownCustomerResource extends JsonResource
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
            'full_name' => $this->full_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'is_verified' => $this->is_verified,
            'avatar_url' => $this->avatar_url,
            'address_line_1'=> $this->address_line_1,
            'address_line_2'=> $this->address_line_2,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'zipcode' => $this->zipcode,
            'billing_same' => $this->billing_same,
            'b_first_name' => $this->b_first_name,
            'b_last_name' => $this->b_last_name,
            'b_email' => $this->b_email,
            'b_phone' => $this->b_phone,
            'b_address_line_1' => $this->b_address_line_1,
            'b_address_line_2' => $this->b_address_line_2,
            'b_country' => $this->b_country,
            'b_state' => $this->b_state,
            'b_city' => $this->b_city,
            'b_zipcode' => $this->b_zipcode,
            'avatar' => $this->avatar,
            'status' => $this->status






            // Add other fields as needed
        ];
    }
}
