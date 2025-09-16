<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ __('custom.pdf') }}</title>
    <link href="{{ static_asset('admin/css/pdf-style.css') }}" rel="stylesheet" type="text/css" />
    @stack('style')
</head>

<body>
    @yield('content')

    @stack('script')
</body>

</html>
