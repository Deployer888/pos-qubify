@extends('admin.layouts.master-live')

@section('content')
    <div class="row">
        <div class="col-sm-12 p-4">
            <div class="card">
                <div class="card-body" id="print-invoice">
                    <div class="row">
                        <div class="col-12">
                            <div class="invoice-title">
                                <h3 class="mt-0">
                                    {{ config('site_title') ?? config('app.name') }}
                                </h3>
                            </div>
                            <hr>
                            <div>
                                <div class="table-responsive ic-responsive-invoice">
                                    <table cellpadding="0" cellspaceing="0" border="0" width="100%">
                                        <tr>
                                            <td>
                                                <address class="ic-invoice-addess">
                                                    <strong>{{ __('custom.billed_to') }}:</strong><br>
                                                    <p class="mb-0">{{ $invoice->billing_info['name'] ?? '' }}</p>
                                                    <p class="mb-0">{{ $invoice->billing_info['email'] ?? '' }}</p>
                                                    <p class="mb-0">{{ $invoice->billing_info['phone_number'] ?? '' }}</p>
                                                    <p class="mb-0">{{ $invoice->billing_info['address_line_1'] ?? '' }}
                                                        , {{
                                                    $invoice->billing_info['address_line_2'] ?? '' }}</p>
                                                    <p class="mb-0">{{ $invoice->billing_info['zip'] ?? '' }},{{
                                                    $invoice->billing_info['city'] ?? '' }},{{
                                                    $invoice->billing_info['state'] ?? '' }},{{
                                                    $invoice->billing_info['country'] ?? '' }}</p>
                                                </address>
                                            </td>
                                            <td>
                                                <address class="ic-invoice-addess">
                                                    <strong>{{ __('custom.shipped_to') }}:</strong>
                                                    <p class="mb-0">{{ $invoice->shipping_info['name'] ?? '' }}</p>
                                                    <p class="mb-0">{{ $invoice->shipping_info['email'] ?? '' }}</p>
                                                    <p class="mb-0">{{ $invoice->shipping_info['phone_number'] ?? '' }}</p>
                                                    <p class="mb-0">{{ $invoice->shipping_info['address_line_1'] ?? '' }}
                                                        ,{{
                                                    $invoice->shipping_info['address_line_2'] ?? '' }}</p>
                                                    <p class="mb-0">{{ $invoice->shipping_info['zip'] ?? '' }}, {{
                                                    $invoice->shipping_info['city'] ?? '' }}, {{
                                                    $invoice->shipping_info['state'] ?? '' }}, {{
                                                    $invoice->shipping_info['country'] ?? '' }}</p>
                                                </address>
                                            </td>
                                            <td>
                                                <address class="ic-invoice-addess text-right">
                                                    <strong>{{ __('custom.invoice') }}:</strong>
                                                    <p class="mb-0">{{ __('custom.invoice_id') }} # {{ make8digits($invoice->id)
                                                    }}</p>
                                                    <p class="mb-0">{{ __('custom.date') }}
                                                        : {{ custom_date($invoice->date) }}
                                                    </p>
                                                    <p class="mb-0">{{ __('custom.total') }}: {{
                                                    currencySymbol().make2decimal($invoice->total) }}</p>
                                                    <p class="mb-0">{{ __('custom.status') }}: {{
                                                    \App\Models\Invoice::INVOICE_ALL_STATUS[$invoice->status] }}</p>
                                                </address>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div>
                                <div class="p-2">
                                    <h3 class="font-16"><strong>{{ __('custom.summary') }}</strong></h3>
                                </div>
                                <div class="">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <td><strong>{{ __('custom.sku') }}</strong></td>
                                                <td><strong>{{ __('custom.name') }}</strong></td>
                                                <td><strong>{{ __('custom.quantity') }}</strong></td>
                                                <td>
                                                    <strong>{{ __('custom.price') }}</strong>
                                                </td>
                                                <td><strong>{{ __('custom.discount') }}</strong>
                                                <td><strong>{{ __('custom.sub_total') }}</strong></td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if ($invoice->items)
                                                @foreach ($invoice->items as $item)
                                                    <tr>
                                                        <td>{{ $item->sku ?? '' }}</td>
                                                        <td width="40%">
                                                            {{ $item->product_name ?? '' }}
                                                            @if($item->product->is_variant == 1 && isset($item->productStock))
                                                                ({{optional(optional($item->productStock)->attribute)->name ?? ''}} : {{optional(optional($item->productStock)->attributeItem)->name ?? ''}})
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->quantity ?? '' }}</td>
                                                        <td>{{ currencySymbol().make2decimal($item->price) ?? '' }}</td>
                                                        <td>{{ $item->discount ?? 0 }} @if($item->discount_type ==
                                                    \App\Models\Invoice::DISCOUNT_PERCENT)
                                                                %
                                                            @endif</td>
                                                        <td>{{ currencySymbol().make2decimal($item->sub_total) ?? '' }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td class="thick-line"></td>
                                                <td class="thick-line"></td>
                                                <td class="thick-line"></td>
                                                <td class="thick-line"></td>
                                                <td class="thick-line">
                                                    <strong>{{ __('custom.discount') }}</strong>
                                                </td>
                                                <td class="thick-line">{{
                                                    currencySymbol().make2decimal($invoice->discount_amount) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line">
                                                    <strong>{{ __('custom.tax') }}</strong>
                                                </td>
                                                <td class="no-line">{{
                                                    currencySymbol().make2decimal($invoice->tax_amount) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line">
                                                    <strong>{{ __('custom.total') }}</strong>
                                                </td>
                                                <td class="no-line">
                                                    <p class="mb-0">
                                                        <b>{{ currencySymbol().make2decimal($invoice->total) }}</b></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line">
                                                    <strong>{{ __('custom.total_paid') }}</strong>
                                                </td>
                                                <td class="no-line">
                                                    <p class="mb-0"><b>{{
                                                        currencySymbol().make2decimal($invoice->total_paid) }}</b></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line">
                                                    <strong>{{ __('custom.total_due') }}</strong>
                                                </td>
                                                <td class="no-line">
                                                    <p class="mb-0"><b>{{
                                                        currencySymbol().make2decimal(calculateDue($invoice->total,
                                                        $invoice->total_paid)) }}</b></p>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-print-none">
                                        <div class="float-right">

                                            <a href="#" data-div-name="print-invoice"
                                               class="btn btn-info waves-effect waves-light section-print-btn"><i
                                                    class="fa fa-print"></i>
                                                <span>{{ __('custom.print') }}</span></a>
                                        </div>

                                        @if ($invoice->status == 'pending')

                                            @if($invoice->payment_type == 'online')

                                                <div class="row ">
                                                    <div class="col-lg-6 col-xl-5 col-md-8">
                                                        <div class="row p-1 border mt-5 mt-md-0">
                                                            <div class="col-lg-12">
                                                                <h5 class="mb-3">{{ __('custom.use_payment_method') }} -
                                                                    {{__('custom.amount')}}: {{
                                                        currencySymbol()}}{{$invoice->last_paid}}</h5>
                                                            </div>
                                                            <div class="col-lg-7 col-sm-7">
                                                                <div id="paypal-button-container"></div>
                                                            </div>
                                                            <div class="col-lg-5 col-sm-5">
                                                                <form action="{{ route('invoices.pay_stripe') }}"
                                                                      method="POST">
                                                                    @csrf
                                                                    <input name="invoice_id" type="hidden"
                                                                           value="{{ $invoice->id }}"/>
                                                                    <button class="btn btn-warning" type="submit"
                                                                            id="checkout-button">{{__('custom.pay_with')}}
                                                                        <img
                                                                            class="ic-stripe-logo"
                                                                            src="{{ static_asset('admin/images/stripe.png') }}"
                                                                            class="ic-paypal" alt="images"/></button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endif

                                        @endif

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div> <!-- end row -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.clientId') }}">
    </script>

    <script type="text/javascript">
        !(function ($) {
            "use strict";
            paypal.Buttons({
                createOrder: function (data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '{{ convertAmountWithCurrencyConverter($invoice->last_paid) }}'
                            }
                        }]
                    });
                },
                onApprove: function (data, actions) {
                    return actions.order.capture().then(function (details) {
                        window.location.href = '/payment/paypal/success?invoice_id={{$invoice->id}}&order_id=' + details.id;
                    });
                }
            }).render('#paypal-button-container');

        })(jQuery);
    </script>
@endpush
