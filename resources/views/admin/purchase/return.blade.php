@extends('admin.layouts.master')

@section('content')
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __t('purchases') }}</a></li>
                    <li class="breadcrumb-item active">{{ __t('return').' '.__t('purchase') }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                @include('includes.messages.validation')

                <form action="{{ route('admin.purchases.return.store', $purchase->id) }}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <h4 class="header-title">{{ __t('return').' '.__t('purchase') }}</h4>
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
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="date" class="pt-2">{{ __t('return_date') }} <span
                                            class="error">*</span></label>
                                    <input type="text" class="form-control datepicker-autoclose" name="return_date"
                                           id="date" value="{{ old('return_date') }}" required
                                           placeholder="{{ __t('date') }}"
                                           autocomplete="off">
                                    @error('date')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="return_note" class="pt-2">{{ __t('return_note') }} <span
                                            class="error">*</span></label>
                                    <textarea name="return_note" id="return_note" required class="form-control"
                                              placeholder="{{ __t('note') }}">{{ old('return_note') }}</textarea>

                                    @error('return_note')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
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
                                            <th colspan="3" class="text-center">{{ __t('purchase_receive') }}</th>
                                            <th class="text-center" width="10%">{{ __t('product_stock') }}</th>
                                            <th class="text-center" width="10%">{{ __t('return') }}</th>
                                            <th colspan="3" class="text-center">{{ __t('purchase_return') }}</th>
                                        </tr>
                                        <tr>
                                            <th>{{ __t('quantity') }}</th>
                                            <th>{{ __t('price') }}</th>
                                            <th>{{ __t('sub_total') }}</th>

                                            <th class="text-center">{{ __t('quantity') }}</th>
                                            <th class="text-center">{{ __t('quantity') }}</th>

                                            <th>{{ __t('quantity') }}</th>
                                            <th>{{ __t('price') }}</th>
                                            <th>{{ __t('sub_total') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($purchase->purchaseItems as $key => $purchaseItems)
                                            @php
                                                $stockQty =
                                                optional($purchaseItems->product)->warehouseStock($purchase->warehouse_id);
                                                $stockAvailableQty = 0;
                                                $availAbleQty = $purchaseItems->receiveItems->sum('quantity') -
                                                optional($purchaseItems->returnItem)->sum('quantity');

                                                if ($stockQty > $availAbleQty){
                                                $stockAvailableQty = $availAbleQty;
                                                }else{
                                                $stockAvailableQty = $stockQty;
                                                }

                                            @endphp
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>
                                                    {{ $purchaseItems->product->sku }}
                                                    <input type="hidden" name="product_id[]"
                                                           value="{{ $purchaseItems->product_id }}">
                                                    <input type="hidden" name="product_stock_id[]"
                                                           value="{{ $purchaseItems->product_stock_id }}">
                                                    <input type="hidden" name="purchase_item_id[]"
                                                           value="{{ $purchaseItems->id }}">
                                                  <input type="hidden" name="receive_quantity[]"
                                                           value="{{ $purchaseItems->receiveItems->sum('quantity') }}">

                                                </td>
                                                <td>{{ $purchaseItems->product->name }}</td>
                                                <td>
                                                <span id="order_quantity_{{ $key+1 }}">{{
                                                    $purchaseItems->receiveItems->sum('quantity') }}</span>
                                                </td>
                                                <td class="text-right">{{
                                                currencySymbol().make2decimal(($purchaseItems->price)) }}</td>
                                                <td class="text-right">{{
                                                currencySymbol().make2decimal(($purchaseItems->sub_total)) }}</td>

                                                <td class="text-center">{{ $stockQty }}</td>
                                                <td class="text-center">{{ $purchaseItems->returnItem->sum('quantity') }}
                                                </td>
                                                <td>

                                                    <input type="hidden" id="stock_available_qty_{{ $key+1 }}"
                                                           value="{{ $stockAvailableQty }}">

                                                    <input type="hidden" id="available_qty_{{ $key+1 }}"
                                                           value="{{ $availAbleQty }}">

                                                    <input type="number"
                                                           class="form-control form-control-sm ic-return-calculate-input"
                                                           rel="{{ $key+1 }}" min="0" id="return_quantity_{{ $key+1 }}"
                                                           @if($stockAvailableQty==0) readonly
                                                           @endif name="return_quantity[]">
                                                    @if($stockAvailableQty == 0)
                                                        <small class="text-danger">{{ __t('no_available_quantity_for_return')
                                                    }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="number"
                                                           class="form-control form-control-sm ic-return-calculate-input"
                                                           rel="{{ $key+1 }}" value="{{ $purchaseItems->price }}"
                                                           id="return_price_{{ $key+1 }}"
                                                           @if($stockAvailableQty==0) readonly
                                                           @endif name="return_price[]">
                                                </td>
                                                <td>
                                                    <input type="number" readonly
                                                           class="form-control form-control-sm sub_total"
                                                           id="return_sub_total_{{ $key+1 }}" name="return_sub_total[]">
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-right">{{ __t('total') }}:</th>
                                            <th class="text-right">{{ currencySymbol().make2decimal($purchase->total) }}
                                            </th>

                                            <th colspan="4" class="text-right">{{ __t('total') }}:</th>
                                            <th class="text-right">
                                                <input name="total" class="form-control form-control-sm total" readonly>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 mt-3">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> {{ __t('submit')
                                }}</button>
                                <a class="btn btn-danger" href="{{ route('admin.purchases.index') }}"><i
                                        class="fa fa-times"></i> {{ __t('cancel') }}</a>
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
