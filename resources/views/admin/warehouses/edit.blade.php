@extends('admin.layouts.master')

@section('content')

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.warehouse') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.edit_warehouse') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ __('custom.edit_warehouse') }}</h4>

                <form class="form-validate" action="{{ route('admin.warehouses.update', $warehouse->id) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="form-group col-sm-6 col-lg-6 col-xl-4">
                            <label for="">{{__('custom.warehouse_name')}} <span class="error">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $warehouse->name }}" required>
                            @error('name')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6 col-lg-6 col-xl-4">
                            <label for="">{{__('custom.email')}}</label>
                            <input type="email" name="email" class="form-control" value="{{ $warehouse->email }}">
                            @error('email')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6 col-lg-6 col-xl-4">
                            <label for="">{{__('custom.phone')}}</label>
                            <input id="phone" type="text" name="phone" class="form-control w-100 phone"
                                value="{{ $warehouse->phone ?? '+1' }}">
                            @error('phone')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6 col-lg-6 col-xl-4">
                            <label for="">{{__('custom.company_name')}}</label>
                            <input type="text" name="company_name" class="form-control"
                                value="{{ $warehouse->company_name }}">
                            @error('company_name')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6 col-lg-6 col-xl-4">
                            <label for="address_1">{{__('custom.address_1')}}</label>
                            <input type="text" class="form-control" name="address_1"
                                value="{{ $warehouse->address_1 }}">
                            @error('address_1')
                            <p class=" error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6 col-lg-6 col-xl-4">
                            <label for="address_2">{{__('custom.address_2')}}</label>
                            <input type="text" class="form-control" name="address_2"
                                value="{{ $warehouse->address_2 }}">
                            @error('address_2')
                            <p class=" error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.barcode')}} <span class="error">*</span></label>
                            <div class="row">
                                <div class="col-sm-8">
                                    <input type="text" id="barcode" name="barcode" class="form-control"
                                           placeholder="Product Barcode" value="{{ $warehouse->barcode ?? Carbon\Carbon::now()->timestamp }}">
                                    @error('barcode')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <img class="img-fluid max-width-50p barcode-image barcode-max-height"
                                         id="b-image-show" alt="barcode">
                                    <input id="barcode-value" type="hidden" name="barcode_image">
                                </div>
                            </div>
                        </div>



                        <div class="form-group col-sm-6 col-lg-6 col-xl-4">
                            <label for="">{{__('custom.priority')}}
                                <p class="p-0 m-0">
                                    <small>
                                        {{__('custom.warehouse_priority_message')}}
                                    </small>
                                </p>
                            </label>
                            <input type="number" name="priority" class="form-control"
                                value="{{ $warehouse->priority }}">
                            @error('priority')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6 col-lg-6 col-xl-4">
                            <label class="text-white">{{__('custom.default')}}</label>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="is_default"
                                    @if($warehouse->is_default) checked @endif value="1"
                                id="is_default">
                                <label class="custom-control-label"
                                    for="is_default">{{__('custom.is_default_warehouse')}}</label>
                            </div>
                            @error('is_default')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="d-block mb-3">{{ __('custom.status') }} <span class="error">*</span></label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="status_yes" value="{{ \App\Models\Warehouse::STATUS_ACTIVE }}"
                                    name="status" class="custom-control-input"
                                    @if(\App\Models\Warehouse::STATUS_ACTIVE==$warehouse->status) checked @endif>
                                <label class="custom-control-label" for="status_yes">{{__('custom.active')}}</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="status_no" value="{{ \App\Models\Warehouse::STATUS_INACTIVE }}"
                                    name="status" class="custom-control-input"
                                    @if(\App\Models\Warehouse::STATUS_INACTIVE==$warehouse->status) checked @endif>
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
                            <a class="btn btn-danger waves-effect" href="{{ route('admin.warehouses.index') }}">
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
