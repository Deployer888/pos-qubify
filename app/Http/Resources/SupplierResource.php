<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
         'fist_name' => $this->first_name,
         'last_name' => $this->last_name,
         'email' => $this->email,
         'phone' => $this->phone,
         'status' => $this->status,
         'avatar' => $this->avatar,
         'avatar_url' => $this->avatar_url
        ];
    }
}
