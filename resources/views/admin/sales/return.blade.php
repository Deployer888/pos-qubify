@extends('admin.layouts.master')
@section('content')
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __t('sales') }}</a></li>
                    <li class="breadcrumb-item active">{{ __t('return') . ' ' . __t('sales') }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                @include('includes.messages.validation')
                <form action="{{ route('admin.sales-return.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{ $sales->id }}">
                    {{--                <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}"> --}}

                    <div class="card-body">
                        <h4 class="header-title">{{ __t('return') . ' ' . __t('sales') }}</h4>

                        @php
                            $customer = (object) $sales->customer;
                            $billing_info = (object) $sales->billing_info;
                            $shipping_info = (object) $sales->shipping_info;
                        @endphp
                        <div class="row">
                            <div class="col-lg-6 col-md-8 col-xl-6 ml-auto">
                                <table class="ic-purchase-print" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="text-right"><b>{{ __t('sale_number') }}</b></td>
                                        <td>:</td>
                                        <td class="text-right">{{ make8digits($sales->id) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><b>{{ __t('sale_date') }}</b></td>
                                        <td>:</td>
                                        <td class="text-right">{{ custom_date($sales->date) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><b>{{ __t('customer_name') }}</b></td>
                                        <td>:</td>
                                        <td class="text-right">{{ optional($customer)->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><b>{{ __t('customer_phone') }}</b></td>
                                        <td>:</td>
                                        <td class="text-right">{{ optional($customer)->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><b>{{ __t('customer_email') }}</b></td>
                                        <td>:</td>
                                        <td class="text-right">{{ optional($customer)->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><b>{{ __t('warehouse') }}</b></td>
                                        <td>:</td>
                                        <td class="text-right">{{ $warehouse->name }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-6">
                                <h4 class="mt-0 header-title">{{ __t('billing_info') }}</h4>
                                <table class="ic-purchase-print" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td><b>{{ __t('name') }}</b></td>
                                        <td>:</td>
                                        <td>{{ optional($billing_info)->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __t('email') }}</b></td>
                                        <td>:</td>
                                        <td>{{ optional($billing_info)->email }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __t('phone') }}</b></td>
                                        <td>:</td>
                                        <td>{{ optional($billing_info)->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __t('address_line_1') }}</b></td>
                                        <td>:</td>
                                        <td>{{ optional($billing_info)->address_line_1 }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __t('address_line_2') }}</b></td>
                                        <td>:</td>
                                        <td>{{ optional($billing_info)->address_line_2 }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __t('country') }}</b></td>
                                        <td>:</td>

                                        <td>{{ !empty($billing_info->country) ? \App\Models\SystemCountry::findOrFail($billing_info->country)->name : '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __t('state') }}</b></td>
                                        <td>:</td>
                                        <td>{{ !empty($billing_info->state) ? \App\Models\SystemState::findOrFail($billing_info->state)->name : '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __t('city') }}</b></td>
                                        <td>:</td>
                                        <td>{{ !empty($billing_info->city) ? \App\Models\SystemCity::findOrFail($billing_info->city)->name : '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __t('zip') }}</b></td>
                                        <td>:</td>
                                        <td>{{ optional($billing_info)->zip }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-6">
                                <h4 class="mt-0 header-title">{{ __t('shipping_info') }}</h4>
                                <table class="ic-purchase-print" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td><b>{{ __t('name') }}</b></td>
                                        <td>:</td>
                                        <td>{{ optional($shipping_info)->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __t('email') }}</b></td>
                                        <td>:</td>
                                        <td>{{ optional($shipping_info)->email }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __t('phone') }}</b></td>
                                        <td>:</td>
                                        <td>{{ optional($shipping_info)->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __t('address_line_1') }}</b></td>
                                        <td>:</td>
                                        <td>{{ optional($shipping_info)->address_line_1 }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __t('address_line_2') }}</b></td>
                                        <td>:</td>
                                        <td>{{ optional($shipping_info)->address_line_2 }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __t('country') }}</b></td>
                                        <td>:</td>
                                        <td>{{ !empty($shipping_info->country) ? \App\Models\SystemCountry::findOrFail($shipping_info->country)->name : '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __t('state') }}</b></td>
                                        <td>:</td>
                                        <td>{{ !empty($shipping_info->state) ? \App\Models\SystemState::findOrFail($shipping_info->state)->name : '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __t('city') }}</b></td>
                                        <td>:</td>
                                        <td>{{ !empty($shipping_info->city) ? \App\Models\SystemCity::findOrFail($shipping_info->city)->name : '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ __t('zip') }}</b></td>
                                        <td>:</td>
                                        <td>{{ optional($shipping_info)->zip }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="date" class="pt-2">{{ __t('return_date') }} <span
                                            class="error">*</span></label>

                                    <div class="form-group">
                                        <input type="text" class="form-control datepicker-autoclose" name="return_date"
                                            id="date" value="{{ old('return_date') ?? date('Y-m-d') }}" required
                                            placeholder="{{ __t('date') }}" autocomplete="off">
                                    </div>
                                    @error('date')
                                        <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="return_note" class="pt-2">{{ __t('return_note') }} </label>
                                    <textarea name="return_note" id="return_note" class="form-control" placeholder="{{ __t('note') }}">{{ old('return_note') }}</textarea>

                                    @error('return_note')
                                        <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="warehouse" class="pt-2">{{ __t('warehouse') }} </label>
                                    <select class="form-control" name="warehouse_id" id="warehouse">
                                        <option value="">{{ __t('select_warehouse') }}</option>
                                        @foreach ($warehouses as $house)
                                            <option value="{{ $house->id }}"
                                                {{ $house->id == $warehouse->id ? 'selected' : '' }}>{{ $house->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('warehouse_id')
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
                                                <th colspan="3" class="text-center">{{ __t('sales') }}</th>
                                                <th class="text-center">{{ __t('available') }}</th>
                                                <th colspan="3" class="text-center">{{ __t('return') }}</th>
                                            </tr>
                                            <tr>
                                                <th>{{ __t('quantity') }}</th>
                                                <th>{{ __t('price') }}
                                                    <small>({{ __('custom.with_tax_and_discount') }})</small>
                                                </th>
                                                <th>{{ __t('sub_total') }} </th>

                                                <th>{{ __t('quantity') }}</th>

                                                <th>{{ __t('quantity') }}</th>
                                                <th>{{ __t('price') }}</th>
                                                <th>{{ __t('sub_total') }} </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php
                                                $saleTotal = 0;
                                            @endphp
                                            @foreach ($sales->items as $key => $item)
                                                <input type="hidden" name="invoice_details_id[]"
                                                    value="{{ $item->id }}">
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        {{ $item->sku }}
                                                        <input type="hidden" name="product_id[]"
                                                            value="{{ $item->product_id }}">
                                                        <input type="hidden" name="product_stock_id[]"
                                                            value="{{ $item->product_stock_id }}">
                                                        <input type="hidden" name="attribute_id[]"
                                                            value="{{ optional($item->productStock)->attribute_id }}">
                                                        <input type="hidden" name="attribute_item_id[]"
                                                            value="{{ optional($item->productStock)->attribute_item_id }}">
                                                        <input type="hidden" name="product_sku[]"
                                                            value="{{ $item->sku }}">
                                                        <input type="hidden" name="price[]"
                                                            value="{{ $item->price }}">
                                                        <input type="hidden" name="discount[]"
                                                            value="{{ $item->discount }}">
                                                        <input type="hidden" name="discount_type[]"
                                                            value="{{ $item->discount_type }}">
                                                    </td>
                                                    <td>
                                                        {{ $item->product_name }}
                                                        @if ($item->product->is_variant != null && $item->product->is_variant == 1 && isset($item->productStock))
                                                            ({{ optional(optional($item->productStock)->attribute)->name ?? '' }}
                                                            :
                                                            {{ optional(optional($item->productStock)->attributeItem)->name ?? '' }})
                                                        @endif
                                                        <input type="hidden" name="product_name[]"
                                                            value="{{ $item->product_name }}">
                                                    </td>
                                                    <td>
                                                        {{ $item->quantity }}
                                                        <input type="hidden" value="{{ $item->quantity }}"
                                                            id="sale_quantity_{{ $item->id }}">
                                                    </td>
                                                    <td>
                                                        @php
                                                            $itemPrice = $item->price;
                                                            $itemDiscount = $item->discount;
                                                            $price = 0;
                                                            $tax = 0;
                                                            if ($item->quantity > 0 && $item->tax > 0) {
                                                                $tax = ($item->price * $item->tax) / 100;
                                                            }
                                                            if ($item->discount_type == 'percent') {
                                                                $price =
                                                                    $itemPrice -
                                                                    ($itemPrice * $itemDiscount) / 100 +
                                                                    $tax;
                                                            } else {
                                                                $price = $itemPrice - $itemDiscount + $tax;
                                                            }
                                                        @endphp
                                                        {{ $price }}

                                                        <input type="hidden" value="{{ $price }}"
                                                            id="sale_price_{{ $price }}">
                                                    </td>
                                                    <td>{{ $price * $item->quantity }}</td>

                                                    <td>
                                                        @php
                                                            $availableQty =
                                                                $item->quantity -
                                                                $item->salesReturnItems->sum('return_qty');
                                                        @endphp
                                                        <input type="text" readonly class="form-control"
                                                            id="available_qty_{{ $item->id }}"
                                                            value="{{ $availableQty }}">
                                                        @if ($availableQty == 0)
                                                            <span
                                                                class="text-danger">{{ __t('no_available_quantity') }}</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <input type="number" class="form-control ic-sale-return-qty"
                                                            rel="{{ $item->id }}" name="return_qty[]"
                                                            id="return_qty_{{ $item->id }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="return_price[]"
                                                            id="return_price_{{ $item->id }}"
                                                            value="{{ $price }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control sub_total"
                                                            name="return_sub_total[]"
                                                            id="return_subtotal_{{ $item->id }}" readonly>
                                                    </td>
                                                </tr>
                                                @php
                                                    $saleTotal += $price;
                                                @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5" class="text-right">{{ __t('total') }}:</th>
                                                <th class="text-right">{{ $saleTotal }}</th>

                                                <th colspan="3" class="text-right">{{ __t('total') }}:</th>
                                                <th class="text-right">
                                                    <input name="total" class="form-control form-control-sm total"
                                                        readonly>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 mt-3">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-save"></i> <span>{{ __t('submit') }}</span>
                                </button>
                                <a class="btn btn-danger" href="{{ route('admin.sales-return.createable_list') }}">
                                    <i class="fa fa-times"></i> <span>{{ __t('cancel') }}</span>
                                </a>
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
