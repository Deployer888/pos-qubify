@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __t('purchases') }}</a></li>
                <li class="breadcrumb-item active">{{ __t('receive').' '.__t('purchase') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            @include('includes.messages.validation')

            <form action="{{ route('admin.purchases.receive.store', $purchase->id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="warehouse_id" value="{{ $purchase->warehouse->id ?? null }}">
                <div class="card-body">
                    <h4 class="header-title">{{ __t('receive').' '.__t('purchase') }}</h4>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="date" class="pt-2">{{ __t('receive_date') }} <span
                                        class="error">*</span></label>
                                <input type="text" class="form-control datepicker-autoclose" name="date" id="date"
                                    value="{{ old('date') ?? date('Y-m-d') }}" required placeholder="{{ __t('date') }}"
                                    autocomplete="off">
                                @error('date')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <table class="ic-purchase-print" width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td><b>{{ __t('purchase_number') }} </b></td>
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
                                            <td>{{ $purchase->warehouse->name }}</td>
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
                                            <td><b>{{ __t('address_line_1') }} </b></td>
                                            <td>:</td>
                                            <td>{{ $purchase->address_line_1 }} </td>
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
                                            <td><b>{{ __t('status') }}</b></td>
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
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered ic-table-return">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="align-middle">{{ __t('sl') }}</th>
                                            <th rowspan="2" class="align-middle">{{ __t('sku') }}</th>
                                            <th rowspan="2" class="align-middle">{{ __t('product_name') }}</th>
                                            <th colspan="3" class="text-center">{{ __t('purchase_order') }}</th>
                                            <th colspan="3" class="text-center">{{ __t('purchase_order_received') }}</th>
                                            <th colspan="5" class="text-center">{{ __t('purchase_receive') }}</th>
                                        </tr>
                                        <tr>
                                            <th>{{ __t('quantity') }}</th>
                                            <th>{{ __t('price') }}</th>
                                            <th>{{ __t('sub_total') }}</th>

                                            <th>{{ __t('quantity') }}</th>
                                            <th>{{ __t('price') }}</th>
                                            <th>{{ __t('sub_total') }}</th>

                                            <th>{{ __t('quantity') }}</th>
                                            <th>{{ __("custom.batch") }}</th>
                                            <th>{{ __("custom.expiry_date") }}</th>
                                            <th>{{ __t('price') }}</th>
                                            <th>{{ __t('sub_total') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($purchase->purchaseItems as $key => $purchaseItems)
                                        @php
                                            $availableQty = $purchaseItems->quantity - $purchaseItems->receiveItems->sum('quantity');
                                        @endphp
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>
                                                {{ $purchaseItems->product->sku }}
                                                <input type="hidden" name="product_id[]" value="{{ $purchaseItems->product_id }}">
                                                <input type="hidden" name="purchase_item_id[]" value="{{ $purchaseItems->id }}">
                                                <input type="hidden" name="product_stock_id[]" value="{{ $purchaseItems->product_stock_id }}">
                                            </td>
                                            <td>
                                                {{ $purchaseItems->product->name }}
                                                @if($purchaseItems->product->is_variant && isset($purchaseItems->productStock))
                                                    ({{ optional($purchaseItems->productStock->attribute)->name ?? '' }} : {{ optional($purchaseItems->productStock->attributeItem)->name ?? '' }})
                                                @endif
                                            </td>
                                            <td><span id="order_quantity_{{ $key+1 }}">{{ $purchaseItems->quantity }}</span></td>
                                            <td class="text-right">{{ currencySymbol().make2decimal($purchaseItems->price) }}</td>
                                            <td class="text-right">{{ currencySymbol().make2decimal($purchaseItems->sub_total) }}</td>

                                            <td>{{ $purchaseItems->receiveItems->sum('quantity') }}</td>
                                            <td class="text-right">{{ currencySymbol().make2decimal($purchaseItems->receiveItems->sum('price')) }}</td>
                                            <td class="text-right">{{ currencySymbol().make2decimal($purchaseItems->receiveItems->sum('sub_total')) }}</td>

                                            <td>
                                                <input type="hidden" id="available_qty_{{ $key+1 }}" value="{{ $availableQty }}">
                                                <input type="number"
                                                    class="form-control form-control-sm ic-calculate-input"
                                                    rel="{{ $key+1 }}"
                                                    min="0"
                                                    id="receive_quantity_{{ $key+1 }}"
                                                    name="receive_quantity[]"
                                                    @if($availableQty == 0) readonly @endif>
                                                @if($availableQty == 0)
                                                    <small class="text-danger">{{ __t('no_available_quantity_for_receive') }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="text"
                                                    class="form-control form-control-sm"
                                                    name="batch[]"
                                                    @if(!$purchaseItems->product->is_batch_product || $availableQty == 0) disabled @endif
>
                                            </td>
                                            <td>
                                                <input type="date"
                                                    class="form-control form-control-sm"
                                                    name="expiry_date[]"
                                                    @if(!$purchaseItems->product->is_batch_product || $availableQty == 0) disabled @endif
>
                                            </td>
                                            <td>
                                                <input type="number"
                                                    class="form-control form-control-sm ic-calculate-input"
                                                    rel="{{ $key+1 }}"
                                                    id="receive_price_{{ $key+1 }}"
                                                    value="{{ $purchaseItems->price }}"
                                                    name="receive_price[]"
                                                    @if($availableQty == 0) readonly @endif>
                                            </td>
                                            <td>
                                                <input type="number"
                                                    readonly
                                                    class="form-control form-control-sm sub_total"
                                                    id="receive_sub_total_{{ $key+1 }}"
                                                    name="receive_sub_total[]">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-right">{{ __t('total') }}:</th>
                                            <th class="text-right">{{ currencySymbol().make2decimal($purchase->total) }}</th>

                                            <th colspan="2" class="text-right">{{ __t('total') }}:</th>
                                            <th class="text-right">{{ currencySymbol().make2decimal($purchase->purchaseReceives->sum('total')) }}</th>

                                            <th colspan="4" class="text-right">{{ __t('total') }}:</th>
                                            <th class="text-right">
                                                <input name="total" class="form-control total" readonly>
                                            </th>
                                        </tr>
                                    </tfoot>

                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 mt-3">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i><span> {{
                                    __t('submit')
                                    }}</span></button>
                            <a class="btn btn-danger" href="{{ route('admin.purchases.index') }}"><i
                                    class="fa fa-times"></i> <span>{{ __t('cancel') }}</span></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script')

@endpush


@push('style')

@endpush
