@extends('admin.layouts.master')

@section('content')
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">{{ __('custom.warehouse_selling_price_report') }}</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.warehouse') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('custom.warehouse_selling_price_report') }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title">{{ __('custom.warehouse_selling_price_report') }}</h4>


                    <div class="text-right">
                        <button type="button" data-div-name="section-to-print-purchases"
                                class="btn btn-warning btn-sm section-print-btn"><i class="fa fa-print"></i> {{
                        __('custom.print') }}</button>
                    </div>
                    <div id="section-to-print-purchases">
                        <p class="mb-0"><b>{{ __('custom.warehouse_selling_price_report') }}:</b> {{ $report_range ?? '' }}</p>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-striped nowrap">
                                <thead>
                                <tr>
                                    <th>SL#</th>
                                    {{-- <th>{{ __('custom.product') }}</th>
                                    <th>{{ __('custom.alert_quantity') }}</th> --}}
                                    <th>{{ __('custom.warehouse') }}</th>
                                    <th class="text-right">{{ __('custom.no_of_product') }}</th>
                                    <th class="text-right">{{ __('custom.total_price') }}</th>
                                    {{-- <th>{{ __('custom.total') }}</th> --}}

                                    {{-- <th>{{ __('custom.stock') }}</th> --}}
                                </tr>

                                </thead>
                                <tbody>
                                    @php

                                        $totalAmout = 0;
                                        $totalStock = 0;

                                    @endphp

                                @foreach($data as $key => $value)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $key }}</td>
                                        <td class="text-right">{{ $value[1] }}</td>
                                        <td class="text-right">{{ currencySymbol() . $value[0] }}</td>


                                    </tr>
                                    @php
                                        $totalAmout += $value[0];
                                        $totalStock += $value[1];
                                    @endphp
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-right">{{ __('custom.total') }}</th>
                                        <th class="text-right">{{ $totalStock }}</th>

                                        <th class="text-right">{{ currencySymbol() . $totalAmout }}</th>
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
    <style>
        /* CSS to align numeric values to the right */
        .text-right {
            text-align: right;
        }
    </style>
@endpush

@push('script')
@endpush
