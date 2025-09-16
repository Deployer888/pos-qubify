@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h4 class="page-title">{{ __('custom.expenses_report') }}</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.expenses') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.expenses_report') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title">{{ __('custom.expenses_report') }}</h4>
                <div class="row">
                    <div class="col-sm-9">
                        <form action="{{ route('admin.reports.expenses') }}">
                            <div class="row input-daterange">
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group mb-lg-0">
                                        <input type="text" name="from_date" value="{{ request()->from_date }}"
                                            id="from_date" class="form-control" placeholder="From Date"
                                            autocomplete="off" required />
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group mb-lg-0">
                                        <input type="text" name="to_date" value="{{ request()->to_date }}" id="to_date"
                                            class="form-control" placeholder="To Date" autocomplete="off" required />
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4 col-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="mdi mdi-filter"></i> {{ __('custom.generate') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-sm-3">
                        <form action="{{ route('admin.reports.expenses') }}">
                            <div class="input-daterange">
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
                    <button type="button" data-div-name="section-to-print-expenses"
                        class="btn btn-warning btn-sm section-print-btn"> <i class="fa fa-print"></i> {{
                        __('custom.print') }}</button>
                    <a href="{{ route('admin.reports.export.expenses', ['type'=>'pdf', 'from_date'=>request()->from_date, 'to_date'=>request()->to_date]) }}"
                        class="btn btn-pdf btn-sm"> <i class="fa fa-file-pdf"></i> {{ __('custom.pdf') }}</a>
                    <a href="{{ route('admin.reports.export.expenses', ['type'=>'csv', 'from_date'=>request()->from_date, 'to_date'=>request()->to_date]) }}"
                        class="btn btn-success btn-sm"> <i class="fa fa-file-csv"></i> {{ __('custom.csv') }}</a>
                    <a href="{{ route('admin.reports.export.expenses', ['type'=>'excel', 'from_date'=>request()->from_date, 'to_date'=>request()->to_date]) }}"
                        class="btn btn-excel btn-sm"> <i class="fa fa-file-csv"></i> {{ __('custom.excel') }}</a>
                </div>
                <div id="section-to-print-expenses">
                    <p class="mb-0"><b>{{ __('custom.expenses_report') }}:</b> {{ $report_range ?? '' }}</p>
                    <p><b>{{ __('custom.total') }}:</b> {{ currencySymbol().make2decimal($total ?? 0) }}</p>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped nowrap">
                            <thead>
                                <tr>
                                    <th>SL#</th>
                                    <th width="10%">{{ __('custom.date') }}</th>
                                    <th>{{ __('custom.title') }}</th>
                                    <th width="10%">{{ __('custom.category') }}</th>
                                    <th width="10%">{{ __('custom.total') }}</th>
                                    <th width="30%">{{ __('custom.notes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                    <td>{{ ++$loop->index }}</td>
                                    <td>{{ $item->date }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->category->name ?? '' }}</td>
                                    <td>{{ currencySymbol().make2decimal($item->total) }}</td>
                                    <td>{{ $item->notes }}</td>
                                </tr>
                                @endforeach
                            </tbody>
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