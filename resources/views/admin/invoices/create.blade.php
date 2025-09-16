@extends('admin.layouts.master')

@section('content')
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.invoice') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('custom.create_invoice') }}</li>
                </ol>
            </div>
        </div>
    </div>

    @if(count($warehouses) > 1)
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-body">
                    <div class="row">
                        <div class="col-sm-12 col-lg-3">
                            <form action="" id="invoiceCreate">
                                <div class="form-group">
                                    <select name="warehouse" id="invoiceCreateWarehouse" class="form-control select2"
                                            onchange="">
                                        <option value="" disabled selected>- {{ __('custom.select_warehouse') }}-
                                        </option>
                                        @foreach($warehouses as $id => $name)
                                            <option
                                                {{ request('warehouse') == $id ? 'selected' : '' }} value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>

                        @if(request('warehouse'))
                            <div class="col-sm-12 col-lg-3 offset-lg-6">
                                <h4 class="m-0">{{ $warehouse->name }}</h4>
                            </div>
                        @else
                            <div class="col-sm-12 col-lg-4 offset-lg-5">
                                <h4 class="m-0">{{ __('custom.no_warehouse_selected') }}</h4>
                            </div>
                        @endif
                    </div>


                </div>
            </div>
        </div>
    @endif
    @if(count($warehouses) > 1 && request('warehouse'))
        <div class="row">
            <div class="col-sm-12">
                <invoice-add app_name="{{ config('site_title') ?? config('app.name') }}" default_tax="{{ getDefaultTax() }}"
                             :user="{{ auth()->user() }}" :all_status="{{ json_encode($all_status) }}"
                             :customers="{{ json_encode($customers) }}" :product_stocks="{{ json_encode($product_stocks) }}"
                             :categories="{{ json_encode($categories) }}" :warehouse_id="{{ $warehouse->id }}"
                             :currency_symbol="{{ json_encode(currencySymbol()) }}">
                </invoice-add>
            </div>
        </div>
    @elseif(count($warehouses) == 1)
        <div class="row">
            <div class="col-sm-12">
                <invoice-add app_name="{{ config('site_title') ?? config('app.name') }}" default_tax="{{ getDefaultTax() }}"
                             :user="{{ auth()->user() }}" :all_status="{{ json_encode($all_status) }}"
                             :customers="{{ json_encode($customers) }}" :product_stocks="{{ json_encode($product_stocks) }}"
                             :categories="{{ json_encode($categories) }}" :warehouse_id="{{ $warehouse->id }}"
                             :currency_symbol="{{ json_encode(currencySymbol()) }}">
                </invoice-add>
            </div>
        </div>
    @endif

@endsection

@push('style')

@endpush

@push('script')

@endpush
