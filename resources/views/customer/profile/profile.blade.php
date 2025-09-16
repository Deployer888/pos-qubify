@extends('customer.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.profile') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.edit_profile') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="col-lg-12 p-0">
    <div class="card">
        <div class="card-body">
            <h4 class="header-title">{{ __('custom.edit_profile') }}</h4>
{{--            <form action="{{ route('customer.profile.update', $user->id) }}" method="post"--}}
{{--                enctype="multipart/form-data">--}}
{{--                @csrf--}}
{{--                @method('PUT')--}}
{{--                <div class="row">--}}
{{--                    <div class="form-group col-sm-12 col-md-6 col-xl-4">--}}
{{--                        <label>{{ __('custom.first_name') }} <span class="error">*</span></label>--}}
{{--                        <input type="text" name="first_name" class="form-control" placeholder="First Name" required--}}
{{--                            value="{{ $user->first_name }}">--}}

{{--                        @error('first_name')--}}
{{--                        <p class="error">{{ $message }}</p>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
{{--                    <div class="form-group col-sm-12 col-md-6 col-xl-4">--}}
{{--                        <label>{{ __('custom.last_name') }} <span class="error">*</span></label>--}}
{{--                        <input type="text" name="last_name" class="form-control" placeholder="Last Name" required--}}
{{--                               value="{{ $user->last_name }}">--}}

{{--                        @error('last_name')--}}
{{--                        <p class="error">{{ $message }}</p>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
{{--                    <div class="form-group col-sm-12 col-md-6 col-xl-4">--}}
{{--                        <label>{{ __('custom.email') }} <span class="error">*</span></label>--}}
{{--                        <input type="email" name="email" class="form-control" required placeholder="Enter email"--}}
{{--                            value="{{ $user->email }}">--}}

{{--                        @error('email')--}}
{{--                        <p class="error">{{ $message }}</p>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
{{--                    <div class="form-group col-sm-12 col-md-6 col-xl-4">--}}
{{--                        <label>{{ __('custom.phone') }}</label>--}}
{{--                        <input type="tel" id="phone" value="{{ $user->phone ?? '+1' }}" name="phone"--}}
{{--                            class="form-control phone">--}}

{{--                        @error('phone')--}}
{{--                        <p class="error">{{ $message }}</p>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="form-group col-sm-12 col-md-6 col-xl-4">--}}
{{--                        <label>{{ __('custom.password') }} <span class="error">*</span></label>--}}
{{--                        <input type="password" name="password" class="form-control" placeholder="Enter password">--}}

{{--                        @error('password')--}}
{{--                        <p class="error">{{ $message }}</p>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
{{--                    <div class="form-group col-sm-12 col-md-6 col-xl-4">--}}
{{--                        <label>{{ __('custom.confirm_password') }} <span class="error">*</span></label>--}}
{{--                        <input type="password" name="password_confirmation" class="form-control"--}}
{{--                            placeholder="Type password again">--}}

{{--                        @error('password_confirmation')--}}
{{--                        <p class="error">{{ $message }}</p>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="form-group col-sm-12 col-md-6 col-xl-4">--}}
{{--                        <label>{{ __('custom.avatar') }}</label>--}}
{{--                        <small>{{ __('custom.image_support_message') }}</small>--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-sm-8">--}}
{{--                                <div class="ic-form-group position-relative">--}}
{{--                                    <input type="file" id="uploadFile" class="f-input form-control" name="avatar">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-sm-12 col-md-6 col-xl-4">--}}
{{--                                <img class="img-64 mt-2 mt-xl-0" src="{{ $user->avatar_url }}" alt="avatar" />--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        @error('avatar')--}}
{{--                        <p class="error">{{ $message }}</p>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="form-group">--}}
{{--                    <div>--}}
{{--                        <button class="btn btn-primary waves-effect waves-lightml-2" type="submit">--}}
{{--                            <i class="fa fa-save"></i> {{ __('custom.submit') }}--}}
{{--                        </button>--}}
{{--                        <a class="btn btn-danger waves-effect" href="{{ route('customer.dashboard') }}">--}}
{{--                            <i class="fa fa-times"></i> {{ __('custom.cancel') }}--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
            <form class="form-validate" action="{{ route('customer.profile.update', $customer->id) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">

                    <div class="form-group col-sm-6">
                        <label for="">{{__('custom.first_name')}} <span class="error">*</span></label>
                        <input type="text" name="first_name" class="form-control"
                               value="{{ $customer->first_name }}" required>
                        @error('first_name')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="">{{__('custom.last_name')}} <span class="error">*</span></label>
                        <input type="text" name="last_name" class="form-control" value="{{ $customer->last_name }}"
                               required>
                        @error('last_name')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="">{{__('custom.email')}} <span class="error">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ $customer->email }}"
                               required>
                        @error('email')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="">{{__('custom.phone')}} <span class="error">*</span></label>
                        <input type="text" id="phone" name="phone" class="form-control phone"
                               value="{{ $customer->phone ?? '+1' }}" required>
                        @error('phone')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="">{{__('custom.company')}} </label>
                        <input type="text" name="company" class="form-control" value="{{ $customer->company }}">
                        @error('company')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="">{{__('custom.designation')}}</label>
                        <input type="text" name="designation" class="form-control"
                               value="{{ $customer->designation }}">
                        @error('designation')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-sm-12">
                        <label for="" class="text-muted">{{ __('custom.address') }}</label>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="">{{__('custom.address_line_1')}}</label>
                                <input type="text" name="address_line_1" class="form-control"
                                       value="{{ $customer->address_line_1 }}">
                                @error('address_line_1')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="">{{__('custom.address_line_2')}}</label>
                                <input type="text" name="address_line_2" class="form-control"
                                       value="{{ $customer->address_line_2 }}">
                                @error('address_line_2')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group ic-select-gray-bg">
                                    <label for="#">{{ __('custom.country') }} </label>
                                    <select id="country" name="country" class="form-control select2">
                                        <option value="">{{ __('custom.select') }} {{ __('custom.country') }}
                                        </option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" @if($country->id == $customer->country)
                                                selected @endif>
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
                                    <label for="#">{{ __('custom.state') }}</label>
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
                                    <label for="#">{{ __('custom.city') }}</label>
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
                                       value="{{ $customer->zipcode }}"
                                       maxlength="12">
                                @error('zipcode')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group col-sm-12">
                                <label for="short_address">{{ __t('short_address') }} <small>({{ __t('short_address_note') }})</small></label>
                                <textarea name="short_address" class="form-control" id="short_address"
                                          placeholder="{{ __t('short_address') }}">{{ $customer->short_address ?? old('short_address') }}</textarea>
                                @error('short_address')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <label for="" class="text-muted">{{ __('custom.billing_address') }}</label>

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <div class="form-check custom-control custom-checkbox">
                                    <input class="form-check-input custom-control-input" type="checkbox" value="1"
                                           id="billingSameAsAddress" name="billing_same" @if($customer->billing_same)
                                               checked @endif>

                                    <label class="form-check-label checkbox-label custom-control-label"
                                           for="billingSameAsAddress">
                                        {{ __('custom.billing_address_same') }}
                                    </label>
                                </div>

                                @error('is_variant')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="">{{__('custom.first_name')}}</label>
                                <input type="text" name="b_first_name" class="form-control"
                                       value="{{ $customer->b_first_name }}">
                                @error('b_first_name')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="">{{__('custom.last_name')}}</label>
                                <input type="text" name="b_last_name" class="form-control"
                                       value="{{ $customer->b_last_name }}">
                                @error('b_last_name')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="">{{__('custom.email')}}</label>
                                <input type="email" name="b_email" class="form-control"
                                       value="{{ $customer->b_email }}">
                                @error('b_email')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="">{{__('custom.phone')}}</label>
                                <input type="text" id="phone2" name="b_phone" class="form-control phone"
                                       value="{{ $customer->b_phone ?? '+1' }}">
                                @error('b_phone')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="">{{__('custom.address_line_1')}}</label>
                                <input type="text" name="b_address_line_1" class="form-control"
                                       value="{{ $customer->b_address_line_1 }}">
                                @error('b_address_line_1')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="">{{__('custom.address_line_2')}}</label>
                                <input type="text" name="b_address_line_2" class="form-control"
                                       value="{{ $customer->b_address_line_2 }}">
                                @error('b_address_line_2')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group ic-select-gray-bg">
                                    <label for="#">{{ __('custom.country') }}</label>
                                    <select id="country2" name="b_country" class="form-control select2">
                                        <option value="">{{ __('custom.select') }} {{ __('custom.country') }}
                                        </option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" @if($country->id == $customer->b_country)
                                                selected @endif>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('b_country')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group ic-select-gray-bg">
                                    <label for="#">{{ __('custom.state') }}</label>
                                    <select id="state2" name="b_state" class="form-control select2">
                                        <option value="">{{ __('custom.select') }} {{ __('custom.state') }}</option>
                                    </select>

                                    @error('b_state')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group ic-select-gray-bg">
                                    <label for="#"> {{ __('custom.city') }}</label> <select id="city2" name="b_city"
                                                                                            class="form-control select2">
                                        <option value="">{{ __('custom.select') }} {{ __('custom.city') }}</option>
                                    </select>

                                    @error('b_city')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="">{{__('custom.zipcode')}}</label>
                                <input type="number" name="b_zipcode" class="form-control"
                                       value="{{ $customer->b_zipcode }}"
                                       maxlength="12">
                                @error('zipcode')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group col-sm-12">
                                <label for="b_short_address">{{ __t('short_address') }} <small>({{ __t('short_address_note') }})</small></label>
                                <textarea name="b_short_address" class="form-control" id="b_short_address"
                                          placeholder="{{ __t('short_address') }}">{{ $customer->b_short_address ?? old('short_address') }}</textarea>
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
                                    <input type="file" id="uploadFile" class="f-input form-control" name="avatar">
                                </div>
                                @error('avatar')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <img class="img-32" src="{{ $customer->avatar_url }}" alt="avatar" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div>
                        <button class="btn btn-primary waves-effect waves-lightml-2" type="submit">
                            <i class="fa fa-save"></i> <span>{{ __('custom.submit') }}</span>
                        </button>
                        <a class="btn btn-danger waves-effect" href="{{ route('admin.customers.index') }}">
                            <i class="fa fa-times"></i> <span>{{ __('custom.cancel') }}</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> <!-- end col -->
@endsection


@push('style')
@endpush

@push('script')

@endpush
