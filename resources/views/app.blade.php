<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
        <title>EcoFarm</title>

        <link rel="stylesheet" type="text/css" href="{{ asset('styles/bootstrap.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('styles/style.css') }}">
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i|Source+Sans+Pro:300,300i,400,400i,600,600i,700,700i,900,900i&display=swap" rel="stylesheet">

        <!-- Styles -->
        @vite(['resources/css/app.css'])

        <link rel="stylesheet" type="text/css" href="{{asset('fonts/css/fontawesome-all.min.css')}}">
        <link rel="manifest" href="{{asset('_manifest.json')}}" data-pwa-version="set_in_manifest_and_pwa_js">
        <link rel="apple-touch-icon" sizes="180x180" href="{{asset('app/icons/icon-192x192.png')}}">

    </head>
<body class="theme-light" data-highlight="highlight-red" data-gradient="body-default">
    <div id="preloader"><div class="spinner-border color-highlight" role="status"></div></div>

    <div id="app"></div>

    @vite(['resources/js/app.js'])
{{--    так не хочет работать --}}
{{--    @vite(['resources/js/bootstrap.min.js'])--}}
{{--    @vite(['resources/js/custom.js'])--}}

{{--    так работает --}}
{{--    <script type="text/javascript" src="{{ asset('custom.js')}}"></script>--}}
{{--    <script type="text/javascript" src="{{ asset('bootstrap.min.js')}}"></script>--}}

{{--    и так работает --}}
    <script type="text/javascript" src="custom.js"></script>
    <script type="text/javascript" src="bootstrap.min.js"></script>

</body>


</html>
