<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponProductsDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $coupon_products = $this['coupon_products'];
        $products = $this['products'];
        $coupon = $this['coupon'];

        $formattedProducts = ProductResource::collection($products)->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
            ];
        });

        return [
            'coupon_products' => CouponProductsResource::collection($coupon_products)->response()->getData(true),
            'products' => $formattedProducts->toArray(),
            'coupon_id' => $coupon->id,
        ];
    }

}
