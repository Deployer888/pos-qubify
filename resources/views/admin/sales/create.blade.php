@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h4 class="page-title">{{ __('custom.add_sale') }}</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.sales') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.add_sale') }}</li>
            </ol>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <sale-add :all_status="{{ json_encode($all_status) }}" :customers="{{ json_encode($customers) }}">
                </sale-add>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
@endpush

@push('script')
@endpush