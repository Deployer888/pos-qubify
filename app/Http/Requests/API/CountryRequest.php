<?php

namespace App\Http\Requests\API;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CountryRequest extends FormRequest
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

                'name'      => [
                    'required',
                    Rule::unique('system_countries', 'name')->ignore($this->route('country')),
                ],
                'shortname' => [
                    'required',
                    Rule::unique('system_countries', 'shortname')->ignore($this->route('country')),
                ],
                'phonecode' => [
                    'required',
                    Rule::unique('system_countries', 'phonecode')->ignore($this->route('country')),
                ],

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
