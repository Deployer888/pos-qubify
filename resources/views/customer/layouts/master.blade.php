<!DOCTYPE html>
<html lang="en">

@if (session()->get('layout'))

<head>
    @include('customer.layouts.partials2._head')
</head>

<body class="ic-layout2-body">
    @include('customer.layouts.partials2._header')
    <div id="wrapper" class="wrapper">
        <div class="container-fluid" id="app">
            @include('flash::message')
            @yield('content')
        </div>
        @include('customer.layouts.modals.invoice_payment')
        @include('customer.layouts.partials._footer')
        @include('customer.layouts.partials2._footer-script')
        @if (config('app_key') &&
        config('app_secret') &&
        config('app_id') && config('app_cluster'))
       @include('customer.layouts.partials._pusher')
   @endif
    </div>
</body>
@else

<head>
    @include('customer.layouts.partials._head')
</head>

<body>
    <div id="wrapper">
        @include('customer.layouts.partials._header')
        @include('customer.layouts.partials._sidebar')
        <div class="content-page">
            <div class="content">
                <div class="container-fluid" id="app">
                    @include('flash::message')
                    @yield('content')
                </div>
            </div>
            @include('customer.layouts.modals.invoice_payment')
        </div>
        @include('customer.layouts.partials._footer')
        @include('customer.layouts.partials._footer-script')
        @if (config('app_key') &&
        config('app_secret') &&
        config('app_id') && config('app_cluster'))
       @include('customer.layouts.partials._pusher')
   @endif
    </div>
</body>
@endif

</html>
