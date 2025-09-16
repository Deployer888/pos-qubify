@extends('admin.layouts.master')

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
                            <section id="tabs" class="project-tab">
                                <div class="ic-employee-warper-container">
                                    <div class="row">
                                        <div class="col-md-12 p-0">
                                            <nav class="ic-customer-details-tab">
                                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab"
                                                        href="#nav-home" role="tab" aria-controls="nav-home"
                                                        aria-selected="true">{{ __t('invoice_history') }}</a>
                                                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab"
                                                        href="#nav-profile" role="tab" aria-controls="nav-profile"
                                                        aria-selected="false">{{ __t('product_history') }}</a>
                                                    <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab"
                                                        href="#nav-contact" role="tab" aria-controls="nav-contact"
                                                        aria-selected="false">{{ __t('to_pay') }}</a>
                                                </div>
                                            </nav>
                                            <div class="tab-content" id="nav-tabContent">
                                                <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                                    aria-labelledby="nav-home-tab">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h6 class="text-muted text-center">{{ __t('invoice_history') }}
                                                            </h6>
                                                            <div class="table-responsive">
                                                                <table class="table table-striped table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>{{ __t('invoice_id') }}</th>
                                                                            <th>{{ __t('date') }}</th>
                                                                            <th>{{ __t('total') }}</th>
                                                                            <th>{{ __t('total_paid') }}</th>
                                                                            <th>{{ __t('payment_type') }}</th>
                                                                            <th>{{ __t('status') }}</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($invoices as $invoice)
                                                                            <tr>
                                                                                <td><a target="_blank" class="btn btn-link"
                                                                                        href="{{ route('admin.invoices.show', $invoice->id) }}">{{ make8digits($invoice->id) }}</a>
                                                                                </td>
                                                                                <td>
                                                                                    {{ date('F m, Y', strtotime($invoice->date)) }}
                                                                                    <br>
                                                                                    <small>{{ date('H:i:s A', strtotime($invoice->date)) }}</small>
                                                                                </td>
                                                                                <td>{{ currencySymbol() . ' ' . $invoice->total }}
                                                                                </td>
                                                                                <td>{{ currencySymbol() . ' ' . $invoice->total_paid }}
                                                                                </td>
                                                                                <td>{{ ucfirst($invoice->payment_type) }}
                                                                                </td>
                                                                                <td>{!! invoiceStatusBadge($invoice->status) !!}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            {!! $invoices->links() !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                                    aria-labelledby="nav-profile-tab">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h6 class="text-muted text-center">
                                                                {{ __t('product_history') }}</h6>
                                                            <table class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>{{ __t('product_id') }}</th>
                                                                        <th>{{ __t('product_name') }}</th>
                                                                        <th>{{ __t('sku') }}</th>
                                                                        <th>{{ __t('price') }}</th>
                                                                        <th>{{ __t('quantity') }}</th>
                                                                        <th>{{ __t('sub_total') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                    @php
                                                                        $products = collect($products)->groupBy(
                                                                            'product_id',
                                                                        );
                                                                    @endphp
                                                                    @foreach ($products as $key => $product)
                                                                        <tr>
                                                                            <td><a target="_blank" class="btn btn-link"
                                                                                    href="{{ route('admin.products.edit', $key) }}">{{ make8digits($key) }}</a>
                                                                            </td>
                                                                            <td>{{ $product->first()['product_name'] }}
                                                                            </td>
                                                                            <td>{{ $product->first()['sku'] }}</td>
                                                                            <td>{{ $product->first()['price'] }}</td>
                                                                            <td>{{ $product->sum('quantity') }}</td>
                                                                            <td>{{ currencySymbol() . ' ' . $product->first()['price'] * $product->sum('quantity') }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                                    aria-labelledby="nav-contact-tab">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h6 class="text-muted text-center">{{ __t('to_pay') }}</h6>
                                                            <table class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>{{ __t('invoice_id') }}</th>
                                                                        <th>{{ __t('date') }}</th>
                                                                        <th>{{ __t('total') }}</th>
                                                                        <th>{{ __t('total_paid') }}</th>
                                                                        <th>{{ __t('payment_type') }}</th>
                                                                        <th>{{ __t('status') }}</th>
                                                                        <th>{{ __t('action') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($not_paid_invoices as $invoice)
                                                                        <tr>
                                                                            <td><a target="_blank" class="btn btn-link"
                                                                                    href="{{ route('admin.invoices.show', $invoice->id) }}">{{ make8digits($invoice->id) }}</a>
                                                                            </td>
                                                                            <td>
                                                                                {{ date('F m, Y', strtotime($invoice->date)) }}
                                                                                <br>
                                                                                <small>{{ date('H:i:s A', strtotime($invoice->date)) }}</small>
                                                                            </td>
                                                                            <td>{{ currencySymbol() . ' ' . $invoice->total }}
                                                                            </td>
                                                                            <td>{{ currencySymbol() . ' ' . $invoice->total_paid }}
                                                                            </td>
                                                                            <td>{{ ucfirst($invoice->payment_type) }}</td>
                                                                            <td>{!! invoiceStatusBadge($invoice->status) !!}
                                                                            </td>
                                                                            <td>
                                                                                <a href="{{ route('admin.invoices.makePayment', $invoice->id) }}"
                                                                                    title="{{ __t('make_payment') }}"
                                                                                    class="btn btn-sm btn-primary"><i
                                                                                        class="fa fa-money-bill"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                            {!! $invoices->links() !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
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
