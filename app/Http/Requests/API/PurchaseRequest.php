<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PurchaseRequest extends FormRequest
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
            'supplier' => 'required|exists:suppliers,id',
            'warehouse' => 'required|exists:warehouses,id',
            'company' => 'nullable|string|max:200',
            'date' => 'required|date_format:Y-m-d',
            'address_line_1' => 'nullable|string|max:200',
            'address_line_2' => 'nullable|string|max:200',
            'country' => 'nullable|exists:system_countries,id',
            'state' => 'nullable|exists:system_states,id',
            'city' => 'nullable|exists:system_cities,id',
            'zipcode' => 'nullable|digits_between:0,8',
            'note' => 'nullable|max:1000',
            'product_id.*' => 'required|exists:products,id',
            'product_stock_id.*' => 'required|exists:product_stocks,id',
            'quantity.*' => 'required|numeric|between:0,99999999.99',
            'note.*' => 'nullable|string|max:200',
            'price.*' => 'required|numeric|between:0,99999999.99',
            'total' => 'required|numeric',
            'product_id' => 'required|array',
            'product_stock_id' => 'required|array',
            'quantity' => 'required|array',
            'price' => 'required|array',
            'short_address' => 'nullable|string|max:1000',
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
