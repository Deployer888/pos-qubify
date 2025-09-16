@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __t('purchases') }}</a></li>
                <li class="breadcrumb-item active">{{ __t('cancel').' '.__t('purchase') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ __t('cancel').' '.__t('purchase') }}</h4>

                <form class="form-validate" action="{{ route('admin.purchases.cancel', $purchase->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-5">

                        <div class="form-group col-sm-6">
                            <label for="date">{{ __t('date') }} <span class="error">*</span></label>
                            <input type="text" class="form-control datepicker-autoclose" name="date" id="date"
                                value="{{ old('date') }}" required placeholder="{{ __t('date') }}" autocomplete="off">

                            @error('date')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="note">{{ __t('note') }} <span class="error">*</span></label>
                            <textarea name="note" class="form-control" id="note"
                                placeholder="{{ __t('note') }}">{{ old('note') }}</textarea>
                            @error('note')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div>
                                    <button class="btn btn-primary waves-effect waves-lightml-2" type="submit">
                                        <i class="fa fa-save"></i> <span>{{ __('custom.submit') }}</span>
                                    </button>
                                    <a class="btn btn-danger waves-effect" href="{{ route('admin.purchases.index') }}">
                                        <i class="fa fa-times"></i> <span>{{ __('custom.cancel') }}</span>
                                    </a>
                                </div>
                            </div>
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
@include('includes.scripts.country_state_city_auto_load')

@endpush