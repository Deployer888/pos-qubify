<?php

namespace App\Http\Controllers\Api\v100\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\DraftInvoiceResource;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Services\Customer\CustomerService;
use App\Services\Invoice\DraftInvoiceService;
use App\Services\Invoice\InvoiceService;
use App\Traits\ApiReturnFormatTrait;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    use ApiReturnFormatTrait;

    protected $invoiceService;
    protected $customerService;
    protected $draftInvoiceService;

    public function __construct(
        InvoiceService $invoiceService,
        DraftInvoiceService $draftInvoiceService,
        CustomerService $customerService
    )
    {
        $this->invoiceService       = $invoiceService;
        $this->draftInvoiceService  = $draftInvoiceService;
        $this->customerService      = $customerService;
    }
    public function index(){
        $purchases = InvoiceResource::collection($this->invoiceService->getInvoiceList())->response()->getData(true);
        return $this->responseWithSuccess('Invoice List', $purchases);
    }
    public function create(Request $request){

        $validator          = Validator::make($request->all(), [
            'date'              => ['required'],
            'warehouse_id'      => ['required', 'exists:warehouses,id'],
            'due_date'          => ['nullable'],
            'customer_id'       => ['required', 'numeric'],
//            'walkin_customer'   => ['nullable'],
//            'is_delivered'      => ['nullable'],
            'billing'           => ['nullable', 'array'],
            'shipping'          => ['nullable', 'array'],
            'tax'               => ['numeric'],
            'discount'          => ['numeric'],
            'discount_type'     => ['nullable', 'string', Rule::in([Invoice::DISCOUNT_FIXED, Invoice::DISCOUNT_PERCENT])],
            'payment_type'      => ['required'],
            'total_paid'        => ['nullable', 'numeric', 'between:0,99999999.99'],
//            'bank_info'         => ['nullable'],
            'notes'             => ['nullable', 'max:200'],
//            'status'            => ['nullable', Rule::in(array_keys(Invoice::INVOICE_ALL_STATUS))],
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
            'items.*.name'      => ['required'],
            'items.*.quantity'  => ['required'],
            'items.*.price'     => ['required']
        ]);

        if ($validator->fails()) {
            return $this->responseWithError($validator->errors()->first(), [],422);
        }
        $data               = $validator->validated();
        // Customer
        $customer           = $this->customerService->get($data['customer_id']);
        $data['customer']   = $customer;

        if ($data['items']) {
            foreach ($data['items'] as $item) {
                $stockCheck = $this->invoiceService->stockCheck($item['id'], $item['quantity']);
                if (!$stockCheck) {
                    return $this->responseWithError('Stock not available', [], 307);
                }
            }
        }

        $invoice            = $this->invoiceService->storeOrUpdate($data);

        return $this->responseWithSuccess('Invoice Created',$invoice);
    }

    public function show($id){
        $invoice = $this->invoiceService->get($id, ['items', 'payments','customerInfo','saleReturns','warehouse:id,name']);
        if(!$invoice){
            return $this->responseWithError('Invoice not found',[],404);
        }
        if($invoice->customer_id != auth()->guard('api_customer')->user()->id){
            return $this->responseWithError('You are not authorized to view this invoice',[],403);
        }
        return $this->responseWithSuccess('Invoice Details',$invoice);
    }
    public function download($id){
        return $this->invoiceService->download($id);
    }

}
