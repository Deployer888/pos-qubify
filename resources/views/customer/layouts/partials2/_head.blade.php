<meta charset="utf-8" />
<title>{{get_page_meta()}} {{ config('site_title') ?? config('app.name') }}</title>
<meta content="Dashboard" name="description" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!--Author-->
<meta name="author" content="ITClanBD" />
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<!-- App favicon -->
<link rel="shortcut icon" href="{{ favicon() }}">
<!-- App css -->
<link href="{{ static_asset('admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ static_asset('admin/css/metismenu.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ static_asset('admin/css/slick.css') }}" rel="stylesheet" type="text/css">
<link href="{{ static_asset('admin/css/icons.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ static_asset('admin/fonts/flaticon/font/flaticon.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ static_asset('admin/css/layout-style.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ static_asset('admin/css/style.css') }}" rel="stylesheet" type="text/css" />
<!-- Sweet Alert -->
<link href="{{ static_asset('admin/plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
<!-- DataTables -->
<link href="{{ static_asset('admin/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Responsive datatable -->
<link href="{{ static_asset('admin/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
 <!-- Datepicker  -->
<link href="{{ static_asset('admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
 <!-- Chartist  -->
<link rel="stylesheet" href="{{ static_asset('admin/plugins/chartist/css/chartist.min.css') }}">
 <!-- Select2  -->
<link href="{{ static_asset('admin/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<!-- intlTelInput css -->
<link rel="stylesheet" href="{{ static_asset('plugins/intl/build/css/intlTelInput.css') }}">
 <!-- Load style form view  -->
 <!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@stack('style')
 <!-- Custom css  -->
<link rel="stylesheet" href="{{ static_asset('admin') . '/css/custom.css' }}">
<style>
    :root {
        /*#28aaa9*/
        --primary-color: {{ config('primary_color') ?: '#28aaa9' }};
        /*#2b2d5d*/
        --secondary-color: {{ config('secondary_color') ?: '#2b2d5d' }};
    }
</style>
<!--  DOUBLE BORDER SPINNER  -->
<div class="ic-preloader">
    <div class="ic-inner-preloader">
        <div class="db-spinner"></div>
    </div>
</div>
