@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.purchases') }}</a></li>
                <li class="breadcrumb-item active">{{ __t('add').' '.__t('purchase') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ __t('add').' '.__t('purchase') }}</h4>

                @include('includes.messages.validation')

                <form class="form-validate" action="{{ route('admin.purchases.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-5">

                        <div class="form-group col-sm-6">
                            <label for="supplier">{{ __t('supplier') }} <span class="error">*</span></label>
                            <select name="supplier" id="supplier" class="form-control select2" required="true">
                                <option value="">- {{ __t('select').' '.__t('supplier') }}-
                                </option>
                                @foreach($suppliers as $supplier)
                                <option {{ old('supplier')==$supplier->id ? 'selected' : '' }}
                                    value="{{ $supplier->id }}">{{ $supplier->full_name }}</option>
                                @endforeach
                            </select>

                            @error('supplier')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="warehouse">{{ __t('warehouse') }} <span class="error">*</span></label>
                            <select name="warehouse" id="warehouse" class="form-control select2" required="true">
                                <option value="">- {{ __t('select').' '.__t('warehouse') }}-
                                </option>

                                @foreach($warehouses as $key => $warehouse)
                                <option {{ old('warehouse')==$key ? 'selected' : '' }} value="{{ $key }}">
                                    {{ $warehouse }}</option>
                                @endforeach

                            </select>

                            @error('warehouse')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="company">{{ __t('company') }}</label>
                            <input type="text" class="form-control" name="company" id="company"
                                value="{{ old('company') }}" placeholder="{{ __t('company') }}">

                            @error('company')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="date">{{ __t('date') }} <span class="error">*</span></label>
                            <input type="text" class="form-control datepicker-autoclose" name="date" id="date"
                                value="{{ old('date') ?? date('Y-m-d') }}" required placeholder="{{ __t('date') }}" autocomplete="off">

                            @error('date')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-sm-12">
                            <label for="" class="text-muted">{{ __('custom.address') }}</label>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="">{{__('custom.address_line_1')}} </label>
                                    <input type="text" name="address_line_1" class="form-control"
                                        value="{{ old('address_line_1') }}">
                                    @error('address_line_1')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-6">
                                    <label for="">{{__('custom.address_line_2')}}</label>
                                    <input type="text" name="address_line_2" class="form-control"
                                        value="{{ old('address_line_2') }}">
                                    @error('address_line_2')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group ic-select-gray-bg">
                                        <label for="#">{{ __('custom.country') }}</label>
                                        <select id="country" name="country" class="form-control select2">
                                            <option value="">{{ __('custom.select') }} {{ __('custom.country') }}
                                            </option>
                                            @foreach($countries as $country)
                                            <option value="{{ $country->id }}">
                                                {{ $country->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('country')
                                        <p class="error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group ic-select-gray-bg">
                                        <label for="#">{{ __('custom.state') }} </label>
                                        <select id="state" name="state" class="form-control select2">
                                            <option value="">{{ __('custom.select') }} {{ __('custom.state') }}</option>
                                        </select>

                                        @error('state')
                                        <p class="error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group ic-select-gray-bg">
                                        <label for="#">{{ __('custom.city') }} </label>
                                        <select id="city" name="city" class="form-control select2">
                                            <option value="">{{ __('custom.select') }} {{ __('custom.city') }}</option>
                                        </select>

                                        @error('city')
                                        <p class="error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label for="">{{__('custom.zipcode')}} </label>
                                    <input type="number" name="zipcode" class="form-control"
                                        value="{{ old('zipcode') }}"
                                        maxlength="12">
                                    @error('zipcode')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="short_address">{{ __t('short_address') }} <small>({{ __t('short_address_note') }})</small></label>
                                    <textarea name="short_address" class="form-control" id="short_address"
                                              placeholder="{{ __t('short_address') }}">{{ old('short_address') }}</textarea>
                                    @error('short_address')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-12">
                                    <label for="note">{{ __t('note') }}</label>
                                    <textarea name="note" class="form-control" id="note"
                                              placeholder="{{ __t('note') }}">{{ old('note') }}</textarea>
                                    @error('note')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>


                    <purchase-add currency_symbol="{{currencySymbol()}}"></purchase-add>

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
