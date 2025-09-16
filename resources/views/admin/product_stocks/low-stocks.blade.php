@extends('admin.layouts.master')

@section('content')

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h4 class="page-title">{{ __('custom.low_product_stock') }}</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.product_stock') }}</a>
                </li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-10">
                        <h4>{{ __('custom.low_product_stock') }}</h4>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ url()->previous() }}" class="btn btn-primary float-right"><i class="fa fa-backspace"></i> {{ __('custom.back') }}</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>{{ __('custom.sl') }}</th>
                                    <th>{{ __('custom.product') }}</th>
                                    <th>{{ __('custom.sku') }}</th>
                                    <th>{{ __('custom.barcode') }}</th>
                                    <th>{{ __('custom.price') }}</th>
                                    <th>{{ __('custom.image') }}</th>
                                    <th>{{ __('custom.alert_quantity') }}</th>
                                    <th>{{ __('custom.stock') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($productStockList as $key => $product)
                                    <tr>
                                        <td>{{ ($key+1) }}</td>
                                        <td><a href="{{ route('admin.products.show', $product['id']) }}" class="btn btn-link">{{ $product['name'] }}</a></td>
                                        <td>{{ $product['sku'] }}</td>
                                        <td>{{ $product['barcode'] }}</td>
                                        <td>{{ $product['price'] }}</td>
                                        <td>
                                            <img src="{{ $product['image'] }}" class="img-64" alt="{{ $product['name'] }}">
                                        </td>
                                        <td>{{ $product['alert_quantity'] }}</td>
                                        <td>{{ $product['stock'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
