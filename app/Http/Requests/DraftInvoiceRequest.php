<?php

namespace App\Http\Requests;

use App\Models\DraftInvoice;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DraftInvoiceRequest extends FormRequest
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
            'date'              => ['required'],
            'warehouse_id'      => ['required', 'exists:warehouses,id'],
            'due_date'          => ['nullable'],
            'customer_id'       => ['nullable', 'numeric'],
            'walkin_customer'   => ['nullable'],
            'billing'           => ['nullable', 'array'],
            'shipping'          => ['nullable', 'array'],
            'tax'               => ['numeric'],
            'discount'          => ['numeric'],
            'discount_type'     => ['nullable', 'string', Rule::in([DraftInvoice::DISCOUNT_FIXED, DraftInvoice::DISCOUNT_PERCENT])],
            'payment_type'      => ['required'],
            'total_paid'        => ['nullable', 'numeric', 'between:0,99999999.99'],
            'bank_info'         => ['nullable'],
            'notes'             => ['nullable', 'max:200'],
            'status'            => ['nullable', Rule::in(array_keys(DraftInvoice::INVOICE_ALL_STATUS))],
            'items'             => ['array'],
            'items.*.id' => ['nullable'],
            'items.*.attribute' => ['nullable', 'array'],
            'items.*.attribute_item' => ['nullable', 'array'],
            'items.*.is_variant' => ['nullable'],
            'items.*.product_id' => ['nullable'],
            'items.*.split_sale' => ['nullable'],
            'items.*.sku' => ['nullable'],
            'items.*.stock' => ['nullable'],
            'items.*.tax_status' => ['nullable'],
            'items.*.custom_tax' => ['nullable'],
            'items.*.discount' => ['nullable'],
            'items.*.discount_type' => ['nullable'],
            'items.*.name' => ['required'],
            'items.*.quantity' => ['required'],
            'items.*.price' => ['required']
        ];
    }
}
