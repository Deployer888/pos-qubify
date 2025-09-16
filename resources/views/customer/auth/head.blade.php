<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="shortcut icon" href="{{ config('favicon') ?? '' }}">
<title>{{__('custom.login')}} | {{ config('site_title') ?? config('app.name') }}</title>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ static_asset('admin/') }}/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ static_asset('admin/') }}/css/icons.css">
<link rel="stylesheet" href="{{ static_asset('admin/') }}/css/slick.css">
<!-- main css -->
<link rel="stylesheet" href="{{ static_asset('admin/') }}/css/style.css">
<link rel="stylesheet" href="{{ static_asset('admin/') }}/css/custom.css">
<link rel="stylesheet" href="{{ static_asset('admin/') }}/css/login-responsive.css">

<style>
    :root {
        /*#28aaa9*/
        --primary-color: {{ config('primary_color') ?: '#28aaa9' }};
        /*#2b2d5d*/
        --secondary-color: {{ config('secondary_color') ?: '#2b2d5d' }};
    }

    @if(config('login_background'))
         .ic_main_form_area {
        background-image: url('{{config('login_background')}}');
    }

    @endif

</style>
