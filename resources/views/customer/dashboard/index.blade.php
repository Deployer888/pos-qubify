@extends('customer.layouts.master')
@section('content')

    <!-- ======== breadcump start ========  -->

    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">{{ __('custom.dashboard') }}</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">{{ __('custom.welcome') }} {{ auth()->guard('customer')->user()->full_name }}</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- ======== breadcump end ========  -->

    <!-- ======== products card start ========  -->

    <div class="ic-section-gap">
        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6">
                <a href="{{ route('customer.invoices.create') }}">
                    <div class="ic-card-head info">
                        <i class="flaticon-new-product ic-card-icon"></i>
                        <i class="flaticon-new-product big-icon"></i>
                        <h3>{{ $data['total_product'] }}</h3>
                        <p>{{ __('custom.total') }} {{ __('custom.product') }}</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6">
                <a href="{{ route('customer.invoices.index') }}">
                    <div class="ic-card-head success">
                        <i class="flaticon-shopping-bag-1 ic-card-icon "></i>
                        <i class="flaticon-shopping-bag-1 big-icon"></i>
                        <h3>{{ $data['total_invoice'] }}</h3>
                        <p>{{ __('custom.total') }} {{ __('custom.invoice') }}</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6">
                <a href="{{ route('customer.draft-invoices.index') }}">
                    <div class="ic-card-head primary">
                        <i class="flaticon-shopping-bag ic-card-icon "></i>
                        <i class="flaticon-shopping-bag big-icon"></i>
                        <h3>{{ $data['total_draft_invoice'] }}</h3>
                        <p>{{ __('custom.draft_invoice') }}</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6">
                <a href="{{ route('customer.products-return-request.index') }}">
                    <div class="ic-card-head secondary">
                        <i class="flaticon-inventory ic-card-icon "></i>
                        <i class="flaticon-inventory big-icon"></i>
                        <h3>{{ $data['total_return_request'] }}</h3>
                        <p>{{ __('custom.return_request') }}</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6">
                <a href="{{ route('customer.products-return-request.index') }}">
                    <div class="ic-card-head success">
                        <i class="flaticon-inventory ic-card-icon "></i>
                        <i class="flaticon-inventory big-icon"></i>
                        <h3>{{ $data['total_return_request_accepted'] }}</h3>
                        <p>{{ __('custom.accepted_request') }}</p>
                    </div>
                </a>
            </div>
{{--            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6">--}}
{{--                <a href="{{ route('customer.products-return-request.index') }}">--}}
{{--                    <div class="ic-card-head danger">--}}
{{--                        <i class="flaticon-inventory ic-card-icon "></i>--}}
{{--                        <i class="flaticon-inventory big-icon"></i>--}}
{{--                        <h3>{{ $data['total_return_request_rejected'] }}</h3>--}}
{{--                        <p>{{ __('custom.rejected_request') }}</p>--}}
{{--                    </div>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6">--}}
{{--                <a href="{{ route('customer.products-return-request.index') }}">--}}
{{--                    <div class="ic-card-head danger">--}}
{{--                        <i class="flaticon-inventory ic-card-icon "></i>--}}
{{--                        <i class="flaticon-inventory big-icon"></i>--}}
{{--                        <h3>{{ $data['total_return_request_amount'] }}</h3>--}}
{{--                        <p>{{ __('custom.return_request_amount') }}</p>--}}
{{--                    </div>--}}
{{--                </a>--}}
{{--            </div>--}}
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6">
                <a href="{{ route('customer.invoices.index') }}">
                    <div class="ic-card-head ">
                        <i class="flaticon-expenses ic-card-icon " style="background-color: #568f53"></i>
                        <i class="flaticon-expenses big-icon"></i>
                        <h3>{{ $data['total_invoice_amount'] }}</h3>
                        <p>{{ __('custom.total') }} {{ __('custom.invoice_amount') }}</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6">
                <a href="{{ route('customer.invoices.index') }}">
                    <div class="ic-card-head warning">
                        <i class="flaticon-expenses ic-card-icon "></i>
                        <i class="flaticon-expenses big-icon"></i>
                        <h3>{{ $data['total_invoice_amount_paid'] }}</h3>
                        <p>{{ __('custom.total') }} {{ __('custom.paid') }}</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6">
                <a href="{{ route('customer.invoices.index') }}">
                    <div class="ic-card-head danger">
                        <i class="flaticon-expenses ic-card-icon "></i>
                        <i class="flaticon-expenses big-icon"></i>
                        <h3>{{ $data['total_invoice_amount'] - $data['total_invoice_amount_paid'] }}</h3>
                        <p>{{ __('custom.total') }} {{ __('custom.due') }}</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- ======== products card end ========  -->

    <!-- ======== top Products start ========  -->

    <div class="ic_products_heads">
        <div class="row">
            <div class="col-xl-6 col-lg-12">
                <div class="card ic-card-height-same">
                    <div class="card-body">
                        <div
                            class="ic-top-products-heading page-title-box pt-0 d-flex align-items-center justify-content-between">
                            <h4 class="page-sub-title ">{{ __('custom.top_product') }}</h4>
                            <div class="float-right d-none d-md-block">
                                <div class="dropdown">
                                    <button class="btn btn-muted dropdown-toggle arrow-none waves-effect waves-light"
                                            type="button" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false" id="btn_ddl">
                                        {{__('custom.month')}} <i class="fas fa-chevron-down ml-2"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item ic-getTop-sale-products prevent-default"
                                           id="year_{{ date('Y') }}" href="javascript:void(0)">
                                            {{ __('custom.year') }}</a>
                                        <a class="dropdown-item ic-getTop-sale-products prevent-default"
                                           id="month_{{ date('Y-m') }}" href="javascript:void(0)">
                                            {{__('custom.month')}}</a>
                                        <a class="dropdown-item ic-getTop-sale-products prevent-default"
                                           id="week_{{ date('Y-m-d') }}" href="javascript:void(0)">
                                            {{__('custom.week')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product-slider-heads">

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div
                            class="ic-top-products-heading page-title-box pt-0 d-flex align-items-center justify-content-between">
                            <h4 class="page-sub-title ">{{ __('custom.best_item_all_time') }}</h4>
                            <div class="float-right d-none d-md-block">
                                <div class="dropdown">
                                    <a href="{{ route('customer.invoices.create') }}"
                                       class="btn btn-secondary2 dropdown-toggle arrow-none waves-effect waves-light">
                                        {{ __('custom.view_all') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="ic-best-products-items">
                            @foreach ($data['most_sale'] as $item)
                                <div class="media d-flex">
                                    <div class="ic-best-products-images">
                                        <img src="{{ $item->thumb_url }}" class="img-fluid inline-block"
                                             alt="product-image">
                                    </div>
                                    <div class="media-body">
                                        <h6><a href="javascript:void(0)">{{ $item->name
                                        }}</a>
                                        </h6>
                                        <p>{{__('custom.total_sale')}} : <span>{{
                                        currencySymbol().make2decimal($item->totalSale()) }}</span></p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======== top Products ens ========  -->

    <!-- ======== products-table start ========  -->

    <div class="ic-products-table">
        <div class="card">
            <div class="card-body">
                <label for="">{{ __('custom.latest_sales') }}</label>
                <div class="table-responsive">
                <table id="table_id" class="datatable table">
                    <thead>
                    <tr>
                        <th>{{ __('custom.sl') }}</th>
                        <th>{{ __('custom.invoice_id') }}</th>
                        <th>{{ __('custom.date') }}</th>
                        <th>{{ __('custom.customer') }}</th>
                        <th>{{ __('custom.total') }}</th>
                        <th>{{ __('custom.paid') }}</th>
                        <th>{{ __('custom.paid_by') }}</th>
                        <th>{{ __('custom.status') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data['latest_sale'] as $item)
                        <tr>
                            <td>{{ ++$loop->index }}</td>
                            <td><a href="{{ route('customer.invoices.show', $item->id) }}">{{ make8digits($item->id) }}</a>
                            </td>
                            <td>{{ custom_date($item->date) }}</td>
                            <td>
                                @if($item->customer_id)
                                    {{ ucfirst($item->customer['full_name'] ?? '') }}
                                @else
                                    {{ ucfirst($item->customer['full_name'] ?? 'Walk-In Customer') }}
                                @endif
                            </td>
                            <td>{{ currencySymbol().make2decimal($item->total) }}</td>
                            <td>{{ currencySymbol().make2decimal($item->total_paid) }}</td>
                            <td>{{ strtoupper($item->payment_type) }}</td>
                            <td>{!! invoiceStatusBadge($item->status) !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ======== products-table ens ========  -->

@endsection

@push('style')

@endpush

@push('script')
    <script type="text/javascript">
        !(function ($) {
            "use strict";

            // top sale product js
            $('.ic-getTop-sale-products').on('click', function () {
                let data = $(this).attr('id');
                let array = data.split('_');
                $('#btn_ddl').html($(this).text() + ' <i class="fas fa-chevron-down ml-2"></i>');
                getTopSaleProducts(array[0], array[1]);
            });
            getTopSaleProducts("month", "{{ date('Y-m') }}")

            function getTopSaleProducts(key, value) {
                if (key == "month") {
                    $.get("{{ route('customer.app-api.get-top-product') }}", {"month": value}, function (response) {
                        let gethtml = '';
                        response.forEach(function (item) {
                            gethtml += `
                            <div class="ic-products-card border">
                                <div class="ic-products-images">
                                    <img src="` + item.thumb_url + `" class="img-fluid"
                                    alt="` + item.name + `">
                                </div>
                                <div class="ic-product-content">
                                    <h6>` + item.name + `</h6>
                                    <p class="mb-0">{{ currencySymbol() }}` + item.total_sale + `</p>
                                </div>
                            </div>
                        `;
                        });
                        $('.product-slider-heads').html(gethtml);
                    })

                } else if (key == "year") {
                    $.get("{{ route('customer.app-api.get-top-product') }}", {"year": value}, function (response) {
                        let gethtml = '';
                        response.forEach(function (item) {
                            gethtml += `
                                <div class="ic-products-card border">
                                    <div class="ic-products-images">
                                        <img src="` + item.thumb_url + `" class="img-fluid"
                                            alt="` + item.name + `">
                                      </div>
                                    <div class="ic-product-content">
                                        <h6>` + item.name + `</h6>
                                        <p class="mb-0">{{ currencySymbol() }}` + item.total_sale + `</p>
                                    </div>
                                </div>
                            `;
                        });
                        $('.product-slider-heads').html(gethtml);
                    });

                } else {
                    $.get("{{ route('customer.app-api.get-top-product') }}", {"week": value}, function (response) {
                        let gethtml = '';
                        response.forEach(function (item) {
                            gethtml += `
                                <div class="ic-products-card border">
                                    <div class="ic-products-images">
                                        <img src="` + item.thumb_url + `" class="img-fluid"
                                            alt="` + item.name + `">
                                      </div>
                                    <div class="ic-product-content">
                                        <h6>` + item.name + `</h6>
                                        <p class="mb-0">{{ currencySymbol() }}` + item.total_sale + `</p>
                                    </div>
                                </div>
                            `;
                        });

                        $('.product-slider-heads').html(gethtml);
                    });

                }
            }

        })(jQuery);
    </script>
@endpush
