@extends('admin.layouts.master')

@section('content')
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.product') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('custom.show_product_details') }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4 class="header-title">{{ __('custom.show_product_details') }}</h4>
                        </div>
                        <div class="col-lg-6 d-print-none ic-print-btn-head">
                            <a href="{{ url()->previous() }}" class="btn btn-info mr-2"><i class="fa fa-arrow-left"></i>
                                Back</a>
                        </div>
                    </div>
                    <h4 class="header-title"></h4>

                    <dl class="row">
                        <dt class="col-sm-3">{{ __('custom.product_name') }}</dt>
                        <dd class="col-sm-9">: {{ $product_details->name }}</dd>

                        <dt class="col-sm-3">{{ __('custom.sku') }}</dt>
                        <dd class="col-sm-9">: {{ $product_details->sku }}</dd>

                        <dt class="col-sm-3">{{ __('custom.barcode') }}</dt>
                        <dd class="col-sm-9">: {{ $product_details->barcode }}</dd>

                        <dt class="col-sm-3 text-truncate">{{ __('custom.category_name') }}</dt>
                        <dd class="col-sm-9">: {{ optional($product_details->category)->name }}</dd>

                        <dt class="col-sm-3">{{ __('custom.manufacturer') }}</dt>
                        <dd class="col-sm-9">
                            : {{ optional($product_details->manufacturer)->name }}
                        </dd>
                        <dt class="col-sm-3">{{ __('custom.model') }}</dt>
                        <dd class="col-sm-9">
                            : {{ $product_details->model }}
                        </dd>
                        <dt class="col-sm-3">{{ __('custom.price') }}</dt>
                        <dd class="col-sm-9">
                            : {{ currencySymbol() . ' ' . $product_details->price }}
                        </dd>
                        <dt class="col-sm-3">{{ __('custom.weight') }}</dt>
                        <dd class="col-sm-9">
                            : {{ $product_details->weight }}
                        </dd>
                        <dt class="col-sm-3">{{ __('custom.weight_unit') }}</dt>
                        <dd class="col-sm-9">
                            : {{ optional($product_details->weight_unit)->name }}
                        </dd>
                        <dt class="col-sm-3">{{ __('custom.notes') }}</dt>
                        <dd class="col-sm-9">
                            : {{ $product_details->notes }}
                        </dd>
                        <dt class="col-sm-3">{{ __('custom.desc') }}</dt>
                        <dd class="col-sm-9">
                            : {!! $product_details->desc !!}
                        </dd>
                        <dt class="col-sm-3">{{ __('custom.custom_tax_amount') }}</dt>
                        <dd class="col-sm-9">
                            : {{ $product_details->custom_tax }} %
                        </dd>
                        <dt class="col-sm-3">{{ __('custom.tax') }}</dt>
                        <dd class="col-sm-9">
                            : {{ $product_details->tax_status == \App\Models\Product::TAX_INCLUDED ? 'included' : 'Excluded' }}
                        </dd>
                        <dt class="col-sm-3">{{ __('custom.is_variant') }}</dt>
                        <dd class="col-sm-9">
                            : {{ $product_details->is_variant ? 'Yes' : 'No' }}
                        </dd>
                        <dt class="col-sm-3">{{ __('custom.is_split_sale') }}</dt>
                        <dd class="col-sm-9">
                            : {{ $product_details->split_sale ? 'Yes' : 'No' }}
                        </dd>
                        <dt class="col-sm-3">{{ __('custom.image') }}</dt>
                        <dd class="col-sm-9">
                            <img
                                src="{{ getStorageImage(\App\Models\Product::FILE_STORE_PATH, $product_details->thumb) }}"
                                width="60px" alt="{{ $product_details->name }}">
                        </dd>

                        <dt class="col-sm-3">{{ __('custom.stock_quantity') }}</dt>
                        <dd class="col-sm-6">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>{{ __('custom.warehouse') }}</th>
                                    @if($product_details->is_variant)
                                        <th>{{ __('custom.attribute') }}</th>
                                    @endif
                                    <th>{{ __('custom.stock_quantity') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($product_details->allStock->groupBy('warehouse_id') as $key => $warehouseDetails)
                                    @foreach($warehouseDetails as $wareHousekey => $stock)
                                        <tr>
                                            @if($wareHousekey == 0)
                                                <td rowspan="{{ count($warehouseDetails) }}">{{ $warehouses[$key] }}    </td>
                                            @endif
                                            @if($product_details->is_variant)
                                                <td>{{ optional($stock->attribute)->name }}
                                                    : {{ optional($stock->attributeItem)->name }}</td>
                                            @endif
                                            <td>{{ $stock->quantity }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                        </dd>
                    </dl>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
@endpush

@push('script')
@endpush
