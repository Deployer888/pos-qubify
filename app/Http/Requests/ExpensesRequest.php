<?php

namespace App\Http\Requests;

use App\Rules\OneDataInArray;
use Illuminate\Foundation\Http\FormRequest;

class ExpensesRequest extends FormRequest
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
            'category' => ['required', 'numeric'],
            'title' => ['required', 'max:200'],
            'date' => ['required', 'max:200'],
            'data' => ['required', 'array', new OneDataInArray],
            'notes' => ['nullable', 'max:500'],
            'expense_user' => ['nullable', 'numeric', 'exists:users,id'],
            'files.*' => ['nullable']
        ];
    }
}
