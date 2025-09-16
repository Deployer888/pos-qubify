<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'phone'         => $this->phone,
            'email'         => $this->email,
            'company_name'  => $this->company_name,
            'address_1'     => $this->address_1,
            'address_2'     => $this->address_2,
            'priority'      => $this->priority,
            'is_default'    => $this->is_default,
            'status'        => $this->status,
            'product_stocks' => $this->product_stocks

            ];
    }
}
