<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeItemsResource extends JsonResource
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
            'attribute_id' => $this->attribute_id,
            'name'=> $this->name,
            'color' => $this->color,
            'image' => $this->image,
            'file_url' => $this->file_url
        ];
    }
}
