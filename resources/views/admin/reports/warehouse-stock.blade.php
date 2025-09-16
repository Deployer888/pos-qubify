@extends('admin.layouts.master')

@section('content')
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">{{ __('custom.warehouse_stock_report') }}</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.warehouse') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('custom.warehouse_stock_report') }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title">{{ __('custom.warehouse_stock_report') }}</h4>


                    <div class="text-right">
                        <button type="button" data-div-name="section-to-print-purchases"
                                class="btn btn-warning btn-sm section-print-btn"><i class="fa fa-print"></i> {{
                        __('custom.print') }}</button>
                    </div>
                    <div id="section-to-print-purchases">
                        <p class="mb-0"><b>{{ __('custom.warehouse_stock_report') }}:</b> {{ $report_range ?? '' }}</p>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-striped nowrap">
                                <thead>
                                <tr>
                                    <th>SL#</th>
                                    <th>{{ __('custom.product') }}</th>
                                    <th>{{ __('custom.alert_quantity') }}</th>
                                    <th>{{ __('custom.warehouse') }}</th>
                                    <th>{{ __('custom.attribute') }}</th>
                                    <th>{{ __('custom.stock') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $key => $product)
                                    @foreach($product->allStock as $stocKey => $stock)
                                        <tr>
                                            @if($stocKey == 0)
                                                <td rowspan="{{ $product->allStock->count() }}">
                                                    {{ ($key+1) }}
                                                </td>
                                                <td rowspan="{{ $product->allStock->count() }}">
                                                    <a target="_blank" href="{{ route('admin.products.show', $product->id) }}">{{ $product->name }}</a>
                                                    <br>
                                                    <small>{{ __('custom.sku') .':'. $product->sku }},
                                                        {{ __('custom.category') .':'. optional($product->category)->name }},
                                                        {{ __('custom.barcode') .':'. $product->barcode }}
                                                    </small>
                                                </td>
                                                    <td rowspan="{{ $product->allStock->count() }}">{{ $product->stock_alert_quantity }}</td>
                                                @endif
                                            <td>
                                                <a target="_blank" href="{{ route('admin.warehouses.show', $stock->warehouse_id) }}">
                                                    {{ isset($warehouses[$stock->warehouse_id]) ? $warehouses[$stock->warehouse_id] : '' }}
                                                </a>
                                            </td>

                                            <td>
                                                @if($stock->attribute_id)
                                                    {{ optional($stock->attribute)->name .':'. optional($stock->attributeItem)->name }}
                                                @endif
                                            </td>
                                            <td>{{ $stock->quantity }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
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
