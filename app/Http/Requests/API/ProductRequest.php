<?php

namespace App\Http\Requests\API;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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

        return [
            'category_id' => ['bail', 'required', 'numeric','exists:product_categories,id'],
            'name' => ['bail', 'required', 'min:1', 'max:200', Rule::unique('products')->ignore($this->id)],
            'sku' => ['bail', 'required', 'min:1', 'max:200', Rule::unique('products')->ignore($this->id)],
            'barcode' => ['bail', 'required', 'min:1', 'max:200', Rule::unique('products')->ignore($this->id)],
            'barcode_image' => ['nullable'],
            'brand_id' => ['bail', 'nullable', 'numeric','exists:brands,id'],
            'manufacturer_id' => ['bail', 'nullable', 'numeric','exists:manufacturers,id'],
            'model' => ['bail', 'nullable', 'max:200'],
            'price' => ['bail', 'required', 'regex:/^(\d+(\.\d*)?)|(\.\d+)$/'],
            'weight' => ['bail','nullable', 'numeric', 'between:0,99999999.99'],
            'weight_unit_id' => ['bail', 'nullable', 'numeric', 'between:0,99999999.99','exists:weight_units,id'],
            'dimension_l' => ['bail', 'nullable', 'numeric', 'between:0,99999999.99'],
            'dimension_w' => ['bail', 'nullable', 'numeric', 'between:0,99999999.99'],
            'dimension_d' => ['bail', 'nullable', 'numeric', 'between:0,99999999.99'],
            'measurement_unit_id' => ['bail', 'nullable', 'numeric','exists:measurement_units,id'],
            'notes' => ['bail', 'nullable', 'max:255'],
            'desc' => ['bail', 'nullable', 'max:10000'],
            'thumb' => ['bail', 'nullable'],
            'is_variant' => ['bail', 'required', 'boolean'],
            'split_sale' => ['bail', 'nullable', 'boolean'],
            'attribute_data' => ['bail', 'nullable'],
            'tax_status' => ['bail', 'required'],
            'custom_tax' => ['bail', 'nullable', 'numeric', 'between:0,99999999.99'],
            'status' => ['bail', 'required', Rule::in([Product::STATUS_ACTIVE, Product::STATUS_INACTIVE])],
            'available_for' => ['bail', 'required', Rule::in(Product::SALE_AVAILABLE_FOR)],
            'customer_buying_price' => ['bail', 'nullable','regex:/^(\d+(\.\d*)?)|(\.\d+)$/'],
        ];
    }
    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'status' => false,
            'message' => $errors->first(),
            'data' => $errors->messages() ,
        ], 422);

        throw new HttpResponseException($response);
    }
}
