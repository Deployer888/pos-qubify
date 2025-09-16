<?php

namespace App\Http\Requests\API;

use App\Models\Coupon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CouponRequest extends FormRequest
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
            'title'             => ['bail', 'required', 'min:1', 'max:200', Rule::unique('coupons')->ignore($this->coupon)],
            'code'              => ['bail', 'required', 'max:100'],
            'minimum_shopping'  => ['bail', 'nullable','numeric'],
            'maximum_discount'  => ['bail', 'nullable','numeric'],
            'discount_type'     => ['bail', 'required', 'max:100'],
            'discount'          => ['bail', 'nullable','numeric'],
            'start_date'        => ['bail', 'nullable', 'max:100'],
            'end_date'          => ['bail', 'nullable', 'max:100'],
            'banner'            => ['bail', 'nullable'],
            'status'            => ['bail', 'required', Rule::in([Coupon::STATUS_ACTIVE, Coupon::STATUS_INACTIVE])]
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
