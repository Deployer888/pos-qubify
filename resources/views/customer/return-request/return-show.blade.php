@extends('customer.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h4 class="page-title">{{ __t('product_return_request_details') }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 d-print-none">
                        <button data-div-name="section-to-print-sshow" class="btn btn-primary float-right section-print-btn" type="button"><i
                                class="fa fa-print"></i> {{ __t('print') }}</button>
                        <a class="btn btn-info float-right mr-2" href="{{ route('customer.products-return-request.index') }}"><i
                                class="fa fa-arrow-left"></i> {{ __t('back') }}</a>
                    </div>
                </div>

               <div id="section-to-print-sshow">
                   <div class="row">
                       <div class="col-lg-6 col-md-8 col-xl-6 ml-auto">
                           <table class="ic-purchase-print" width="100%" cellpadding="0" cellspacing="0">
                               <tr>
                                   <td class="text-right"><b>{{ __t('invoice_number') }} </b></td>
                                   <td>:</td>
                                   <td class="text-right">{{ make8digits($sale_return->id) }}</td>
                               </tr>
                               <tr>
                                   <td class="text-right"><b>{{ __t('customer_name') }}</b></td>
                                   <td>:</td>
                                   <td class="text-right">{{ optional($sale_return->invoice->customerInfo)->first_name ?: "Walk-in Customer" }}</td>
                               </tr>
                               <tr>
                                   <td class="text-right"><b>{{ __t('customer_phone') }}</b></td>

                                   <td>:</td>
                                   <td class="text-right">{{ optional($sale_return->invoice->customerInfo)->phone }}</td>
                               </tr>
                               <tr>
                                   <td class="text-right"><b>{{ __t('return_date') }}</b></td>
                                   <td>:</td>
                                   <td class="text-right">{{ date('Y-m-d', strtotime($sale_return->return_date)) }}</td>

                               </tr>
                               <tr>
                                   <td class="text-right"><b>{{ __t('status') }}</b></td>
                                   <td>:</td>
                                   <td class="text-right">{{ ucfirst($sale_return->status) }}</td>

                               </tr>
                           </table>
                       </div>
                   </div>

                   <div class="row">
                       <div class="col-sm-6">
                           <h4>{{ __t('billing_info') }}</h4>
                           @php
                               $billing_info = (object) $sale_return->invoice->billing_info;
                           @endphp
                           <table class="ic-purchase-print" width="100%" cellpadding="0" cellspacing="0">
                               <tr>
                                   <td><b>{{ __t('address_line_1') }}</b></td>
                                   <td>:</td>
                                   <td>{{ $billing_info->address_line_1 }}</td>
                               </tr>
                               <tr>
                                   <td><b>{{ __t('address_line_2') }}</b></td>
                                   <td>:</td>
                                   <td>{{ $billing_info->address_line_2 }}</td>
                               </tr>
                               <tr>
                                   <td><b>{{ __t('country') }}</b></td>
                                   <td>:</td>
                                   <td>{{ $billing_info->country }}</td>
                               </tr>
                               <tr>
                                   <td><b>{{ __t('state') }}</b></td>
                                   <td>:</td>
                                   <td>{{ $billing_info->state }}</td>
                               </tr>
                               <tr>
                                   <td><b>{{ __t('city') }}</b></td>
                                   <td>:</td>
                                   <td>{{ $billing_info->city }}</td>
                               </tr>
                               <tr>
                                   <td><b>{{ __t('zip') }}</b></td>
                                   <td>:</td>
                                   <td>{{ $billing_info->zip }}</td>
                               </tr>
                           </table>
                       </div>
                       <div class="col-sm-6">
                           <h4>{{ __t('shipping_info') }}</h4>
                           @php
                               $shipping_info = (object) $sale_return->invoice->shipping_info;
                           @endphp
                           <table class="ic-purchase-print" width="100%" cellpadding="0" cellspacing="0">
                               <tr>
                                   <td><b>{{ __t('address_line_1') }}</b></td>
                                   <td>:</td>
                                   <td>{{ $shipping_info->address_line_1 }}</td>
                               </tr>
                               <tr>
                                   <td><b>{{ __t('address_line_2') }}</b></td>
                                   <td>:</td>
                                   <td>{{ $shipping_info->address_line_2 }}</td>
                               </tr>
                               <tr>
                                   <td><b>{{ __t('country') }}</b></td>
                                   <td>:</td>
                                   <td>{{ $shipping_info->country }}</td>
                               </tr>
                               <tr>
                                   <td><b>{{ __t('state') }}</b></td>
                                   <td>:</td>
                                   <td>{{ $shipping_info->state }}</td>
                               </tr>
                               <tr>
                                   <td><b>{{ __t('city') }}</b></td>
                                   <td>:</td>
                                   <td>{{ $shipping_info->city }}</td>
                               </tr>
                               <tr>
                                   <td><b>{{ __t('zip') }}</b></td>
                                   <td>:</td>
                                   <td>{{ $shipping_info->zip }}</td>
                               </tr>
                           </table>
                       </div>
                   </div>

                   <div class="row">
                       <div class="col-sm-12">
                           <div class="table-responsive">
                               <table class="table table-bordered">
                                   <thead>
                                   <tr>
                                       <th>{{ __t('sl') }}</th>
                                       <th>{{ __t('sku') }}</th>
                                       <th>{{ __t('product_name') }}</th>
                                       <th>{{ __t('quantity') }}</th>
                                       <th>{{ __t('price') }}</th>
                                       <th>{{ __t('sub_total') }}</th>
                                   </tr>
                                   </thead>
                                   <tbody>
                                   @foreach($sale_return->saleReturnRequestItems as $key => $returnItems)
                                       <tr>
                                           <td>{{ $key+1 }}</td>
                                           <td>{{ $returnItems->product->sku }}</td>
                                           <td>
                                               {{ $returnItems->product->name }}
                                               @if($returnItems->product->is_variant != null && $returnItems->product->is_variant == 1 && isset($returnItems->productStock))
                                                   ({{optional(optional($returnItems->productStock)->attribute)->name ?? ''}} : {{optional(optional($returnItems->productStock)->attributeItem)->name ?? ''}})
                                               @endif
                                           </td>
                                           <td>{{ $returnItems->return_qty }}</td>
                                           <td class="text-right">{{ currencySymbol().make2decimal($returnItems->return_price) }}</td>
                                           <td class="text-right">{{ currencySymbol().make2decimal($returnItems->return_sub_total) }}</td>
                                       </tr>
                                   @endforeach
                                   </tbody>
                                   <tfoot>
                                   <tr>
                                       <th colspan="5" class="text-right">{{ __t('total') }}: </th>
                                       <th class="text-right">{{ currencySymbol().make2decimal($sale_return->return_total_amount) }}</th>
                                   </tr>
                                   </tfoot>
                               </table>
                               <div>
                                   <p><b>{{ __t('return') }} {{ __t('note') }}: </b>{{ $sale_return->return_note }}</p>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')

@endpush

@push('script')

@endpush
