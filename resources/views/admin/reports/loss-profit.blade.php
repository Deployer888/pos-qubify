@extends('admin.layouts.master')

@section('content')
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">{{ __('custom.loss_profit_report') }}</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.reports') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('custom.loss_profit_report') }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title">{{ __('custom.loss_profit_report') }}</h4>
                    <div class="row">
                        <div class="col-sm-10">
                            <form action="{{ route('admin.report.loss-profit') }}">
                                <div class="row input-daterange">
                                    <div class="col-md-6 col-lg-3">
                                        <div class="form-group mb-lg-0">
                                            <select name="warehouse" id="reportPurchaseWarehouses" class="form-control">
                                                <option value="">{{ __('custom.select_warehouse') }}</option>
                                                @foreach($wareHouses as $key => $value)
                                                    <option
                                                        {{ request('warehouse') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="form-group mb-lg-0">
                                            <input type="text" name="from_date" value="{{ request()->from_date }}"
                                                   id="from_date" class="form-control" placeholder="From Date"
                                                   autocomplete="off" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="form-group mb-lg-0">
                                            <input type="text" name="to_date" value="{{ request()->to_date }}"
                                                   id="to_date"
                                                   class="form-control" placeholder="To Date" autocomplete="off"
                                                   required/>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3 col-6">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="mdi mdi-filter"></i> {{ __('custom.generate') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-sm-2">
                            <form action="{{ route('admin.report.loss-profit') }}">
                                <div class="input-daterange">
                                    <input type="hidden" name="q" value="all-time">
                                    <input type="hidden" name="warehouse" value="{{ request('warehouse') }}"
                                           id="allTimeWarehouse">
                                    <button type="submit" class="btn btn-secondary w-100">
                                        <i class="mdi mdi-filter"></i> {{ __('custom.all_time') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <br>

                    <div class="text-right">
                        <hr>
                        <button type="button" data-div-name="section-to-print-payments"
                                class="btn btn-warning btn-sm section-print-btn"><i class="fa fa-print"></i> {{
                        __('custom.print') }}</button>
                    </div>
                    <div id="section-to-print-payments">
                        <p class="mb-0"><b>{{ __('custom.loss_profit_report') }}:</b> {{ $report_range ?? '' }}</p>
                        <table class="table table-sm table-bordered table-striped nowrap">
                            <thead>
                            <tr>
                                <th>{{ __('custom.sales') }} {{ __('custom.qty') }}</th>
                                <th>{{ __('custom.purchase') }} {{ __('custom.qty') }}</th>
                                <th>{{ __('custom.assets') }} {{ __('custom.qty') }}</th>
                                <th>{{ __('custom.sale') }} {{ __('custom.amount') }}</th>
                                <th>{{ __('custom.purchase') }} {{ __('custom.amount') }}</th>
                                <th>{{ __('custom.profit') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $loss_profit_data['total_sale_qty'] }}</td>
                                    <td>{{ $loss_profit_data['total_purchase_qty'] }}</td>
                                    <td>{{ $loss_profit_data['total_asset_qty'] }}</td>
                                    <td>{{ currencySymbol(). $loss_profit_data['total_sales_price'] }}</td>
                                    <td>{{ currencySymbol(). $loss_profit_data['total_purchase_price'] }}</td>
                                    <td>{{ currencySymbol(). number_format($loss_profit_data['total_profit'], 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
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
