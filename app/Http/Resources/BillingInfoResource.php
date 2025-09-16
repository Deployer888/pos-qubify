<?php

namespace App\Http\Resources;

use App\Models\SystemCity;
use App\Models\SystemState;
use App\Models\SystemCountry;
use Illuminate\Http\Resources\Json\JsonResource;

class BillingInfoResource extends JsonResource
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
            'name' => optional($this)->name ,
            'email' => optional($this)->email,
            'phone' => optional($this)->phone,
            'address_line_1' => optional($this)->address_line_1 ,
            'address_line_2' => optional($this)->address_line_2,
            'country' => new CountriesResource($this->country),
            'state' => new StatesResource($this->state),
            'city' =>new CitiesResource($this->city),
            'zip' => optional($this)->zip,

        ];
    }
}
