@extends('admin.layouts.master')

@section('content')
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.supplier') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('custom.supplier_details') }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="emp-profile ic-employee-warper-container">
                        <div class="ic-customer-details-warper">
                            <div class="ic-customer-profile-basic-info supplier">
                                <div class="profile-img">
                                    <img class="img-thumbnail"
                                        src="{{ getStorageImage(\App\Models\Supplier::FILE_STORE_PATH, $supplier->avatar) }}"
                                        alt="{{ $supplier->full_name }}" />
                                </div>
                                <div class="ic-customer-basic-info">
                                    <h5 class="text-muted">{{ __t('basic_info') }}</h5>
                                    <div class="profile-head">
                                        <h5>
                                            {{ $supplier->full_name }}
                                        </h5>
                                        <h6>
                                            {{ $supplier->email }}
                                        </h6>
                                        <p class="ic-discription-customer mb-0">
                                            {{ $supplier->phone }}
                                        </p>
                                        <p class="ic-discription-customer mb-0">
                                            {{ __t('company') }}: {{ $supplier->company }}
                                        </p>
                                        <p class="ic-discription-customer mb-0">
                                            {{ __t('designation') }}: {{ $supplier->designation }}
                                        </p>
                                    </div>
                                </div>
                                <div class="ic-customer-adress-info">
                                    <div class="profile-head">
                                        <h5 class="text-muted">{{ __t('address') }}</h5>
                                        <address class="ic-address-info-customer">
                                            {!! $supplier->address_line_1 ? $supplier->address_line_1 . ', <br>' : '' !!}
                                            {!! $supplier->address_line_2 ? $supplier->address_line_2 . ', <br>' : '' !!}
                                            {!! $supplier->city ? optional($supplier->systemCity)->name . ', ' : '' !!}
                                            {!! $supplier->state ? optional($supplier->systemState)->name . ', ' : '' !!}
                                            {!! $supplier->country ? optional($supplier->systemCountry)->name . ', ' : '' !!}
                                            {!! $supplier->zipcode !!},
                                        </address>
                                        <address class="ic-address-info-customer">
                                            {{ __t('short_address') }}: {{ $supplier->short_address }}
                                        </address>
                                        <h6 title="{{ __t('status') }}">
                                            @if ($supplier->status == \App\Models\Customer::STATUS_ACTIVE)
                                                <span class="badge badge-success"><i class="fa fa-check-circle"></i>
                                                    {{ ucfirst($supplier->status) }}</span>
                                            @else
                                                <span class="badge badge-danger"><i class="fa fa-times-circle"></i>
                                                    {{ ucfirst($supplier->status) }}</span>
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="ic-profile-details-goback">
                                <a href="{{ route('admin.customers.index') }}" class="btn btn-primary float-right"><i
                                        class="fa fa-backspace"></i> {{ __t('back') }}</a>
                            </div>
                        </div>

                        <section id="tabs" class="project-tab">
                            <div class=" ic-employee-warper-container">
                                <div class="row">
                                    <div class="col-md-12 p-0">
                                        <nav class="ic-customer-details-tab">
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab"
                                                    href="#nav-home" role="tab" aria-controls="nav-home"
                                                    aria-selected="true">{{ __t('purchase_history') }}</a>
                                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab"
                                                    href="#nav-profile" role="tab" aria-controls="nav-profile"
                                                    aria-selected="false">{{ __t('product_history') }}</a>
                                            </div>
                                        </nav>
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                                aria-labelledby="nav-home-tab">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h6 class="text-muted text-center">{{ __t('purchase_history') }}
                                                        </h6>
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>{{ __t('purchase_number') }}</th>
                                                                        <th>{{ __t('date') }}</th>
                                                                        <th>{{ __t('total') }}</th>
                                                                        <th>{{ __t('total_product') }}</th>
                                                                        <th>{{ __t('status') }}</th>
                                                                        <th>{{ __t('received') }}</th>
                                                                        <th>{{ __t('missing_item') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($purchases as $purchase)
                                                                        <tr>
                                                                            <td><a target="_blank" class="btn btn-link"
                                                                                    href="{{ route('admin.purchases.show', $purchase->id) }}">{{ make8digits($purchase->purchase_number) }}</a>
                                                                            </td>
                                                                            <td>
                                                                                {{ date('F m, Y', strtotime($purchase->date)) }}
                                                                                <br>
                                                                                <small>{{ date('H:i:s A', strtotime($purchase->created_at)) }}</small>
                                                                            </td>
                                                                            <td>{{ currencySymbol() . ' ' . $purchase->total }}
                                                                            </td>
                                                                            <td>{{ $purchase->purchaseItems->count() }}
                                                                            </td>
                                                                            <td>
                                                                                @php
                                                                                    $badge =
                                                                                        $purchase->status ==
                                                                                        \App\Models\Purchase::STATUS_REQUESTED
                                                                                            ? 'badge-success'
                                                                                            : ($purchase->status ==
                                                                                            \App\Models\Purchase::STATUS_CONFIRMED
                                                                                                ? 'badge-primary'
                                                                                                : 'badge-danger');
                                                                                @endphp
                                                                                <span
                                                                                    class="badge {{ $badge }}">{{ \Illuminate\Support\Str::upper($purchase->status) }}</span>
                                                                            </td>
                                                                            <td>
                                                                                @if ($purchase->received)
                                                                                    <span
                                                                                        class="badge badge-success">{{ __t('received') }}</span>
                                                                                @else
                                                                                    <span
                                                                                        class="badge badge-warning">{{ __t('not_received_yet') }}</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if ($purchase->received)
                                                                                    @php
                                                                                        $purchaseItemQty = $purchase->purchaseItems->sum(
                                                                                            'quantity',
                                                                                        );
                                                                                        $purchaseReceiveItemQty = 0;
                                                                                        foreach (
                                                                                            $purchase->purchaseItems
                                                                                            as $purchaseItems
                                                                                        ) {
                                                                                            $purchaseReceiveItemQty += $purchaseItems->receiveItems->sum(
                                                                                                'quantity',
                                                                                            );
                                                                                        }
                                                                                    @endphp
                                                                                    @if ($purchaseItemQty != $purchaseReceiveItemQty)
                                                                                        <span
                                                                                            class="badge badge-danger">{{ __t('missing') }}</span>
                                                                                    @endif
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        {!! $purchases->links() !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                                aria-labelledby="nav-profile-tab">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h6 class="text-muted text-center">{{ __t('product_history') }}
                                                        </h6>
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
                                                                        <td>{{ $product->first()['product_name'] }}</td>
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

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
@endpush

@push('script')
    {{-- @include('includes.scripts.country_state_city_auto_load', ['address_data'=> $supplier]) --}}
    {{-- @include('includes.scripts.country_state_city_auto_load_2', ['address_data'=> $supplier]) --}}
@endpush
