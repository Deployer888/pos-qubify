@extends('admin.layouts.master')

@section('content')

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.attribute') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.edit_attribute') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ __('custom.edit_attribute') }}</h4>
                <form class="form-validate" action="{{ route('admin.attributes.update', $attribute->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.attribute_name')}} <span class="error">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $attribute->name }}" required>
                            @error('name')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <attribute-edit :items="{{ $attribute->items }}"></attribute-edit
                            :items="{{ $attribute->items }}">

                        <div class="form-group col-sm-6">
                            <label class="d-block mb-3">{{ __('custom.status') }} <span class="error">*</span></label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="status_yes" value="{{ \App\Models\Attribute::STATUS_ACTIVE }}"
                                    name="status" class="custom-control-input"
                                    @if(\App\Models\Attribute::STATUS_ACTIVE==$attribute->status) checked @endif>
                                <label class="custom-control-label" for="status_yes">{{ __('custom.active') }}</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="status_no" value="{{ \App\Models\Attribute::STATUS_INACTIVE }}"
                                    name="status" class="custom-control-input"
                                    @if(\App\Models\Attribute::STATUS_INACTIVE==$attribute->status) checked @endif>
                                <label class="custom-control-label" for="status_no">{{ __('custom.inactive') }}</label>
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
                            <a class="btn btn-danger waves-effect" href="{{ route('admin.attributes.index') }}">
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