<?php

namespace App\Http\Requests\API;

use App\Models\Brand;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BrandRequest extends FormRequest
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
            'name' => ['bail', 'required', 'min:1', 'max:200', Rule::unique('brands')->ignore($this->brand)],
            'desc' => ['bail', 'nullable', 'max:255'],
            'image' => ['bail', 'nullable'],
            'status' => ['bail', 'required', Rule::in([Brand::STATUS_ACTIVE, Brand::STATUS_INACTIVE])]
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
