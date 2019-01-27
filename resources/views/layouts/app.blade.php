<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @stack('styles');
    @stack('initialScripts');

    <style>
        html {
            font-size: 14px;
        }
        body {
            padding-bottom: 100px;
        }
        .card {
            margin-bottom: 10px;
        }
        .level {
            display: flex;
            align-items: baseline;
        }
        .flex {
            flex: 1;
        }
        .reply-buttons button{
            margin-right: 10px;
        }
        .ml-a{
            margin-left: auto;
        }
        [v-cloak] {
            display: none;
        }
    </style>

    <script>
        window.App = {!!json_encode([
                'signedIn' => \Auth::check(),
                'isAdmin' => \Auth::check() && \Auth::user()->is_admin
            ])!!};
    </script>
</head>
<body>
    <div id="app">
        @include('layouts.nav')

        <main class="py-4">
            @yield('content')
        </main>
        <flash message="{{ session('flash') }}"></flash>
    </div>

    @stack('scripts');
</body>
</html>
