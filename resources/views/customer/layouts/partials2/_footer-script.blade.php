<script> window._asset = '{{ static_asset() }}'; </script>
<!-- App's Basic Js  -->
<script src="{{ static_asset('admin/js/jquery.min.js?v='.config('app.version')) }}"></script>
<script src="{{ static_asset('admin/js/bootstrap.bundle.min.js?v='.config('app.version')) }}"></script>
<script src="{{ static_asset('admin/js/metisMenu.min.js?v='.config('app.version')) }}"></script>
<script src="{{ static_asset('admin/js/jquery.slimscroll.js?v='.config('app.version')) }}"></script>
<script src="{{ static_asset('admin/js/waves.min.js?v='.config('app.version')) }}"></script>
<!-- App js-->
<script src="{{ static_asset('admin/js/app.js?v='.config('app.version')) }}"></script>
<script src="{{ static_asset('admin/js/layout-app.js?v='.config('app.version')) }}"></script>
<!-- Sweet-Alert  -->
<script src="{{ static_asset('admin/plugins/sweet-alert2/sweetalert2.min.js?v='.config('app.version')) }}"></script>
<script src="{{ static_asset('admin/pages/sweet-alert.init.js?v='.config('app.version')) }}"></script>
<!-- Required datatable js -->
<script src="{{ static_asset('admin/plugins/datatables/jquery.dataTables.min.js?v='.config('app.version')) }}"></script>
<script src="{{ static_asset('admin/plugins/datatables/dataTables.bootstrap4.min.js?v='.config('app.version')) }}"></script>
<!-- Responsive examples -->
<script src="{{ static_asset('admin/plugins/datatables/dataTables.responsive.min.js?v='.config('app.version')) }}"></script>
<script src="{{ static_asset('admin/plugins/datatables/responsive.bootstrap4.min.js?v='.config('app.version')) }}"></script>
<!-- intlTelInput  -->
<script src="{{ static_asset('plugins/intl/build/js/intlTelInput-jquery.min.js?v='.config('app.version')) }}"></script>
<!-- Select2 -->
<script src="{{ static_asset('admin/plugins/select2/js/select2.min.js?v='.config('app.version')) }}"></script>
<!-- Datepicker  -->
<script src="{{ static_asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js?v='.config('app.version')) }}"></script>
<!-- Chart  -->
<script src="{{ static_asset('admin/plugins/chartjs/Chart.js?v='.config('app.version')) }}"></script>
<script src="{{ static_asset('admin/plugins/chartist/js/chartist.min.js?v='.config('app.version')) }}"></script>
<script src="{{ static_asset('admin/plugins/chartist/js/chartist-plugin-tooltip.min.js?v='.config('app.version')) }}"></script>
<!-- peity JS -->
<script src="{{ static_asset('admin/plugins/peity-chart/jquery.peity.min.js?v='.config('app.version')) }}"></script>
<!-- slick  -->
<script src="{{ static_asset('admin/js/slick.min.js?v='.config('app.version')) }}"></script>
<!-- barcode  -->
<script src="{{ static_asset('plugins/jsbarcode/jsbarcode.js?v='.config('app.version')) }}"></script>

<!-- Load language  -->
<script type="text/javascript">
    !(function($) {
        "use Strict"
        window._locale = '{{ app()->getLocale() }}';
        window._translations = {!!cache('translations') !!};
    })(jQuery)
</script>

<!-- Vue -->
<script src="{{ mix('js/app.js') }}"></script>

<!-- Load script form view  -->
@stack('script')

<!-- Custom js  -->
<script src="{{ static_asset('admin') . '/js/custom.js?v='.config('app.version') }}"></script>
<script src="{{ static_asset('admin') . '/js/custom-dev.js?v='.config('app.version') }}"></script>


