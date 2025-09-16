<?php

namespace App\Http\Requests;

use App\Models\ProductCategory;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryRequest extends FormRequest
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
            'name' => ['bail', 'required', 'min:1', 'max:200', Rule::unique('product_categories')->ignore($this->product_category)],
            'desc' => ['bail', 'nullable', 'max:255'],
            'image' => ['bail', 'nullable', 'image', 'mimes:jpeg,jpg,png', 'max:300'],
            'parent_id' => ['bail', 'nullable', 'numeric'],
            'status' => ['bail', 'required', Rule::in([ProductCategory::STATUS_ACTIVE, ProductCategory::STATUS_INACTIVE])]
        ];
    }
}
