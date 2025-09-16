
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="{{ static_asset('admin/') }}/js/jquery.min.js"></script>
<script src="{{ static_asset('admin/') }}/js/bootstrap.bundle.min.js"></script>
<script src="{{ static_asset('admin/') }}/js/slick.min.js"></script>
<!-- Extra Plugin CSS -->
<script src="{{ static_asset('admin/') }}/js/login-slider.js"></script>
@if(\Config::get('app.demo_mode'))
    <script>
        !(function($) {
            "use strict";
            $(".btn-oneclick-login").on("click", function () {
                var type = $(this).attr('data-value');
                if(type == 'admin'){
                    $("#email").val("admin@app.com");
                    $("#password").val("12345678");
                    $('#loginForm').submit();
                }else{
                    $("#email").val("customer@app.com");
                    $("#password").val("12345678");
                    $('#loginForm').submit();
                }

            });
        })(jQuery);
    </script>
@endif
