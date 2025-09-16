<?php

namespace App\Http\Requests\API;

use App\Models\ProductCategory;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


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
            'name' => ['bail', 'required', 'min:1', 'max:200', Rule::unique('product_categories')->ignore($this->category)],
            'desc' => ['bail', 'nullable', 'max:255'],
            'image' => ['bail', 'nullable'],
            'parent_id' => ['bail', 'nullable', 'numeric'],
            'status' => ['bail', 'required', Rule::in([ProductCategory::STATUS_ACTIVE, ProductCategory::STATUS_INACTIVE])]
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
