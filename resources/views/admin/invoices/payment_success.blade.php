@extends('admin.layouts.master-live')

@section('content')
<div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8 p-4">
        <div class="card">
            <div class="card-body">
                <div class="col-lg-12">
                    <div class="home-wrapper text-center">
                        <h3 class="m-t-30">{{ __('custom.payment_successful') }} !</h3>
                        <p class="mb-5">{{ __('custom.we_are_delighted_to_inform') }}</p>
                    </div>
                    <!-- end home wrapper -->
                </div>
            </div>
        </div>
    </div>
</div>
