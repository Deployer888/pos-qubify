<!-- Invoice payment add modal  -->
<div class="modal fade" id="invoicePaymentAdd" tabindex="-1" role="dialog" aria-labelledby="invoicePaymentAddTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.invoices.add_payment') }}" method="post">
                @csrf
                <input type="hidden" name="product_id" id="add-invoice-payment-invoice-id">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('custom.add_payment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="">{{ __('custom.date') }} <span class="error">*</span></label>
                            <input type="text" name="date" class="form-control datepicker-autoclose" autocomplete="off"
                                value="{{ old('date') }}" required>
                            @error('date')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="">{{ __('custom.payment_type') }} <span class="error">*</span></label>
                            <input type="text" name="payment_type" class="form-control"
                                value="{{ old('payment_type') }}" required maxlength="50">
                            @error('payment_type')
                            <p class="error">{{ $message }}</p>
                            @enderror

                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="">{{ __('custom.amount') }} <span class="error">*</span></label>
                            <input type="number" name="amount" class="form-control" value="{{ old('amount') }}"
                                required>
                            @error('amount')
                            <p class="error">{{ $message }}</p>
                            @enderror

                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="">{{ __('custom.notes') }}</label>
                            <input type="text" name="notes" class="form-control" value="{{ old('notes') }}"
                                maxlength="200">
                            @error('notes')
                            <p class="error">{{ $message }}</p>
                            @enderror

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('custom.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('custom.save_payment') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Invoice payment show modal  -->
<div class="modal fade" id="invoicePaymentView" tabindex="-1" role="dialog" aria-labelledby="invoicePaymentViewTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('custom.payment_history') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="invoice-payment-view-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('custom.date') }}</th>
                                <th scope="col">{{ __('custom.payment_type') }}</th>
                                <th scope="col">{{ __('custom.amount') }}</th>
                                <th scope="col">{{ __('custom.notes') }}</th>
                                <th scope="col" class="text-center">{{ __('custom.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invoice payment send modal  -->
<div class="modal fade" id="invoicePaymentSend" tabindex="-1" role="dialog" aria-labelledby="invoicePaymentSendTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.invoices.sendInvoice') }}" method="post">
                @csrf
                <input type="hidden" name="invoice_id" id="send-invoice-payment-invoice-id">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('custom.send_invoice') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="">{{ __('custom.email') }} <span class="error">*</span></label>
                            <input id="send-invoice-payment-email" type="email" name="email" class="form-control"
                                value="{{ old('email') }}" required maxlength="50">
                            @error('email')
                            <p class="error">{{ $message }}</p>
                            @enderror

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('custom.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('custom.send_invoice') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Invoice live url  -->
<div class="modal fade" id="liveInvoiceUrl" tabindex="-1" role="dialog" aria-labelledby="liveInvoiceUrlTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('custom.invoice_live_url') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" id="live-invoice-token" disabled>
                        <div class="ic-copy-msg text-right mt-2">
                            <span style="display: none" id="copied_msg">{{ __('custom.copied') }}</span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('custom.close') }}</button>
                <button type="button" class="btn btn-primary copy-url-btn">{{ __('custom.copy_url') }}</button>

            </div>
        </div>
    </div>
</div>





<div class="modal fade" id="stockUpdate" tabindex="-1" role="dialog" aria-labelledby="StockUpdateTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('custom.stock_update') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" id="stack-update-pupup">

                </div>
            </div>
        </div>
    </div>
</div>
