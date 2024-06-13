<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- <meta name="csrf-token" content="{{ csrf_token() }}"/> -->
        <title>KIOSK</title>
        
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script src="https://code.highcharts.com/stock/highstock.js"></script>

    </head>
    <body class="antialiased">
        @if (!Auth::check())
            <script>
                localStorage.removeItem('authToken');
                localStorage.removeItem('userRole');
                localStorage.removeItem('product_id');
            </script>
        @elseif(Auth::check())
            <script>
                localStorage.setItem('authToken', '{{ csrf_token() }}');
                localStorage.setItem('userRole', '{{ Auth::user()->role_id }}');
            </script>
        @endif
        <div id="app">
        </div>
        <script src="{{mix('/js/app.js')}}"></script>
    </body>
</html>
