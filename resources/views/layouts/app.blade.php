<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- PWA  -->
    {{--<meta name="theme-color" content="#6777ef"/>
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">--}}
    @include('layouts.partials.head')
</head>
<body class="loading @guest authentication-bg @endguest"
      data-layout-config='{"leftSideBarTheme":"light","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
<!-- Pre-loader -->
<div id="preloader">
    <div id="status">
        <div class="bouncing-loader">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
</div>
<!-- End Preloader-->
@php
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);
$tmp = ($uri_segments[1]=='admin')?'admin':'web';
$tmpNav = ($uri_segments[1]=='admin')?'layouts.partials.admin-nav':'layouts.partials.nav';
@endphp

@auth($tmp)
    <div class="wrapper">
        @include($tmpNav)
        <div class="content-page">
            <div class="content">
                @include('layouts.partials.header')

                @yield('content')
            </div>
            @include('layouts.partials.footer')
        </div>
    </div>

    <!-- /End-bar -->
    @include('layouts.partials.footer-script')
@else

    @yield('content')

    @include('layouts.partials.footer')
    @include('layouts.partials.footer-script')
@endauth
{{--<script src="{{ asset('/sw.js') }}"></script>--}}
{{--<script>
    if (!navigator.serviceWorker.controller) {
        navigator.serviceWorker.register("/sw.js").then(function (reg) {
            // console.log("Service worker has been registered for scope: " + reg.scope);
        });
    }
</script>--}}
</body>
</html>






























