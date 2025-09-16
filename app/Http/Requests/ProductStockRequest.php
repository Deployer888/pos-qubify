<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStockRequest extends FormRequest
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
        /*return [
            'alert_quantity' => ['bail', 'required', 'numeric', 'digits_between:0,8'],
            'warehouse_stock' => ['bail', 'required', 'array'],
        ];*/

        if ($this->is_variant){

            return [
                'alert_quantity' => ['bail', 'required', 'numeric', 'between:0,99999999.99'],
                'warehouse_stock' => ['bail', 'required', 'array'],
                'supplier_id' => ['sometimes','required','numeric'],
                'warehouse_stock.*.warehouse' =>  ['bail', 'required', 'exists:warehouses,id'],
                'warehouse_stock.*.quantity.*.*' =>  ['bail', 'nullable', 'numeric', 'between:0,99999999.99'],
                'warehouse_stock.*.price.*.*'                         => ['bail','nullable','numeric'],
                'warehouse_stock.*.customer_buying_price.*.*'         => ['bail','nullable','numeric'],
//                'warehouse_stock.*.sku.*.*'                           => ['bail', 'required', 'min:1'],
//                'warehouse_stock.*.barcode.*.*'                       => ['bail', 'required', 'min:1'],
//                'warehouse_stock.*.barcode_image.*.*'                 => ['nullable'],
            ];

        }else{
            return [
                'alert_quantity'                                    => ['bail', 'required', 'numeric', 'between:0,99999999.99'],
                'warehouse_stock'                                   => ['bail', 'required', 'array'],
                'warehouse_stock.*.warehouse'                       => ['bail', 'required', 'exists:warehouses,id'],
                'warehouse_stock.*.quantity'                        => ['bail', 'nullable', 'numeric', 'between:0,99999999.99'],
                'supplier_id'                                       => ['sometimes','required','numeric'],
                'warehouse_stock.*.price'                           => ['bail','nullable','numeric'],
                'warehouse_stock.*.customer_buying_price'           => ['bail','nullable','numeric'],
//                'warehouse_stock.*.sku'                             => ['bail', 'required', 'min:1'],
//                'warehouse_stock.*.barcode'                         => ['bail', 'required', 'min:1'],
//                'warehouse_stock.*.barcode_image'                   => ['nullable'],
            ];
        }
    }

    public function messages()
    {
        return [
            'warehouse_stock.*.warehouse.required' => 'Warehouse field is required',
            'warehouse_stock.*.warehouse.exist' => 'Selected Warehouse is invalid',
            'warehouse_stock.*.quantity.numeric' => 'Quantity must be a number',
            'warehouse_stock.*.quantity.digits_between' => 'Quantity must be between 0 and 8 digits.',

            'warehouse_stock.*.quantity.*.*.numeric' => 'Quantity must be a number',
            'warehouse_stock.*.quantity.*.*.digits_between' => 'Quantity must be between 0 and 8 digits.'

        ];
    }
}
