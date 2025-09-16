@extends('admin.layouts.master')

@section('content')
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">{{ __('custom.sales_report') }}</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.sales') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('custom.sales_report') }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title">{{ __('custom.sales_report') }}</h4>
                    <div class="row">
                        <div class="col-sm-10">
                            <form action="{{ route('admin.reports.sales') }}">
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
                                    <div class="col-md-4 col-lg-3">
                                        <div class="form-group mb-lg-0">
                                            <input type="text" name="from_date" value="{{ request()->from_date }}"
                                                   id="from_date" class="form-control" placeholder="From Date"
                                                   autocomplete="off" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-3">
                                        <div class="form-group mb-lg-0">
                                            <input type="text" name="to_date" value="{{ request()->to_date }}"
                                                   id="to_date"
                                                   class="form-control" placeholder="To Date" autocomplete="off"
                                                   required/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-3 col-12">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="mdi mdi-filter"></i> {{ __('custom.generate') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-sm-2">
                            <form action="{{ route('admin.reports.sales') }}">
                                <div class="input-daterange">
                                    <input type="hidden" name="warehouse" value="{{ request('warehouse') }}"
                                           id="allTimeWarehouse">
                                    <input type="hidden" name="q" value="all-time">
                                    <button type="submit" class="btn btn-secondary w-100">
                                        <i class="mdi mdi-filter"></i> {{ __('custom.all_time') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <br>

                    @if ($data)
                        <div class="text-right">
                            <hr>
                            <button type="button" data-div-name="section-to-print-sales"
                                    class="btn btn-warning btn-sm section-print-btn"><i class="fa fa-print"></i> {{
                        __('custom.print') }}</button>
                            <a href="{{ route('admin.reports.export.sales', ['type'=>'pdf', 'from_date'=>request()->from_date, 'to_date'=>request()->to_date]) }}"
                               class="btn btn-pdf btn-sm"> <i class="fa fa-file-pdf"></i> {{ __('custom.pdf') }}</a>
                            <a href="{{ route('admin.reports.export.sales', ['type'=>'csv', 'from_date'=>request()->from_date, 'to_date'=>request()->to_date]) }}"
                               class="btn btn-success btn-sm"> <i class="fa fa-file-csv"></i> {{ __('custom.csv') }}</a>
                            <a href="{{ route('admin.reports.export.sales', ['type'=>'excel', 'from_date'=>request()->from_date, 'to_date'=>request()->to_date]) }}"
                               class="btn btn-excel btn-sm"> <i class="fa fa-file-csv"></i> {{ __('custom.excel') }}</a>
                        </div>
                        <div id="section-to-print-sales">
                            <p class="mb-0"><b>{{ __('custom.sales_report') }}:</b> {{ $report_range ?? '' }}</p>
                            <p class="mb-0"><b>{{ __('custom.gross_total') }}:</b> {{ currencySymbol().make2decimal($gross_total
                        ?? 0) }}</p>
                            <p class="mb-0"><b>{{ __('custom.net_total') }}:</b> {{ currencySymbol().make2decimal($total_paid ??
                        0) }}</p>
                            <p><b>{{ __('custom.due') }}
                                    :</b> {{ currencySymbol().make2decimal($gross_total - $total_paid) }}
                            </p>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-striped nowrap">
                                    <thead>
                                    <tr>
                                        <th>{{ __('custom.sl') }}#</th>
                                        <th>{{ __('custom.invoice_id') }}</th>
                                        <th width="10%">{{ __('custom.warehouse') }}</th>
                                        <th width="10%">{{ __('custom.date') }}</th>
                                        <th>{{ __('custom.customer') }}</th>
                                        <th>{{ __('custom.tax') }}</th>
                                        <th>{{ __('custom.discount') }}</th>
                                        <th>{{ __('custom.total') }}</th>
                                        <th>{{ __('custom.due') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $totalAmount = 0;
                                        $totalDueAmount = 0;
                                    @endphp
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ ++$loop->index }}</td>
                                            <td>
                                                <a href="{{ route('admin.invoices.show', $item->id) }}"
                                                   class="btn btn-link" target="_blank">{{ make8digits($item->id) }}</a>
                                            </td>
                                            <td>{{ optional($item->warehouse)->name }}</td>
                                            <td>{{ custom_date($item->date) }}</td>
                                            <td>
                                                @if ($item->customer_id)
                                                    {{ucfirst($item->customer['full_name'] ?? '')}}
                                                @else
                                                    {{ucfirst($item->customer['full_name'] ?? 'Walk-In Customer')}}
                                                @endif
                                            </td>
                                            <td>{{ currencySymbol().make2decimal($item->tax_amount) }}</td>
                                            <td>{{ currencySymbol().make2decimal($item->discount_amount) }}</td>
                                            <td>{{ currencySymbol().make2decimal($item->total) }}</td>
                                            <td>{{ currencySymbol().make2decimal($item->total - $item->total_paid) }}</td>
                                        </tr>
                                        @php
                                            $totalAmount += $item->total;
                                            $totalDueAmount += ($item->total - $item->total_paid);
                                        @endphp
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="7">{{ __('custom.total') }}</th>
                                        <th>{{ currencySymbol().make2decimal($totalAmount) }}</th>
                                        <th>{{ currencySymbol().make2decimal($totalDueAmount) }}</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

@endsection

@push('style')

@endpush

@push('script')

@endpush
