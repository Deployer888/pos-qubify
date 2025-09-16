<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Models\ExpensesCategory;
use Illuminate\Foundation\Http\FormRequest;

class ExpensesCategoryRequest extends FormRequest
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
            'name' => ['bail', 'required', 'min:1', 'max:200', Rule::unique('expenses_categories')->ignore($this->expenses_category)],
            'desc' => ['bail', 'nullable', 'max:255'],
            'status' => ['bail', 'required', Rule::in([ExpensesCategory::STATUS_ACTIVE, ExpensesCategory::STATUS_INACTIVE])]
        ];
    }
}
