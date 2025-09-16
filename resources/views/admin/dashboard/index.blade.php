@extends('admin.layouts.master')
@section('content')
    <!-- ======== breadcump start ========  -->
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">{{ __('custom.dashboard') }}</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">{{ __('custom.welcome') }} {{ auth()->user()->name }}</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- ======== breadcump end ========  -->

    @canany([
        'Total Customer',
        'Total Supplier',
        'Total Product',
        'Total Sale',
        'Total Purchase',
        'Total Expenses',
        'Total
        Sale Amount',
        'Total purchase Amount',
        'Total Expenses Amount',
        'Total Sale Return Request',
        'Total Pending Sale Return
        Request',
        'Total Stock',
        'Sale Report Charts',
        'Top Products',
        'Best Items',
        'Latest Sales',
        'Total Product Category',
        ])
        <!-- ======== products card start ========  -->

        <div class="ic-section-gap">
            <div class="row">
                @if (Auth::user()->email == 'clanadmin@app.com')
                    <div class="col-sm-12 pb-3 text-right">
                        <a href="/reset-db" class="btn btn-primary btn-sm right">Reset</a>
                    </div>
                @endif
                @can('Total Customer')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.customers.index') }}">
                            <div class="ic-card-head primary">
                                <i class="flaticon-conversation ic-card-icon"></i>
                                <i class="flaticon-conversation big-icon"></i>
                                <h3>{{ $data['total_customer'] }}</h3>
                                <p>{{ __('custom.total') }} {{ __('custom.customer') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Total Supplier')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.suppliers.index') }}">
                            <div class="ic-card-head secondary">
                                <i class="flaticon-inventory ic-card-icon "></i>
                                <i class="flaticon-inventory big-icon"></i>
                                <h3>{{ $data['total_supplier'] }}</h3>
                                <p>{{ __('custom.total') }} {{ __('custom.supplier') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Total Product')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.products.index') }}">
                            <div class="ic-card-head info">
                                <i class="flaticon-new-product ic-card-icon"></i>
                                <i class="flaticon-new-product big-icon"></i>
                                {{--                        <h3>{{ $data['total_product_with_variant'] }}</h3> --}}
                                <h3>{{ $data['total_product'] }}</h3>
                                <p>{{ __('custom.total') }} {{ __('custom.product') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan

                @can('Total Sale')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.invoices.index') }}">
                            <div class="ic-card-head success">
                                <i class="flaticon-shopping-bag ic-card-icon"></i>
                                <i class="flaticon-shopping-bag big-icon"></i>
                                <h3>{{ $data['total_sale'] }}</h3>
                                <p>{{ __('custom.total') }} {{ __('custom.sale') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Total Purchase')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.purchases.index') }}">
                            <div class="ic-card-head">
                                <i class="flaticon-shopping-bag-1 ic-card-icon " style="background-color: #064420"></i>
                                <i class="flaticon-shopping-bag-1 big-icon"></i>
                                <h3>{{ $data['total_purchase'] }}</h3>
                                <p>{{ __('custom.total') }} {{ __('custom.purchase') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Total Expenses')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.expenses.index') }}">
                            <div class="ic-card-head warning">
                                <i class="flaticon-expenses ic-card-icon "></i>
                                <i class="flaticon-expenses big-icon"></i>
                                <h3>{{ $data['total_expenses'] }}</h3>
                                <p>{{ __('custom.total') }} {{ __('custom.expenses') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Total Sale Amount')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.invoices.index') }}">
                            <div class="ic-card-head success">
                                <i class="flaticon-shopping-bag ic-card-icon"></i>
                                <i class="flaticon-shopping-bag big-icon"></i>
                                <h3>{{ formatNumber($data['total_sale_amount']) }} {{ currencySymbol() }}</h3>
                                <p>{{ __('custom.sale_amount') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Total purchase Amount')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.purchases.index') }}">
                            <div class="ic-card-head primary">
                                <i class="flaticon-shopping-bag-1 ic-card-icon"></i>
                                <i class="flaticon-shopping-bag-1 big-icon"></i>
                                <h3>{{ formatNumber($data['total_purchase_amount']) }} {{ currencySymbol() }}</h3>
                                <p>{{ __('custom.purchase_amount') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Total Expenses Amount')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.expenses.index') }}">
                            <div class="ic-card-head danger">
                                <i class="flaticon-expenses ic-card-icon"></i>
                                <i class="flaticon-expenses big-icon"></i>
                                <h3>{{ formatNumber($data['total_expenses_amount']) }} {{ currencySymbol() }}</h3>
                                <p>{{ __('custom.expenses_amount') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Total Sale Return')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.sales-return.index') }}">
                            <div class="ic-card-head ">
                                <i class="mdi mdi-keyboard-return ic-card-icon" style="background-color: #42343a"></i>
                                <i class="mdi mdi-keyboard-return big-icon"></i>
                                <h3>{{ $data['total_sale_return'] }}</h3>
                                <p>{{ __('custom.sale_returns') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Total Sale Return Request')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.sales-return.requests') }}">
                            <div class="ic-card-head info">
                                <i class="flaticon-inventory ic-card-icon"></i>
                                <i class="flaticon-inventory big-icon"></i>
                                <h3>{{ $data['total_sale_return_request'] }}</h3>
                                <p>{{ __('custom.return_request') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Total Pending Sale Return Request')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.sales-return.requests') }}">
                            <div class="ic-card-head warning">
                                <i class="flaticon-inventory ic-card-icon"></i>
                                <i class="flaticon-inventory big-icon"></i>
                                <h3>{{ $data['total_pending_sale_return_request'] }}</h3>
                                <p>{{ __('custom.pending_return_request') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Total Stock')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.products.index') }}">
                            <div class="ic-card-head">
                                <i class="flaticon-report ic-card-icon" style="background-color: #5b5b25"></i>
                                <i class="flaticon-report big-icon"></i>
                                <h3>{{ $data['total_stock'] }}</h3>
                                <p>{{ __('custom.total') }} {{ __('custom.stock') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Total Product Category')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.product-categories.index') }}">
                            <div class="ic-card-head ">
                                <i class="mdi mdi-database ic-card-icon" style="background-color: #8a5f52"></i>
                                <i class="mdi mdi-database big-icon"></i>
                                <h3>{{ $data['total_product_category'] }}</h3>
                                {{--                        <h3>{{ $data['total_product'] }}</h3> --}}
                                <p>{{ __('custom.total') }} {{ __('custom.product_category') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Total Invoice By Auth User')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.invoices.index') }}">
                            <div class="ic-card-head">
                                <i class="flaticon-shopping-bag-1 ic-card-icon" style="background-color: #503570"></i>
                                <i class="flaticon-shopping-bag-1 big-icon"></i>
                                <h3>{{ $data['invoice_created_by_auth_user'] }}</h3>
                                <p>{{ __('custom.total') }} {{ __('custom.invoice_by_you') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Total Sale By Auth User')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.invoices.index') }}">
                            <div class="ic-card-head">
                                <i class="flaticon-shopping-bag ic-card-icon" style="background-color: #70354c"></i>
                                <i class="flaticon-shopping-bag big-icon"></i>
                                <h3>{{ formatNumber($data['total_sale_by_auth_user']) }} {{ currencySymbol() }}</h3>
                                <p>{{ __('custom.total') }} {{ __('custom.sale_by_you') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Total Warehouse')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.warehouses.index') }}">
                            <div class="ic-card-head">
                                <i class="ti-home ic-card-icon" style="background-color: #43ff00"></i>
                                <i class="ti-home big-icon"></i>
                                <h3>{{ $data['total_warehouse'] }}</h3>
                                <p>{{ __('custom.total') }} {{ __('custom.warehouse') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('Active Coupons')
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <a href="{{ route('admin.coupons.index') }}">
                            <div class="ic-card-head">
                                <i class="mdi mdi-percent ic-card-icon" style="background-color: #a2a1a1"></i>
                                <i class="mdi mdi-percent big-icon"></i>
                                <h3>{{ $data['total_active_coupon'] }}</h3>
                                <p>{{ __('custom.active_coupon') }}</p>
                            </div>
                        </a>
                    </div>
                @endcan
            </div>
        </div>

        <!-- ======== products card end ========  -->

        <!-- ======== chart start ========  -->
        @can('Sale Report Charts')
            <div class="row">
                <div class="col-lg-12 col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-item-center">
                                <div class="col-lg-12 ic-expance-text-heading-part">
                                    <h4 class="ic-expance-heading">{{ __('custom.sales') }} @if (!request()->from_date)
                                            {{ __('custom.this_year') }}
                                        @endif
                                    </h4>
                                    <h3 class="ic-earning-heading">{{ currencySymbol() }}{{ make2decimal($total) }}</h3>
                                </div>
                                <div class="col-lg-8 my-auto ic-expance-form-heads">
                                    <form action="">
                                        <div class="row input-daterange ic-mobile-range">
                                            <div class="col-md-6 col-lg-3">
                                                <div class="form-group mb-lg-0">
                                                    <input type="text" name="from_date" value="{{ request()->from_date }}"
                                                        id="from_date" class="form-control" placeholder="From Date"
                                                        autocomplete="off" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-3">
                                                <div class="form-group mb-lg-0">
                                                    <input type="text" name="to_date" value="{{ request()->to_date }}"
                                                        id="to_date" class="form-control" placeholder="To Date"
                                                        autocomplete="off" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-3 col-12">
                                                <button type="submit" class="btn btn-primary btn-block">
                                                    <i class="mdi mdi-filter"></i> {{ __('custom.filter') }}</button>
                                            </div>
                                            <div class="col-md-6 col-lg-3 col-12">
                                                <a href="{{ route('admin.dashboard') }}"
                                                    class="btn btn-primary btn-block mt-3 mt-md-0">
                                                    <i class="mdi mdi-refresh"></i> {{ __('custom.refresh') }}</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-4 my-auto ic-expance-form-chart input-daterange ic-mobile-range">
                                    <button class="btn btn-secondary" type="button" id='line'><i
                                            class="fas fa-chart-line"></i>
                                        {{ __('custom.line') }}</button>
                                    <button class="btn btn-secondary" type="button" id='bar'><i
                                            class="fas fa-chart-bar"></i>
                                        {{ __('custom.bar') }}</button>
                                </div>
                            </div>
                            <canvas id="salesChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-xl-4">
                    <div class="card ic-max-height-same">
                        <div class="card-body">
                            <div class="ic-expance-part">
                                <div class="ic-expance-text">
                                    <h4 class="ic-expance-heading">{{ __('custom.sales_all_time') }}</h4>
                                    <h3 class="ic-earning-heading">{{ currencySymbol() }}{{ make2decimal($total_all_time) }}</h3>
                                </div>
                            </div>
                            <div class="ic-piechart-part">
                                <canvas id="pieChart"></canvas>
                                <ul>
                                    <li><span class="this-mounth"><span class="circle-this"></span> {{ __('custom.this_month') }}
                                            {{ currencySymbol() }}{{ make2decimal($this_month_total) }}</span></li>
                                    <li><span class="last-mounth"><span class="circle-last"></span> {{ __('custom.last_month') }}
                                            {{ currencySymbol() }}{{ make2decimal($last_month_total) }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan


        <!-- ======== chart end ========  -->

        <!-- ======== top Products start ========  -->
        @canany(['Top Products', 'Best Items'])
            <div class="ic_products_heads">
                <div class="row">
                    @can('Top Products')
                        <div class="col-xl-6 col-lg-12">
                            <div class="card ic-card-height-same">
                                <div class="card-body">
                                    <div
                                        class="ic-top-products-heading page-title-box pt-0 d-flex align-items-center justify-content-between">
                                        <h4 class="page-sub-title ">{{ __('custom.top_product') }}</h4>
                                        <div class="float-right d-none d-md-block">
                                            <div class="dropdown">
                                                <button class="btn btn-muted dropdown-toggle arrow-none waves-effect waves-light"
                                                    type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                    id="btn_ddl">
                                                    {{ __('custom.month') }} <i class="fas fa-chevron-down ml-2"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item ic-getTop-sale-products prevent-default"
                                                        id="year_{{ date('Y') }}" href="#">
                                                        {{ __('custom.year') }}</a>
                                                    <a class="dropdown-item ic-getTop-sale-products prevent-default"
                                                        id="month_{{ date('Y-m') }}" href="#">
                                                        {{ __('custom.month') }}</a>
                                                    <a class="dropdown-item ic-getTop-sale-products prevent-default"
                                                        id="week_{{ date('Y-m-d') }}" href="#">
                                                        {{ __('custom.week') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-slider-heads">

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan
                    @can('Best Items')
                        <div class="col-xl-6 col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div
                                        class="ic-top-products-heading page-title-box pt-0 d-flex align-items-center justify-content-between">
                                        <h4 class="page-sub-title ">{{ __('custom.best_item_all_time') }}</h4>
                                        <div class="float-right d-none d-md-block">
                                            <div class="dropdown">
                                                <a href="{{ route('admin.products.index') }}"
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
                                                    <h6><a
                                                            href="{{ route('admin.products.edit', $item->id) }}">{{ $item->name }}</a>
                                                    </h6>
                                                    <p>{{ __('custom.total_sale') }} :
                                                        <span>{{ currencySymbol() . make2decimal($item->totalSale()) }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
        @endcanany
        <!-- ======== top Products ens ========  -->

        <!-- ======== products-table start ========  -->
        @can('Latest Sales')
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
                                            <td><a
                                                    href="{{ route('admin.invoices.show', $item->id) }}">{{ make8digits($item->id) }}</a>
                                            </td>
                                            <td>{{ custom_date($item->date) }}</td>
                                            <td>
                                                @if ($item->customer_id)
                                                    {{ ucfirst($item->customer['full_name'] ?? '') }}
                                                @else
                                                    {{ ucfirst($item->customer['full_name'] ?? 'Walk-In Customer') }}
                                                @endif
                                            </td>
                                            <td>{{ currencySymbol() . make2decimal($item->total) }}</td>
                                            <td>{{ currencySymbol() . make2decimal($item->total_paid) }}</td>
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
        @endcan
        <!-- ======== products-table ens ========  -->
    @endcanany
@endsection

@push('style')
@endpush

@push('script')
    <script type="text/javascript">
        !(function($) {
            "use strict";

            // top sale product js
            $('.ic-getTop-sale-products').on('click', function() {
                let data = $(this).attr('id');
                let array = data.split('_');
                $('#btn_ddl').html($(this).text() + ' <i class="fas fa-chevron-down ml-2"></i>');
                getTopSaleProducts(array[0], array[1]);
            });
            getTopSaleProducts("month", "{{ date('Y-m') }}")

            function getTopSaleProducts(key, value) {
                if (key == "month") {
                    $.get("{{ route('admin.app-api.get-top-product') }}", {
                        "month": value
                    }, function(response) {
                        let gethtml = '';
                        response.forEach(function(item) {
                            console.log(item);
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
                    $.get("{{ route('admin.app-api.get-top-product') }}", {
                        "year": value
                    }, function(response) {
                        let gethtml = '';
                        response.forEach(function(item) {
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
                    $.get("{{ route('admin.app-api.get-top-product') }}", {
                        "week": value
                    }, function(response) {
                        let gethtml = '';
                        response.forEach(function(item) {
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

            // line Chart
            const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                'October', 'November', 'December'
            ];
            const data = {
                labels: labels,
                datasets: [{
                    label: '{{ __('custom.sales') }}({{ currencySymbol() }})',
                    backgroundColor: '#FF5733',
                    borderColor: '#FF5733',
                    data: @php echo json_encode($graph_data) @endphp,
                }]
            };

            // init config
            const config = {
                type: 'line',
                data,
                options: {}
            };

            var myChart;

            icChange('line');
            $("#line").on('click', function() {
                icChange('line');
            });

            $("#bar").on('click', function() {
                icChange('bar');
            });

            function icChange(newType) {
                var ctx = document.getElementById("salesChart").getContext("2d");

                if (myChart) {
                    myChart.destroy();
                }

                var temp = jQuery.extend(true, {}, config);
                temp.type = newType;
                myChart = new Chart(ctx, temp);
            };

            // pie chart
            var oilCanvas = document.getElementById("pieChart");
            var oilData = {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                    'October', 'November', 'December'
                ],
                datasets: [{
                    data: @php echo json_encode($pie_graph_data) @endphp,
                    backgroundColor: [
                        "#FF6384",
                        "#63FF84",
                        "#6FE3D5",
                        "#5182FF",
                        "#56C876",
                        "#2A73A8",
                        "#EEBF48",
                        "#6FE3C0",
                        "#28AAA9",
                        "#6FE3C0",
                        "#3D96FF",
                        "#E36F6F"
                    ]
                }]
            };

            var pieChart = new Chart(oilCanvas, {
                type: 'pie',
                data: oilData,
                options: {
                    responsive: true,
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                },
            });

        })(jQuery);
    </script>
@endpush
