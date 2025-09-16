@extends('customer.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.invoice') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.invoice_list') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
{{--        <div class="ic-invoice-card-wraper">--}}
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">{{ __('custom.invoice_list') }}</h4>
                    {!! $dataTable->table(['class' => 'nowrap']) !!}
                </div>
            </div>
{{--        </div>--}}

    </div>
</div>

@endsection

@push('style')
@include('includes.styles.datatable')
@endpush

@push('script')
@include('includes.scripts.datatable')

@endpush
