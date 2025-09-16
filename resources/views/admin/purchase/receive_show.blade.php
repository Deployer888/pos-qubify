@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __t('purchases') }}</a></li>
                <li class="breadcrumb-item active">{{ __t('purchase_receive_details') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <h4 class="header-title">{{ __t('purchase_receive_details') }}</h4>
                    </div>
                    <div class="col-lg-6 col-md-6 d-print-none ic-print-btn-head">
                        <button data-div-name="section-to-print-pshow" class="btn btn-primary section-print-btn"
                            type="button"><i class="fa fa-print"></i> {{ __t('print') }}</button>
                        <a class="btn btn-info mr-2" href="{{ route('admin.purchases.receive-list') }}"><i
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
                                            <td>{{ $purchase_receive->purchase->purchase_number }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('supplier') }}</b></td>
                                            <td>:</td>
                                            <td>{{ $purchase_receive->purchase->supplier->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('supplier_phone') }}</b></td>
                                            <td>:</td>
                                            <td>{{ $purchase_receive->purchase->supplier->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('warehouse') }}</b></td>
                                            <td>:</td>
                                            <td>{{ $purchase_receive->purchase->warehouse->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('company') }}</b></td>
                                            <td>:</td>
                                            <td>{{ $purchase_receive->purchase->company }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('receive_date') }}</b></td>
                                            <td>:</td>
                                            <td>{{ date('Y-m-d', strtotime($purchase_receive->receive_date)) }}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table class="ic-purchase-print" width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td><b>{{ __t('address_line_1') }}</b></td>
                                            <td>:</td>
                                            <td>{{ optional($purchase_receive->purchase)->address_line_1 }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('address_line_2') }} </b></td>
                                            <td>:</td>
                                            <td>{{ optional($purchase_receive->purchase)->address_line_2 }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('country') }}</b></td>
                                            <td>:</td>
                                            <td>{{ optional(optional($purchase_receive->purchase)->systemCountry)->name
                                                }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('state') }}</b></td>
                                            <td>:</td>
                                            <td>{{ optional(optional($purchase_receive->purchase)->systemState)->name
                                                }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('city') }}</b></td>
                                            <td>:</td>
                                            <td>{{ optional(optional($purchase_receive->purchase)->systemCity)->name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('short_address') }}</b></td>
                                            <td>:</td>
                                            <td>{{ optional($purchase_receive->purchase)->short_address }}
                                            </td>
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

                                        @foreach($purchase_receive->purchaseItemReceives as $key => $receiveItems)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $receiveItems->product->sku }}</td>
                                            <td>
                                                {{ $receiveItems->product->name }}
                                                @if($receiveItems->product->is_variant != null && $receiveItems->product->is_variant == 1 && isset($receiveItems->productStock))
                                                    ({{optional(optional($receiveItems->productStock)->attribute)->name ?? ''}} : {{optional(optional($receiveItems->productStock)->attributeItem)->name ?? ''}})
                                                @endif
                                            </td>
                                            <td>{{ $receiveItems->quantity }}</td>
                                            <td class="text-right">{{
                                                currencySymbol().make2decimal($receiveItems->price) }}</td>
                                            <td class="text-right">{{
                                                currencySymbol().make2decimal($receiveItems->sub_total) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-right">{{ __t('total') }}: </th>
                                            <th class="text-right">{{
                                                currencySymbol().make2decimal($purchase_receive->total) }}</th>
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
