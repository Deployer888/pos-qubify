<?php

namespace App\Http\Requests\API;

use App\Models\Customer;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerRequest extends FormRequest
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
            'first_name'                => ['required', 'max:100'],
            'last_name'                 => ['required', 'max:100'],
            'email'                     => ['required', 'email', 'max:100',  Rule::unique('customers')->ignore($this->customer)],
            'phone'                     => ['required', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'password'                  => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation'     => ['required', 'string', 'min:8'],
            'company'                   => ['nullable', 'max:200'],
            'designation'               => ['nullable', 'max:200'],
            'address_line_1'            => ['nullable', 'max:255'],
            'address_line_2'            => ['nullable', 'max:255'],
            'country'                   => ['nullable', 'numeric'],
            'state'                     => ['nullable', 'numeric'],
            'city'                      => ['nullable', 'numeric'],
            'zipcode'                   => ['nullable', 'max:20'],
            'short_address'             => ['nullable', 'string', 'max:1000'],
            'billing_same'              => ['boolean', 'max:20'],
            'b_first_name'              => ['nullable', 'max:100'],
            'b_last_name'               => ['nullable', 'max:100'],
            'b_email'                   => ['nullable', 'email', 'max:100'],
            'b_phone'                   => ['nullable', 'max:20'],
            'b_address_line_1'          => ['nullable', 'max:255'],
            'b_address_line_2'          => ['nullable', 'max:255'],
            'b_country'                 => ['nullable', 'numeric'],
            'b_state'                   => ['nullable', 'numeric'],
            'b_city'                    => ['nullable', 'numeric'],
            'b_zipcode'                 => ['nullable', 'max:20'],
            'b_short_address'           => ['nullable', 'string', 'max:1000'],
            'avatar'                    => ['nullable'],
            'status'                    => ['required', Rule::in([Customer::STATUS_ACTIVE, Customer::STATUS_INACTIVE])]
        ];
        if ($this->customer) {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
            $rules['password_confirmation'] = ['nullable', 'string', 'min:8'];
        }
        return $rules;
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
