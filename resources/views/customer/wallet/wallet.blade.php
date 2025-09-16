@extends('customer.layouts.master')
@section('content')
    <div class="row p-4">
        <div class="col-12">
            <!-- Right Sidebar -->
            <div class="mb-3">
                <div class="card p-4">
                    <div class="row align-items-end">
                        <div class="col-sm-10">
                            <form id="filtter_data" method="GET" action="{{ route('customer.allwalletamount') }}">
                                <div class="row input-daterange">
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group mb-lg-0">
                                            <input type="date" name="from_date" id="from_date" placeholder="From Date"
                                                autocomplete="off" value="{{ request()->from_date }}" required="required"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group mb-lg-0">
                                            <input type="date" name="to_date" id="to_date" placeholder="To Date"
                                                value="{{ request()->to_date }}" autocomplete="off" required="required"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-12">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="mdi mdi-filter"></i> Generate
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-2">
                            <form id="allTimeWarehouse" method="GET" action="{{ route('admin.markasreadall') }}">
                                <input type="hidden" name="from_date" value="">
                                <input type="hidden" name="to_date" value="">
                                <button type="submit" class="btn btn-secondary w-100">
                                    <i class="mdi mdi-filter"></i> All Time
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <h4>{{ __('custom.wallet_history') }}</h4>
                        <table class="table table-sm table-bordered table-striped nowrap">
                            <thead>
                                <tr>
                                    <th>{{ __('custom.sl') }}#</th>
                                    <th>{{ __('custom.date') }}</th>
                                    <th>{{ __('custom.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($wallets as $wallet)
                                    <tr>
                                        <td>{{ ++$loop->index }}</td>
                                        <td>{{ $wallet->created_at->format('Y-m-d') }}</td>
                                        <td>{{ currencySymbol() . make2decimal($wallet->amount) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate ic-paginate paging_simple_numbers">
                                {{ $wallets->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
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
