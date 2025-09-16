<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResource extends JsonResource
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
            "id"            => $this->id,
            "name"          => $this->name,
            "desc"          => $this->desc,
            "image"         => $this->image,
            "status"        => $this->status,
            "parent_id"     => $this->parent_id,
            "position"      => $this->position,
            "file_url"      => $this->file_url,
            "sub_category"  => $this->whenLoaded('subCategory', function () {
                return CategoriesResource::collection($this->subCategory);
            }),
        ];
    }
}
