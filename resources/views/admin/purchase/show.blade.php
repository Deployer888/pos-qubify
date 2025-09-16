@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __t('purchases') }}</a></li>
                <li class="breadcrumb-item active">{{ __t('view').' '.__t('purchase') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <h4 class="header-title">{{ __t('view').' '.__t('purchase') }}</h4>
                    </div>
                    <div class="col-lg-6 d-print-none ic-print-btn-head">
                        <button data-div-name="section-to-print-pshow" class="btn btn-primary section-print-btn"
                            type="button"><i class="fa fa-print"></i> {{ __t('print') }}</button>
                        <a class="btn btn-info mr-2" href="{{ route('admin.purchases.index') }}"><i
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
                                            <td>{{ $purchase->purchase_number }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('supplier') }}</b></td>
                                            <td>:</td>
                                            <td>{{ $purchase->supplier->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('supplier_phone') }}</b></td>
                                            <td>:</td>
                                            <td>{{ $purchase->supplier->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('warehouse') }}</b></td>
                                            <td>:</td>
                                            <td>{{ $purchase->company }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('company') }}</b></td>
                                            <td>:</td>
                                            <td>{{ $purchase->company }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('date') }}</b></td>
                                            <td>:</td>
                                            <td>{{ date('Y-m-d', strtotime($purchase->date)) }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('note') }}</b></td>
                                            <td>:</td>
                                            <td>{{ $purchase->notes }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('short_address') }}</b></td>
                                            <td>:</td>
                                            <td>{{ $purchase->short_address }}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table class="ic-purchase-print" width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td><b>{{ __t('address_line_1') }}</b></td>
                                            <td>:</td>
                                            <td>{{ $purchase->address_line_1 }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('address_line_2') }}</b></td>
                                            <td>:</td>
                                            <td>{{ $purchase->address_line_2 }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('country') }}</b></td>
                                            <td>:</td>
                                            <td>{{ optional($purchase->systemCountry)->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('state') }}</b></td>
                                            <td>:</td>
                                            <td>{{ optional($purchase->systemState)->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('city') }}</b></td>
                                            <td>:</td>
                                            <td>{{ optional($purchase->systemCity)->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('status') }} </b></td>
                                            <td>:</td>
                                            <td> @if($purchase->status == \App\Models\Purchase::STATUS_REQUESTED)
                                                <span class="badge badge-primary">{{ strtoupper($purchase->status)
                                                    }}</span>
                                                @elseif($purchase->status == \App\Models\Purchase::STATUS_CONFIRMED)
                                                <span class="badge badge-success">{{ strtoupper($purchase->status)
                                                    }}</span>
                                                @else
                                                <span class="badge badge-danger">{{ strtoupper($purchase->status)
                                                    }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ __t('received') }}</b></td>
                                            <td>:</td>
                                            <td>
                                                @if($purchase->received)
                                                <span class="badge badge-success">{{ strtoupper(__t('received'))
                                                    }}</span>
                                                @else
                                                <span class="badge badge-warning">{{ strtoupper(__t('not_received_yet'))
                                                    }}</span>
                                                @endif
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
                                            <th>{{ __t('note') }}</th>
                                            <th>{{ __t('sub_total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($purchase->purchaseItems as $key => $purchaseItems)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $purchaseItems->product->sku }}</td>
                                            <td>{{ $purchaseItems->product->name }}
                                                @if($purchaseItems->product->is_variant != null && $purchaseItems->product->is_variant == 1 && isset($purchaseItems->productStock))
                                                    ({{optional(optional($purchaseItems->productStock)->attribute)->name ?? ''}} : {{optional(optional($purchaseItems->productStock)->attributeItem)->name ?? ''}})
                                                @endif
                                            </td>
                                            <td>{{ $purchaseItems->quantity }}</td>
                                            <td class="text-right">{{
                                                currencySymbol().make2decimal($purchaseItems->price)
                                                }}</td>
                                            <td>{{ $purchaseItems->note }}</td>
                                            <td class="text-right">{{
                                                currencySymbol().make2decimal($purchaseItems->sub_total) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-right">{{ __t('total') }}: </th>
                                            <th class="text-right">{{ currencySymbol().make2decimal($purchase->total) }}
                                            </th>
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
