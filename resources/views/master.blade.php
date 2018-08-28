<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/favicon.png">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@section('title') BeArteSpace @show</title>

    {{--<link href="https://fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet">--}}

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('css/icons.css') }}" rel="stylesheet">

    <script>

        window.cfg = {
            'currency': '{{ session('currency') }}',
            'locale': '{{ session('locale') }}',

        };

        window.bus = {
            'alert': {!! json_encode(session('alert')?? '') !!},
            'errors': {!! json_encode($errors->all()) !!},
            'notify': {!! json_encode(session('notify')) !!}

        };

        window.trans = {!! $translations !!};

        window.status = {!! json_encode(session('status') ?? '') !!};

        window.error = {!! json_encode(session('error') ?? '') !!};

        window.cart = {!! json_encode(session( 'cart' ) ?? '') !!};

        window.favourites = '';

    </script>


</head>
<body class="@if(is_rtl()) rtl @endif">

<el-col id="app" class="app">

    <el-container>

        <el-header height="auto" class="app-header">
            @yield('header')
        </el-header>

        <el-container class="app-content">
            @yield('content')
        </el-container>

        <el-footer height="auto" class="app-footer">
            @yield('footer')
        </el-footer>

    </el-container>

</el-col>

@yield('script')

<script src="{{ mix('js/app.js') }}"></script>

</body>
</html>