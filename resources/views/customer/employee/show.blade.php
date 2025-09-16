@extends('customer.layouts.master')
@section('content')
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.customer') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('custom.customer_details') }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="emp-profile ic-employee-warper-container">
                        <form method="post">
                            <div class="ic-customer-details-warper">
                                <div class="ic-customer-profile-basic-info">
                                    <div class="profile-img">
                                        <img class="img-thumbnail"
                                            src="{{ getStorageImage(\App\Models\Customer::FILE_STORE_PATH, $customer->avatar) }}"
                                            alt="{{ $customer->full_name }}" />
                                    </div>
                                    <div class="ic-customer-basic-info">
                                        <h5 class="text-muted">{{ __t('basic_info') }}</h5>
                                        <div class="profile-head">
                                            <h5>
                                                {{ $customer->full_name }}
                                            </h5>
                                            <h6>
                                                {{ $customer->email }}
                                            </h6>
                                            <p class="mb-0 ic-discription-customer">
                                                {{ $customer->phone }}
                                            </p>
                                            <p class="mb-0 ic-discription-customer">
                                                {{ __t('company') }}: {{ $customer->company }}
                                            </p>
                                            <p class="mb-0 ic-discription-customer">
                                                {{ __t('designation') }}: {{ $customer->designation }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="ic-profile-details-goback">
                                    <a href="{{ route('admin.customers.index') }}" class="btn btn-primary float-right"><i
                                            class="fa fa-backspace"></i> {{ __t('back') }}</a>
                                </div>
                            </div>
                            <div class="ic-customer-details-info-warper">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="customer-billing-info">
                                            <h5 class="text-muted">{{ __t('billing_info') }}</h5>
                                            <div class="profile-head">
                                                <h5>
                                                    {{ $customer->b_first_name . ' ' . $customer->b_last_name }}
                                                </h5>
                                                <h6>
                                                    {{ $customer->b_email }}
                                                </h6>
                                                <p class="mb-0 ic-discription-customer">
                                                    {{ $customer->b_phone }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="ic-customer-address">
                                            <div class="profile-head">
                                                <h5 class="text-muted">{{ __t('address') }}</h5>
                                                <address class="ic-address-info-customer">
                                                    {!! $customer->address_line_1 ? $customer->address_line_1 . ', <br>' : '' !!}
                                                    {!! $customer->address_line_2 ? $customer->address_line_2 . ', <br>' : '' !!}
                                                    {!! $customer->city ? optional($customer->systemCity)->name . ', ' : '' !!}
                                                    {!! $customer->state ? optional($customer->systemState)->name . ', ' : '' !!}
                                                    {!! $customer->country
                                                        ? optional($customer->systemCountry)->name .
                                                            ',
                                                                                                                                                            '
                                                        : '' !!}
                                                    {!! $customer->zipcode !!},
                                                </address>
                                                <address class="ic-address-info-customer">
                                                    {{ __t('short_address') }}: {{ $customer->short_address }}
                                                </address>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="ic-customer-billing-address">
                                            <div class="profile-head">
                                                <h5 class="text-muted">{{ __t('billing_address') }}</h5>
                                                <address class="ic-address-info-customer">
                                                    {!! $customer->b_address_line_1
                                                        ? $customer->b_address_line_1 .
                                                            ',
                                                                                                                                                            <br>'
                                                        : '' !!}
                                                    {!! $customer->b_address_line_2
                                                        ? $customer->b_address_line_2 .
                                                            ',
                                                                                                                                                            <br>'
                                                        : '' !!}
                                                    {!! optional($customer->b_city_data)->name ? optional($customer->b_city_data)->name . ',' : '' !!}
                                                    {!! optional($customer->b_state_data)->name ? optional($customer->b_state_data)->name . ',' : '' !!}
                                                    {!! optional($customer->b_country_data)->name ? optional($customer->b_country_data)->name . ',' : '' !!}
                                                    {!! $customer->b_zipcode ? $customer->b_zipcode . ',' : '' !!}
                                                </address>
                                                <address class="ic-address-info-customer">
                                                    {{ __t('short_address') }}: {{ $customer->b_short_address }}
                                                </address>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="ic-customer-status">
                                            <h5 class="text-muted">{{ __t('status') }}</h5>
                                            <h6 title="{{ __t('status') }}">
                                                @if ($customer->status == \App\Models\Customer::STATUS_ACTIVE)
                                                    <span class="badge badge-success"><i class="fa fa-check-circle"></i>
                                                        {{ ucfirst($customer->status) }}</span>
                                                @else
                                                    <span class="badge badge-danger"><i class="fa fa-times-circle"></i>
                                                        {{ ucfirst($customer->status) }}</span>
                                                @endif
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
@endpush

@push('script')
    @include('includes.scripts.country_state_city_auto_load', ['address_data' => $customer])
    @include('includes.scripts.country_state_city_auto_load_2', ['address_data' => $customer])
@endpush
