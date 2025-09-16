<?php

namespace App\Http\Controllers\Api\v100\Admin;

use DB;
use Throwable;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Http\Resources\InvoiceResource;
use App\Services\Invoice\InvoiceService;
use Illuminate\Support\Facades\Validator;
use App\Services\Customer\CustomerService;
use App\Http\Resources\InvoiceDetailsResource;

class InvoiceController extends Controller
{
    use ApiReturnFormatTrait;
    protected $invoiceService;
    protected $customerService;

    public function __construct(
        InvoiceService $invoiceService,
        CustomerService $customerService
    )
    {
        $this->invoiceService   = $invoiceService;
        $this->customerService  = $customerService;
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
            'walkin_customer'   => ['nullable'],
            'is_delivered'      => ['nullable'],
            'billing'           => ['nullable', 'array'],
            'shipping'          => ['nullable', 'array'],
            'tax'               => ['numeric'],
            'discount'          => ['numeric'],
            'discount_type'     => ['nullable', 'string', Rule::in([Invoice::DISCOUNT_FIXED, Invoice::DISCOUNT_PERCENT])],
            'payment_type'      => ['required'],
            'total_paid'        => ['nullable', 'numeric', 'between:0,99999999.99'],
            'bank_info'         => ['nullable'],
            'notes'             => ['nullable', 'max:200'],
            'status'            => ['nullable', Rule::in(array_keys(Invoice::INVOICE_ALL_STATUS))],
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
    public function update(Request $request,$id){

        $validator          = Validator::make($request->all(), [
            'date'              => ['required'],
            'warehouse_id'      => ['required', 'exists:warehouses,id'],
            'due_date'          => ['nullable'],
            'customer_id'       => ['required', 'numeric'],
            'walkin_customer'   => ['nullable'],
            'is_delivered'      => ['nullable'],
            'billing'           => ['nullable', 'array'],
            'shipping'          => ['nullable', 'array'],
            'tax'               => ['numeric'],
            'discount'          => ['numeric'],
            'discount_type'     => ['nullable', 'string', Rule::in([Invoice::DISCOUNT_FIXED, Invoice::DISCOUNT_PERCENT])],
            'payment_type'      => ['required'],
            'total_paid'        => ['nullable', 'numeric', 'between:0,99999999.99'],
            'bank_info'         => ['nullable'],
            'notes'             => ['nullable', 'max:200'],
            'status'            => ['nullable', Rule::in(array_keys(Invoice::INVOICE_ALL_STATUS))],
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

        $invoice            = $this->invoiceService->storeOrUpdate($data,$id);

        return $this->responseWithSuccess('Invoice Updated',$invoice);
    }

    public function show($id){
        $invoice = $this->invoiceService->get($id, ['items', 'payments','customerInfo','saleReturns','warehouse:id,name']);
        // $invoice['items']['return_quantity'] = $invoice['items'] ? $invoice['items'][0]->returnQuantity() : null;
        // return $this->responseWithSuccess('Invoice Details',$invoice);
        return $this->responseWithSuccess('Invoice Details',new InvoiceDetailsResource($invoice));

    }
    public function download($id){
        return $this->invoiceService->download($id);
    }
    public function getPayments($invoice_id)
    {

        $payment = $this->invoiceService->getPayments($invoice_id);
        return $this->responseWithSuccess('Payment Details',$payment);

    }
    public function deliveryStatusChange($id,$status)
    {
        $this->invoiceService->deliveryStatusChange($id,$status);

        return $this->responseWithSuccess(__('custom.invoice_delivered_status_changed_successfully'));
    }
    public function delete($id){
        try {
           if ($this->invoiceService->deleteInvoiceByAPI($id)){
            return $this->responseWithSuccess(__('custom.invoice_deleted_successful'));
           }
           return $this->responseWithError(__('custom.invoice_deleted_failed'));

        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }

    }
    public function makePaymentPost(Request $request, $id)
    {
        try {
            if($this->invoiceService->makePayment($id, $request->all())){
                return $this->responseWithSuccess(__('custom.payment_make_successfully'));
            }
            return $this->responseWithError(__('custom.payment_make_failed'));
        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());
        }


    }
    public function invoiceCustomerEmail($id)
    {
        $email = $this->invoiceService->invoiceCustomerEmail($id);

        return $this->responseWithSuccess('Customer Email',$email);

    }
    public function sendInvoice(Request $request)
    {

       $validator          = Validator::make($request->all(), [
        'invoice_id'       => ['required', 'numeric'],
        'email'   =>         ['required','email'],

    ]);

    if ($validator->fails()) {
        return $this->responseWithError($validator->errors()->first(), $validator->errors(),422);
    }
    $data = $validator->validated();
        try {
            $this->invoiceService->sendInvoice($data);

            return $this->responseWithSuccess(__('custom.invoice_added_successful'));
        } catch (Throwable $th) {
            return $this->responseWithError(__('custom.invoice_added_failed'),$th->getMessage());
        }
    }


}
