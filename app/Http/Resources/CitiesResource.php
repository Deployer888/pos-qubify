<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CitiesResource extends JsonResource
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
            'id'        => $this->id,
            'name'      => $this->name,
            'state' => new StatesResource($this->whenLoaded('state')),
            'country' => $this->when(
                $this->relationLoaded('state') &&
                $this->state->relationLoaded('country'),
                function () {
                    return new CountriesResource($this->state->country);
                }
            ),

        ];
    }
}
