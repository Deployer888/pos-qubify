<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.layouts.partials._head')
</head>

<body>
    <div>
        <div class="content">
            <div class="container-fluid" id="app">
                @yield('content')
            </div>
        </div>
        @include('admin.layouts.partials._footer-script')
    </div>
</body>

</html>