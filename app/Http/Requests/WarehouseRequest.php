<?php

namespace App\Http\Requests;

use App\Models\Warehouse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class WarehouseRequest extends FormRequest
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
            'name' => ['bail', 'required', 'min:1', 'max:200', Rule::unique('warehouses')->ignore($this->warehouse)],
            'email' => ['bail', 'nullable', 'email'],
            'phone' => ['bail', 'nullable', 'max:20'],
            'company_name' => ['bail', 'nullable', 'max:255'],
            'address_1' => ['bail', 'nullable', 'max:255'],
            'address_2' => ['bail', 'nullable', 'max:255'],
            'barcode' => ['bail', 'required', 'max:13', Rule::unique('warehouses')->ignore($this->warehouse)],
            'barcode_image' => ['bail', 'nullable'],
            'priority' => ['numeric', 'max:100'],
            'is_default' => ['boolean'],
            'status' => ['bail', 'required', Rule::in([Warehouse::STATUS_ACTIVE, Warehouse::STATUS_INACTIVE])]
        ];
    }
}
