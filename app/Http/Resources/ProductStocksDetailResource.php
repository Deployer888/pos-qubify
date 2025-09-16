<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductStocksDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {


            $product = $this['product'];
            $warehouses = $this['warehouses'];
            $old_stocks = $this['old_stocks'];
            $suppliers = $this['suppliers'];

        return [
            'product_details' => [
                'product'=> $product,
                // 'attributes'=> AttributeResource::collection($product->attributes),
                'weight_unit'=> $product->weight_unit,


            ],
                'old_stocks' => $old_stocks,
               'warehouses' => WarehouseResource::collection($warehouses),
                'suppliers' => SupplierResource::collection($suppliers),
        ];

    }
}
