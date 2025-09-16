@extends('customer.layouts.master')
@section('content')
    <div class="row p-4">
        <div class="col-12">
            <!-- Right Sidebar -->
            <div class="mb-3">
                <div class="card p-4">
                    <div class="row align-items-end">
                        <div class="col-sm-10">
                            <form id="filtter_data" method="GET" action="{{ route('customer.markasreadall') }}">
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

                    <ul class="message-list mt-4">
                        @isset($notifications)
                            @foreach ($notifications as $notification)
                                <li class="{{ $notification->read_at ? 'read' : 'unread' }}">
                                    <div class="col-mail col-mail-1">
                                        <a href="#" class="title">{{ $notification->data['title'] }}</a>
                                    </div>
                                    <div class="col-mail col-mail-2">
                                        <a href="{{ $notification->data['url'] }}"
                                            data-notification-id="{{ $notification->id }}"
                                            class="subject notify-item notification-item">
                                            {{ $notification->data['message'] }}
                                        </a>
                                        <div class="date">{{ $notification->created_at->format('M j') }}</div>
                                    </div>
                                </li>
                            @endforeach
                        @endisset
                    </ul>
                    @if ($notifications->total() > 1)
                        <div class="row px-3">
                            <!-- Pagination Info -->
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" role="status" aria-live="polite">
                                    Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }} of
                                    {{ $notifications->total() }} notifications
                                </div>
                            </div>

                            <!-- Pagination Links -->
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate ic-paginate paging_simple_numbers">
                                    {{ $notifications->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </div>
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
