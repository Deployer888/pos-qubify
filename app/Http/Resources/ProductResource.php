<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'barcode_image' => $this->barcode_image,
            'category' => new CategoriesResource($this->category),
            'manufacturer' => new ManufacturerResource($this->manufacturer),
            'brand' => new BrandResource($this->brand),
            'measurement_unit' => new ManufacturerResource($this->measurement_unit),
            'weight_unit' => new WeightUnitResource($this->weight_unit),
            'model' => $this->model,
            'price' => $this->price,
            'weight' => $this->weight,
            'notes' => $this->notes,
            'desc' => $this->desc,
            'stock' => $this->weight_unit ? $this->stock .' '.$this->weight_unit->name : $this->stock,
            'status' => $this->status,
            'custom_tax' => $this->custom_tax,
            'dimension_l' => $this->dimension_l,
            'dimension_w' => $this->dimension_w,
            'dimension_d' => $this->dimension_d,
            'customer_buying_price' => $this->customer_buying_price,
            'custom_tax_amount' => $this->custom_tax,
            'tax' => $this->tax_status == \App\Models\Product::TAX_INCLUDED ? 'included' : 'excluded',
            'is_variant' => $this->is_variant ? 'Yes' : 'No',
            'is_split_sale' => $this->split_sale ? 'Yes' : 'No',
            'is_available_for' => $this->available_for,
            'image' => getStorageImage(\App\Models\Product::FILE_STORE_PATH, $this->thumb),
            // 'stock_quantity' => $this->getStockDetails($this,$warehouses),
            // 'product_create_info' => new ProductCreateResource($this['product_create_info']),
            // 'old_attribute_data' => json_decode($this['old_attribute_data'])
            // 'date_added' => $this->created_at->format('Y-m-d H:i:s'),
            // 'last_updated' => $this->updated_at->format('Y-m-d H:i:s'),
            // Add more custom keys as needed
        ];
    }


}

