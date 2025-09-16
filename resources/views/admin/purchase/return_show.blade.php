@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h4 class="page-title">{{ __t('purchase_return_details') }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 d-print-none ic-print-btn-head">
                        <button data-div-name="section-to-print-pshow" class="btn btn-primary section-print-btn" type="button"><i
                                class="fa fa-print"></i> {{ __t('print') }}</button>
                        <a class="btn btn-info mr-2" href="{{ route('admin.purchases.return.list') }}"><i
                                class="fa fa-arrow-left"></i> {{ __t('back') }}</a>
                    </div>
                </div>
               <div id="section-to-print-pshow">
                   <div class="table-responsive">
                       <table width="100%" cellpadding="0" cellspacing="0">
                           <tr>
                               <td>
                                   <table class="ic-purchase-print" width="100%" cellpadding="0" cellspacing="0">
                                       <tr>
                                           <td><b>{{ __t('purchase_number') }}</b></td>
                                           <td>:</td>
                                           <td>{{ $purchase_return->purchase->purchase_number }}</td>
                                       </tr>
                                       <tr>
                                           <td><b>{{ __t('supplier') }}</b></td>
                                           <td>:</td>
                                           <td>{{ optional(optional($purchase_return->purchase)->supplier)->full_name }}</td>
                                       </tr>
                                       <tr>
                                           <td><b>{{ __t('supplier_phone') }}</b></td>
                                           <td>:</td>
                                           <td>{{ optional(optional($purchase_return->purchase)->supplier)->phone }}</td>
                                       </tr>
                                       <tr>
                                           <td><b>{{ __t('warehouse') }}</b></td>
                                           <td>:</td>
                                           <td>{{ optional(optional($purchase_return->purchase)->warehouse)->name }}</td>
                                       </tr>
                                       <tr>
                                           <td><b>{{ __t('company') }}</b></td>
                                           <td>:</td>
                                           <td>{{ optional($purchase_return->purchase)->company }}</td>
                                       </tr>
                                       <tr>
                                           <td><b>{{ __t('return_date') }}</b></td>
                                           <td>:</td>
                                           <td>{{ date('Y-m-d', strtotime($purchase_return->return_date)) }}</td>
                                       </tr>
                                   </table>
                               </td>
                               <td>
                                   <table class="ic-purchase-print" width="100%" cellpadding="0" cellspacing="0">
                                       <tr>
                                           <td><b>{{ __t('address_line_1') }}</b></td>
                                           <td>:</td>
                                           <td>{{ optional($purchase_return->purchase)->address_line_1 }}</td>
                                       </tr>
                                       <tr>
                                           <td><b>{{ __t('address_line_2') }}</b></td>
                                           <td>:</td>
                                           <td>{{ optional($purchase_return->purchase)->address_line_2 }}</td>
                                       </tr>
                                       <tr>
                                           <td><b>{{ __t('country') }}</b></td>
                                           <td>:</td>
                                           <td>{{ optional(optional($purchase_return->purchase)->systemCountry)->name }}</td>
                                       </tr>
                                       <tr>
                                           <td><b>{{ __t('state') }}</b></td>
                                           <td>:</td>
                                           <td>{{ optional(optional($purchase_return->purchase)->systemState)->name }}</td>
                                       </tr>
                                       <tr>
                                           <td><b>{{ __t('city') }}</b></td>
                                           <td>:</td>
                                           <td>{{ optional(optional($purchase_return->purchase)->systemCity)->name }}</td>
                                       </tr>
                                       <tr>
                                           <td><b>{{ __t('short_address') }}</b></td>
                                           <td>:</td>
                                           <td>{{ optional($purchase_return->purchase)->short_address }}</td>
                                       </tr>
                                   </table>
                               </td>
                           </tr>
                       </table>
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

                                   @foreach($purchase_return->purchaseReturnItems as $key => $returnItems)
                                       <tr>
                                           <td>{{ $key+1 }}</td>
                                           <td>{{ $returnItems->product->sku }}</td>
                                           <td>{{ $returnItems->product->name }}
                                               @if($returnItems->product->is_variant != null && $returnItems->product->is_variant == 1 && isset($returnItems->productStock))
                                                   ({{optional(optional($returnItems->productStock)->attribute)->name ?? ''}} : {{optional(optional($returnItems->productStock)->attributeItem)->name ?? ''}})
                                               @endif
                                           </td>
                                           <td>{{ $returnItems->quantity }}</td>
                                           <td class="text-right">{{ currencySymbol().make2decimal($returnItems->price) }}</td>
                                           <td class="text-right">{{ currencySymbol().make2decimal($returnItems->sub_total) }}</td>
                                       </tr>
                                   @endforeach
                                   </tbody>
                                   <tfoot>
                                   <tr>
                                       <th colspan="5" class="text-right">{{ __t('total') }}: </th>
                                       <th class="text-right">{{ currencySymbol().make2decimal($purchase_return->total) }}</th>
                                   </tr>
                                   </tfoot>
                               </table>
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
