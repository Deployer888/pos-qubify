<?php

namespace App\Http\Requests;

use App\Models\Attribute;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AttributeRequest extends FormRequest
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
            'name' => ['bail', 'required', 'min:1', 'max:200', Rule::unique('attributes')->ignore($this->attribute)],
            'item_data' => 'bail|nullable|array',
            'status' => ['bail', 'required', Rule::in([Attribute::STATUS_ACTIVE, Attribute::STATUS_INACTIVE])]
        ];
    }
}
