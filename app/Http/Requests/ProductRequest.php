<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'category_id' => ['bail', 'required', 'numeric'],
            'name' => ['bail', 'required', 'min:1', 'max:200', Rule::unique('products')->ignore($this->product)],
            'sku' => ['bail', 'required', 'min:1', 'max:200', Rule::unique('products')->ignore($this->product)],
            'barcode' => ['bail', 'required', 'min:1', 'max:200', Rule::unique('products')->ignore($this->product)],
            'barcode_image' => ['nullable'],
            'brand_id' => ['bail', 'nullable', 'numeric'],
            'manufacturer_id' => ['bail', 'nullable', 'numeric'],
            'model' => ['bail', 'nullable', 'max:200'],
            'price' => ['bail', 'required', 'regex:/^(\d+(\.\d*)?)|(\.\d+)$/'],
            'weight' => ['bail','nullable', 'numeric', 'between:0,99999999.99'],
            'weight_unit_id' => ['bail', 'nullable', 'numeric', 'between:0,99999999.99'],
            'dimension_l' => ['bail', 'nullable', 'numeric', 'between:0,99999999.99'],
            'dimension_w' => ['bail', 'nullable', 'numeric', 'between:0,99999999.99'],
            'dimension_d' => ['bail', 'nullable', 'numeric', 'between:0,99999999.99'],
            'measurement_unit_id' => ['bail', 'nullable', 'numeric'],
            'notes' => ['bail', 'nullable', 'max:255'],
            'desc' => ['bail', 'nullable', 'max:10000'],
            'thumb' => ['bail', 'nullable', 'image', 'mimes:jpeg,jpg,png', 'max:300'],
            'is_variant' => ['bail', 'nullable','in:0,1'],
            'is_batch_product' => ['bail', 'nullable','in:0,1'],
            'split_sale' => ['bail', 'nullable', 'boolean'],
            'tax_status' => ['bail', 'required'],
            'custom_tax' => ['bail', 'nullable', 'numeric', 'between:0,99999999.99'],
            'status' => ['bail', 'required', Rule::in([Product::STATUS_ACTIVE, Product::STATUS_INACTIVE])],
            'available_for' => ['bail', 'required', Rule::in(Product::SALE_AVAILABLE_FOR)],
            'customer_buying_price' => ['bail', 'nullable','regex:/^(\d+(\.\d*)?)|(\.\d+)$/'],
        ];
         // Conditionally add the attribute_data rule
         if ($this->method()=='POST' &&  $this->is_variant == 1) {
            $rules['attribute_data'] = [
                'bail',
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    foreach ($value as $index => $item) {
                        // Check if "attribute" key exists and is not empty
                        if (!isset($item['attribute']) || empty($item['attribute'])) {
                            $fail('The attribute field is required for each item.');
                        }

                        // Check if "attribute_items" key exists and is not empty
                        if (!isset($item['attribute_items']) || empty($item['attribute_items'])) {
                            $fail('At least one item must be selected for each attribute.');
                        }
                    }
                },
            ];
        }

        return $rules;
    }
}
