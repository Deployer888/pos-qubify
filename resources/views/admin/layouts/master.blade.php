<!DOCTYPE html>
<html lang="en">

@if (session()->get('layout'))

<head>
    @include('admin.layouts.partials2._head')
</head>

<body class="ic-layout2-body">
    @include('admin.layouts.partials2._header')
    <div id="wrapper" class="wrapper">
        <div class="container-fluid" id="app">
            @include('flash::message')
            @yield('content')
        </div>
        @include('admin.layouts.modals.invoice_payment')
{{--        @include('admin.layouts.modals.stock_update')--}}
        @include('admin.layouts.partials._footer')
        @include('admin.layouts.partials2._footer-script')
        @if (config('app_key') &&
        config('app_secret') &&
        config('app_id') && config('app_cluster'))
       @include('admin.layouts.partials._pusher')
   @endif
    </div>
</body>
@else

<head>
    @include('admin.layouts.partials._head')
</head>

<body>
    <div id="wrapper">
        @include('admin.layouts.partials._header')
        @include('admin.layouts.partials._sidebar')
        <div class="content-page">
            <div class="content">
                <div class="container-fluid" id="app">
                    @include('flash::message')
                    @yield('content')
                </div>
            </div>
            @include('admin.layouts.modals.invoice_payment')
        </div>
        @include('admin.layouts.partials._footer')
        @include('admin.layouts.partials._footer-script')
        @if (config('app_key') &&
        config('app_secret') &&
        config('app_id') && config('app_cluster'))
       @include('admin.layouts.partials._pusher')
   @endif

    </div>
</body>
@endif

</html>
