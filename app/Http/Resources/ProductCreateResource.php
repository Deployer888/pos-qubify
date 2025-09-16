<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCreateResource extends JsonResource
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
        'categories' => CategoriesResource::collection($this['categories']),
        'brands' => BrandResource::collection($this['brands']),
        'manufacturers' => ManufacturerResource::collection($this['manufacturers']),
        'weight_units' => WeightUnitResource::collection($this['weight_units']),
        'measurement_units'=>MeasurementUnitResource::collection($this['measurement_units']),
        'attributes' => AttributeResource::collection($this['attributes']),
        'barcode' => $this['barcode'],
        'skuSetting' => $this['skuSetting']


       ];
    }
}
