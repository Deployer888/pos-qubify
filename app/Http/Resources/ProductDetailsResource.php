<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailsResource extends JsonResource
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
        return [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'barcode' => $product->barcode,
            'barcode_image' => $product->barcode_image,
            'category' => new CategoriesResource($product->category),
            'manufacturer' => new ManufacturerResource($product->manufacturer),
            'brand' => new BrandResource($product->brand),
            'measurement_unit' => new ManufacturerResource($product->measurement_unit),
            'weight_unit' => new WeightUnitResource($product->weight_unit),
            'model' => $product->model,
            'price' => $product->price,
            'weight' => $product->weight,
            'notes' => $product->notes,
            'desc' => $product->desc,
            'status' => $product->status,
            'custom_tax' => $product->custom_tax,
            'dimension_l' => $product->dimension_l,
            'dimension_w' => $product->dimension_w,
            'dimension_d' => $product->dimension_d,
            'customer_buying_price' => $product->customer_buying_price,
            'custom_tax_amount' => $product->custom_tax,
            'tax' => $product->tax_status == \App\Models\Product::TAX_INCLUDED ? 'included' : 'excluded',
            'is_variant' => $product->is_variant ? 'Yes' : 'No',
            'is_split_sale' => $product->split_sale ? 'Yes' : 'No',
            'is_available_for' => $product->available_for,
            'image' => getStorageImage(\App\Models\Product::FILE_STORE_PATH, $product->thumb),
            'stock_quantity' => $this->getStockDetails($product,$warehouses),
            'product_create_info' => new ProductCreateResource($this['product_create_info']),
            'old_attribute_data' => json_decode($this['old_attribute_data'])
            // 'date_added' => $product->created_at->format('Y-m-d H:i:s'),
            // 'last_updated' => $product->updated_at->format('Y-m-d H:i:s'),
            // Add more custom keys as needed
        ];
    }

    protected function getStockDetails($product,$warehouses)
    {
        $stockDetails = [];

        foreach ($product->allStock->groupBy('warehouse_id') as $key => $warehouseDetails) {
            foreach ($warehouseDetails as $wareHousekey => $stock) {
                $stockDetails[] = [
                    'warehouse' => $wareHousekey == 0 ? $warehouses[$key] : null,
                    'attribute' => $product->is_variant ? optional($stock->attribute)->name : null,
                    'attribute_item' => $product->is_variant ? optional($stock->attributeItem)->name : null,
                    'quantity' => $stock->quantity,
                ];
            }
        }

        return $stockDetails;
    }
}
