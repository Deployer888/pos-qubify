@extends('admin.layouts.master')

@section('content')

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.supplier') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.add_supplier') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ __('custom.add_supplier') }}</h4>
                <form class="form-validate" action="{{ route('admin.suppliers.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.first_name')}} <span class="error">*</span></label>
                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}"
                                required>
                            @error('first_name')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.last_name')}} <span class="error">*</span></label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}"
                                required>
                            @error('last_name')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.email')}} <span class="error">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            @error('email')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.phone')}} <span class="error">*</span></label>
                            <input type="text" id="phone" name="phone" class="form-control phone"
                                value="{{ old('phone') ?? '+1' }}" required>
                            @error('phone')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.company')}} </label>
                            <input type="text" name="company" class="form-control" value="{{ old('company') }}">
                            @error('company')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.designation')}}</label>
                            <input type="text" name="designation" class="form-control" value="{{ old('designation') }}">
                            @error('designation')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-sm-12">
                            <label for="" class="text-muted">{{ __('custom.address') }}</label>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="">{{__('custom.address_line_1')}} </label>
                                    <input type="text" name="address_line_1" class="form-control"
                                        value="{{ old('address_line_1') }}" >
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
                                        <label for="#">{{ __('custom.country') }} </label>
                                        <select id="country" name="country" class="form-control select2" >
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
                                        <select id="state" name="state" class="form-control select2" >
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
                                        <select id="city" name="city" class="form-control select2" >
                                            <option value="">{{ __('custom.select') }} {{ __('custom.city') }}</option>
                                        </select>

                                        @error('city')
                                        <p class="error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label for="">{{__('custom.zipcode')}}</label>
                                    <input type="number" name="zipcode" class="form-control"
                                        value="{{ old('zipcode') }}"
                                        maxlength="12" >
                                    @error('zipcode')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-12">
                                    <label for="short_address">{{ __t('short_address') }} <small>({{ __t('short_address_note') }})</small></label>
                                    <textarea name="short_address" class="form-control" id="short_address"
                                              placeholder="{{ __t('short_address') }}">{{ old('short_address') }}</textarea>
                                    @error('short_address')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-12 col-lg-12 col-xl-6">
                            <label for="">{{__('custom.avatar')}}</label>
                            <small class="font-12">{{ __('custom.image_support_message') }}</small>
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <input type="file" id="uploadFile" class="f-input form-control image_pick" data-image-for="avatar" name="avatar">
                                    </div>
                                    @error('avatar')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <img class="img-32 default-image-size" src="{{ static_asset('images/default-64.png') }}" alt="images" id="img_avatar"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-sm-6">
                            <label class="d-block mb-3">{{ __('custom.status') }} <span class="error">*</span></label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="status_yes" value="{{ \App\Models\Supplier::STATUS_ACTIVE }}"
                                    name="status" class="custom-control-input" checked="">
                                <label class="custom-control-label" for="status_yes">{{__('custom.active')}}</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="status_no" value="{{ \App\Models\Supplier::STATUS_INACTIVE }}"
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
                            <a class="btn btn-danger waves-effect" href="{{ route('admin.suppliers.index') }}">
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
@include('includes.scripts.country_state_city_auto_load')

@endpush
