<?php

namespace App\Http\Requests\API;

use App\Rules\OneDataInArray;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'date' => ['required', 'date'],
            'data' => ['required', 'array', new OneDataInArray],
            'notes' => ['nullable', 'max:500'],
            'expense_user' => ['nullable', 'numeric', 'exists:users,id'],
            'files.*' => ['nullable']
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
