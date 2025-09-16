<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //dd($this);
        return [
                'id'           => $this->id,
                'name'         => $this->name,
                'permissions' => PermissionResource::collection($this->permissions)
        ];
    }
}
