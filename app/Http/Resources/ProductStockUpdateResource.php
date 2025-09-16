<?php

namespace App\Http\Resources;

use App\Models\Warehouse;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductStockUpdateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        if ($this->is_variant) {
            return [
                'is_variant' => true,
                'stock_info' => $this->allStock->groupBy('warehouse_id')->map(function ($warehouse) {
                    return [
                        'warehouse_name' => Warehouse::find($warehouse[0]->warehouse_id)->name,
                        'stocks' => $warehouse->map(function ($stock) {
                            return [
                                'attribute_name' => optional($stock->attribute)->name,
                                'attribute_item_name' => optional($stock->attributeItem)->name,
                                'stock_id' => $stock->id,
                                'quantity' => $stock->quantity,
                            ];
                        }),
                    ];
                })->values(),
            ];
        }

        return [
            'is_variant' => false,
            'stock_info' => StockResource::collection($this->allStock)

      ];

    }


    public function getVariantProductWarehouses(){

    }
    public function getNormalProductInfo(){

    }
}
