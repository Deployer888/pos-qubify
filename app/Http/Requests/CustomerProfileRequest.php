<?php

namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CustomerProfileRequest extends FormRequest
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
            'first_name' => ['bail', 'required', 'max:100'],
            'last_name' => ['bail', 'required', 'max:100'],
            'email' => ['bail', 'required', 'email', 'max:100', Rule::unique('customers')->ignore(auth()->guard('customer')->id())],
            'phone' => ['bail', 'required', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'company' => ['bail', 'nullable', 'max:200'],
            'designation' => ['bail', 'nullable', 'max:200'],
            'address_line_1' => ['bail', 'nullable', 'max:255'],
            'address_line_2' => ['bail', 'nullable', 'max:255'],
            'country' => ['bail', 'nullable', 'numeric'],
            'state' => ['bail', 'nullable', 'numeric'],
            'city' => ['bail', 'nullable', 'numeric'],
            'zipcode' => ['bail', 'nullable', 'max:20'],
            'short_address' => ['bail', 'nullable', 'string', 'max:1000'],
            'billing_same' => ['bail', 'boolean', 'max:20'],
            'b_first_name' => ['bail', 'nullable', 'max:100'],
            'b_last_name' => ['bail', 'nullable', 'max:100'],
            'b_email' => ['bail', 'nullable', 'email', 'max:100'],
            'b_phone' => ['bail', 'nullable', 'max:20'],
            'b_address_line_1' => ['bail', 'nullable', 'max:255'],
            'b_address_line_2' => ['bail', 'nullable', 'max:255'],
            'b_country' => ['bail', 'nullable', 'numeric'],
            'b_state' => ['bail', 'nullable', 'numeric'],
            'b_city' => ['bail', 'nullable', 'numeric'],
            'b_zipcode' => ['bail', 'nullable', 'max:20'],
            'b_short_address' => ['bail', 'nullable', 'string', 'max:1000'],
            'avatar' => ['bail', 'nullable', 'image', 'mimes:jpeg,jpg,png', 'max:300'],
        ];
    }
}
