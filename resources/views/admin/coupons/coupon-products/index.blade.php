@extends('admin.layouts.master')

@section('content')
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">

                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}" class="">{{ __('custom.coupon_list') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('custom.coupon_product_list') }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">{{ __('custom.coupon_product_list') }}</h4>
                    {!! $dataTable->table(['class' => 'nowrap']) !!}
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">{{ __('custom.coupon_product_add') }}</h4>
                    <form class="form-validate" action="{{ route('admin.coupon.product.store') }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <input type="hidden" name="coupon_id" value="{{ $coupon->id }}">
                                <label for="">{{__('custom.product')}} <span class="error">*</span></label>
                                <select id="product_id" name="product_id" class="form-control select2" >
                                    <option value="">{{ __('custom.select') }} {{ __('custom.product') }}</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>

                                @error('product_id')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="float-right">
                                <button class="btn btn-primary waves-effect waves-lightml-2" type="submit">
                                    <i class="fa fa-save"></i> <span>{{ __('custom.submit') }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('style')
    @include('includes.styles.datatable')
@endpush

@push('script')
    @include('includes.scripts.datatable')
@endpush
