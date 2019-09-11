<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Stock | @yield('title')  </title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('lib/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/css/skin-blue.min.css') }}">
    @yield('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    @include('layouts.header')
    @include('layouts.aside')
    <div class="content-wrapper">
        @yield('content')
    </div>
</div>


<script src="{{ asset('lib/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('lib/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('lib/js/adminlte.min.js') }}"></script>
<script src="{{ asset('lib/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('lib/js/bootbox.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.js"></script>
<script src="{{ asset('js/main.js') }}"></script>
@yield('scripts')
</body>
</html>
