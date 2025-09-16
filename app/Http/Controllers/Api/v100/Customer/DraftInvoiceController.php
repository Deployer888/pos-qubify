<?php

namespace App\Http\Controllers\Api\v100\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\DraftInvoiceResource;
use App\Http\Resources\InvoiceResource;
use App\Models\DraftInvoice;
use App\Models\Invoice;
use App\Services\Customer\CustomerService;
use App\Services\Invoice\DraftInvoiceService;
use App\Services\Invoice\InvoiceService;
use App\Traits\ApiReturnFormatTrait;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DraftInvoiceController extends Controller
{
    use ApiReturnFormatTrait;

    protected $customerService;
    protected $draftInvoiceService;
    protected $invoiceService;

    public function __construct(
        InvoiceService $invoiceService,
        DraftInvoiceService $draftInvoiceService,
        CustomerService $customerService
    )
    {
        $this->draftInvoiceService  = $draftInvoiceService;
        $this->customerService      = $customerService;
        $this->invoiceService       = $invoiceService;
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
            'discount_type'     => ['nullable', 'string', Rule::in([DraftInvoice::DISCOUNT_FIXED, DraftInvoice::DISCOUNT_PERCENT])],
            'payment_type'      => ['required'],
            'total_paid'        => ['nullable', 'numeric', 'between:0,99999999.99'],
//            'bank_info'         => ['nullable'],
            'notes'             => ['nullable', 'max:200'],
//            'status'            => ['nullable', Rule::in(array_keys(Invoice::INVOICE_ALL_STATUS))],
            'items'             => ['array'],
            'items.*.name'      => ['required'],
            'items.*.quantity'  => ['required'],
            'items.*.price'     => ['required'],

            'items.*.attribute'         => ['required'],
            'items.*.attribute_item'    => ['required'],
            'items.*.is_variant'        => ['required'],
            'items.*.product_id'        => ['required']
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
        $draftInvoice            = $this->draftInvoiceService->storeOrUpdate($data);

        return $this->responseWithSuccess('Draft Invoice Created',$draftInvoice);
    }

    public function draftInvoiceList(){
        $draft_invoice = DraftInvoiceResource::collection($this->draftInvoiceService->getDraftInvoiceList())->response()->getData(true);
        return $this->responseWithSuccess('Draft Invoice List',$draft_invoice);
    }
    public function draftInvoiceDetails($id){
        $draft_invoice = $this->draftInvoiceService->get($id, ['items']);
        if(!$draft_invoice){
            return $this->responseWithError('Draft Invoice not found',[],404);
        }
        if($draft_invoice->customer_id != auth()->guard('api_customer')->user()->id){
            return $this->responseWithError('You are not authorized to view this invoice',[],403);
        }
        return $this->responseWithSuccess('Draft Invoice Details',$draft_invoice);
    }
    public function destroy($id)
    {
        $draftInvoice = $this->draftInvoiceService->get($id, ['items']);

        if(!$draftInvoice){
            return $this->responseWithError('Draft Invoice not found',[],404);
        }
        if ($draftInvoice->customer_id != auth()->guard('api_customer')->user()->id){
            return $this->responseWithError('You are not authorized to delete this invoice',[],403);
        }

        if ($draftInvoice->delete()) {
            return $this->responseWithSuccess('Draft Invoice Deleted');
        } else {
            return $this->responseWithError('Something went wrong');
        }
    }
    public function storeDraftToInvoice(Request $request, $id){
        $draft_invoice        = $this->draftInvoiceService->get($id, ['items']);
        if(!$draft_invoice){
            return $this->responseWithError('Draft Invoice not found',[],404);
        }
        if($draft_invoice->customer_id != auth()->guard('api_customer')->user()->id){
            return $this->responseWithError('You are not authorized for this draft invoice',[],403);
        }
        $validator          =  Validator::make($request->all(), [
            'date'              => ['required'],
            'warehouse_id'      => ['required', 'exists:warehouses,id'],
            'due_date'          => ['nullable'],
            'customer_id'       => ['nullable', 'numeric'],
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
            'items.*.name'      => ['required'],
            'items.*.quantity'  => ['required'],
            'items.*.price'     => ['required'],
            'items.*.attribute'         => ['required'],
            'items.*.attribute_item'    => ['required'],
            'items.*.is_variant'        => ['required'],
            'items.*.product_id'        => ['required']
        ]);

        if ($validator->fails()) {
            return $this->responseWithError($validator->errors()->first(), [],422);
        }

        $data               = $validator->validated();

        // Customer
        $customer           = $this->customerService->get($data['customer_id']);
        $data['customer']   = $customer;

        try {
            $invoice = $this->invoiceService->storeOrUpdate($data);
            $draft_invoice->delete();
            return $this->responseWithSuccess('Invoice Created',$invoice,201);
        } catch (Throwable $th) {
            return $this->responseWithError('Something went wrong',[],500);
        }

    }
    public function update(Request $request, $id){
        $draft_invoice        = $this->draftInvoiceService->get($id, ['items']);
        if(!$draft_invoice){
            return $this->responseWithError('Draft Invoice not found',[],404);
        }
        if($draft_invoice->customer_id != auth()->guard('api_customer')->user()->id){
            return $this->responseWithError('You are not authorized for this draft invoice',[],403);
        }
        $validator          =  Validator::make($request->all(), [
            'date'              => ['required'],
            'warehouse_id'      => ['required', 'exists:warehouses,id'],
            'due_date'          => ['nullable'],
            'customer_id'       => ['nullable', 'numeric'],
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
            'items.*.name'      => ['required'],
            'items.*.quantity'  => ['required'],
            'items.*.price'     => ['required'],

            'items.*.attribute'         => ['required'],
            'items.*.attribute_item'    => ['required'],
            'items.*.is_variant'        => ['required'],
            'items.*.product_id'        => ['required']
        ]);

        if ($validator->fails()) {
            return $this->responseWithError($validator->errors()->first(), [],422);
        }

        $data               = $validator->validated();

        // Customer
        $customer           = $this->customerService->get($data['customer_id']);
        $data['customer']   = $customer;

        try {
            $draftInvoice            = $this->draftInvoiceService->storeOrUpdate($data,$id);
            return $this->responseWithSuccess('Draft Invoice Updated Successfully',$draftInvoice,200);
        } catch (Throwable $th) {
            return $this->responseWithError('Something went wrong',[],500);
        }

    }

}
