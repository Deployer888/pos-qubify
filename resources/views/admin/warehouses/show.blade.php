@extends('admin.layouts.master')

@section('content')
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.warehouse') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('custom.warehouse_details') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="emp-profile ic-employee-warper-container d-print-none">
                        <div class="ic-customer-details-warper ic-details-wharphase">
                            <div class="ic-customer-profile-basic-info ic-details-warephaseInfo p-3 border ">
                                <div class="ic-customer-basic-info pl-0 mt-0">
                                    <div class="profile-head">
                                        <h5 class="text-muted">
                                            {{ $warehouse_details->name }}
                                        </h5>
                                        <h6>
                                            {{ __t('email') }}: {{ $warehouse_details->email }}
                                        </h6>
                                        <p class="mb-0 ic-discription-customer">
                                            {{ __t('phone') }}: {{ $warehouse_details->phone }}
                                        </p>
                                        <p class="mb-0 ic-discription-customer">
                                            {{ __t('company') }}: {{ $warehouse_details->company_name }}
                                        </p>
                                        <p class="mb-0 ic-discription-customer">
                                            {{ __t('address') }}: {{ $warehouse_details->address_1 }} <br>
                                            {{ $warehouse_details->address_1 }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="ic-profile-details-goback">
                                <a href="{{ route('admin.warehouses.show-pdf', $warehouse_details->id) }}"
                                    class="btn btn-info mr-2"><i class="fa fa-download"></i>
                                    {{ __('custom.download') }}</a>

                                <button class="btn btn-success mr-2" type="button" id="printWareHouseDetails"><i
                                        class="fa fa-print"></i> {{ __('custom.print') }}</button>
                                <a href="{{ route('admin.warehouses.index') }}" class="btn btn-primary float-right"><i
                                        class="fa fa-backspace"></i> {{ __t('back') }}</a>
                            </div>
                        </div>
                    </div>


                    <div class="row d-print-block" style="display: none">
                        <div class="col-sm-12">
                            <div class="profile-head text-center">
                                <h5 class="text-muted">
                                    {{ $warehouse_details->name }}
                                </h5>
                                <h6>
                                    {{ __t('email') }}: {{ $warehouse_details->email }}
                                </h6>
                                <p class="mb-0 ic-discription-customer">
                                    {{ __t('phone') }}: {{ $warehouse_details->phone }}
                                </p>
                                <p class="mb-0 ic-discription-customer">
                                    {{ __t('company') }}: {{ $warehouse_details->company_name }}
                                </p>
                                <p class="mb-0 ic-discription-customer">
                                    {{ __t('address') }}: {{ $warehouse_details->address_1 }} <br>
                                    {{ $warehouse_details->address_1 }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <h5>{{ __('custom.product_stock') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="table-active">
                                            <th><strong>{{ __('custom.product') }}</strong></th>
                                            <th><strong>{{ __('custom.sku') }}</strong></th>
                                            <th><strong>{{ __('custom.category') }}</strong></th>
                                            <th>
                                                <stong>{{ __('custom.manufacturer') }}</stong>
                                            </th>
                                            <th><strong>{{ __('custom.quantity') }}</strong></th>
                                            <th><strong>{{ __('custom.price') }}</strong></th>
                                            <th><strong>{{ __('custom.total') }}</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalStock = 0;
                                            $totalAmout = 0;
                                        @endphp
                                        @foreach ($warehouse_details->product_stocks as $product_stocks)
                                            <tr>
                                                <td class="text-left">
                                                    <a href="{{ route('admin.products.show', $product_stocks->product_id) }}"
                                                        class="text-decoration-none"><img
                                                            class="img-40 p-1 border mb-2 mr-2"
                                                            src="{{ getStorageImage(\App\Models\Product::FILE_STORE_PATH, optional($product_stocks->product)->thumb) }}"
                                                            alt="{{ optional($product_stocks->product)->name }}"
                                                            width="80px">
                                                        {{ optional($product_stocks->product)->name }}
                                                        @if (optional($product_stocks->product)->is_variant == 1)
                                                            <small>({{ optional($product_stocks->attribute)->name . ':' . optional($product_stocks->attributeItem)->name }}
                                                                )</small>
                                                        @endif
                                                    </a>


                                                </td>
                                                <td>{{ optional($product_stocks->product)->sku }}</td>
                                                <td>{{ optional(optional($product_stocks->product)->category)->name }}</td>
                                                <td>{{ optional(optional($product_stocks->product)->manufacturer)->name }}
                                                </td>
                                                <td>
                                                    @if ($product_stocks->quantity <= 0)
                                                        <span
                                                            class="text-danger"><b>{{ $product_stocks->quantity }}</b></span>
                                                        {{ optional(optional($product_stocks->product)->weight_unit)->name }}
                                                    @else
                                                        <span
                                                            class="text-success"><b>{{ $product_stocks->quantity }}</b></span>
                                                        {{ optional(optional($product_stocks->product)->weight_unit)->name }}
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    {{ currencySymbol() . make2decimal(optional($product_stocks->product)->price) }}
                                                </td>
                                                <td class="text-right">
                                                    {{ currencySymbol() . optional($product_stocks->product)->price * $product_stocks->quantity }}
                                                </td>
                                            </tr>
                                            @php
                                                $totalStock += $product_stocks->quantity;
                                                $totalAmout +=
                                                    optional($product_stocks->product)->price *
                                                    $product_stocks->quantity;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-right">{{ __('custom.total') }}</th>
                                            <th>{{ $totalStock }}</th>
                                            <th></th>
                                            <th>{{ currencySymbol() . $totalAmout }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
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
