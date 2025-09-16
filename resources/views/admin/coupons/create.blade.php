@extends('admin.layouts.master')

@section('content')

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.coupon') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.add_coupon') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ __('custom.add_coupon') }}</h4>
                <form class="form-validate" action="{{ route('admin.coupons.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.coupon_title')}} <span class="error">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                            @error('title')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.coupon_code')}} <span class="error">*</span></label>
                            <input type="text" name="code" class="form-control" value="{{ old('code') }}">
                            @error('code')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.discount_type')}}</label>
{{--                            <input type="text" step="any" name="discount_type" class="form-control" value="{{ old('discount_type') }}">--}}
                            <select name="discount_type" class="form-control select2">
                                <option value="percent">{{ __('%') }}</option>
                                <option value="fixed">{{ __('Fixed') }}</option>
                            </select>
                            @error('discount_type')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.discount')}}</label>
                            <input type="number" step="any" name="discount" class="form-control" value="{{ old('discount') }}">
                            @error('discount')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.start_date')}}</label>
                            <input type="date" step="any" name="start_date" class="form-control datepicker-autoclose" value="{{ old('date') ?? date('Y-m-d') }}" autocomplete="off">
                            @error('start_date')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.end_date')}}</label>
                            <input type="date" step="any" name="end_date" class="form-control datepicker-autoclose" value="{{ old('end_date') }}">
                            @error('end_date')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.minimum_shopping')}}</label>
                            <input type="number" step="any" name="minimum_shopping" class="form-control" value="{{ old('minimum_shopping') }}">
                            @error('minimum_shopping')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6 d-none">
                            <label for="">{{__('custom.maximum_discount')}}</label>
                            <input type="number" step="any" name="maximum_discount" class="form-control" value="{{ old('maximum_discount') }}">
                            @error('maximum_discount')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.banner')}}</label>
                            <small>{{ __('custom.image_support_message') }}</small>
                            <div class="form-group">
                                <input type="file" id="uploadFile" class="f-input form-control" name="banner"
                                    value="{{ old('banner') }}">
                            </div>
                            @error('banner')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="form-group col-sm-6">
                            <label class="d-block mb-3">{{ __('custom.status') }} <span class="error">*</span></label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="status_yes" value="{{ \App\Models\Coupon::STATUS_ACTIVE }}"
                                    name="status" class="custom-control-input" checked="">
                                <label class="custom-control-label" for="status_yes">{{__('custom.active')}}</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="status_no" value="{{ \App\Models\Coupon::STATUS_INACTIVE }}"
                                    name="status" class="custom-control-input">
                                <label class="custom-control-label" for="status_no">{{__('custom.inactive')}}</label>
                            </div>

                            @error('status')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div>
                            <button class="btn btn-primary waves-effect waves-lightml-2" type="submit">
                                <i class="fa fa-save"></i> <span>{{ __('custom.submit') }}</span>
                            </button>
                            <input type="hidden" id="is_submit_set_product" name="is_submit_set_product">
                            <button class="btn btn-info waves-effect waves-lightml-2" type="submit" id="submit_set_product">
                                <i class="fa fa-save"></i> <span>{{ __('custom.submit_and_set_product') }}</span>
                            </button>
                            <a class="btn btn-danger waves-effect" href="{{ route('admin.coupons.index') }}">
                                <i class="fa fa-times"></i> <span>{{ __('custom.cancel') }}</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
@endpush

@push('script')

@endpush
