<?php

namespace App\Http\Requests;

use App\Models\Manufacturer;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ManufacturerRequest extends FormRequest
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
            'name' => ['bail', 'required', 'min:1', 'max:200', Rule::unique('manufacturers')->ignore($this->manufacturer)],
            'desc' => ['bail', 'nullable', 'max:255'],
            'image' => ['bail', 'nullable', 'image', 'mimes:jpeg,jpg,png', 'max:300'],
            'status' => ['bail', 'required', Rule::in([Manufacturer::STATUS_ACTIVE, Manufacturer::STATUS_INACTIVE])]
        ];
    }
}
