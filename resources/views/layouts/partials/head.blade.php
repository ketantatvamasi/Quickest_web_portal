<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>


<script src="{{ asset('js/app.js') }}"></script>


{{--<link rel="dns-prefetch" href="//fonts.gstatic.com">--}}
{{--<link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet">--}}


<link href="{{ asset('css/app.css') }}" rel="stylesheet">


<!-- App favicon -->
<link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico')}}">

<!-- App css -->
<link href="{{ asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" id="light-style" />
<link href="{{ asset('assets/css/app-dark.min.css')}}" rel="stylesheet" type="text/css" id="dark-style" />
<link href="{{ asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css"/>
<script>
    var SITEURL = "{{url('/')}}";
</script>
@stack('styles')
