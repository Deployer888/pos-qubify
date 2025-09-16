<?php

namespace App\Http\Requests;

use App\Models\Supplier;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
            'email' => ['bail', 'required', 'email', 'max:100', Rule::unique('suppliers')->ignore($this->supplier)],
            'phone' => ['bail', 'required', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'company' => ['bail', 'nullable', 'max:200'],
            'designation' => ['bail', 'nullable', 'max:200'],
            'address_line_1' => ['bail', 'nullable', 'max:255'],
            'address_line_2' => ['bail', 'nullable', 'max:255'],
            'country' => ['bail', 'nullable', 'numeric'],
            'state' => ['bail', 'nullable', 'numeric'],
            'city' => ['bail', 'nullable', 'numeric'],
            'zipcode' => ['bail', 'nullable', 'max:20'],
            'short_address' => ['bail', 'nullable', 'max:1000'],
            'avatar' => ['bail', 'nullable', 'image', 'mimes:jpeg,jpg,png', 'max:300'],
            'status' => ['bail', 'required', Rule::in([Supplier::STATUS_ACTIVE, Supplier::STATUS_INACTIVE])]
        ];
    }
}
