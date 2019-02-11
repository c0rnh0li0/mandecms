<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/mdb/mdb.min.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mdb/mdb.min.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <passport-clients></passport-clients>
        <passport-authorized-clients></passport-authorized-clients>
        <passport-personal-access-tokens></passport-personal-access-tokens>

        @guest
        <guestnavbar app_name="{{ config('app.name', 'MandeCMS [no env title]') }}"
                     txt_register='{{ __('Register') }}'
                     txt_login="{{ __('Login') }}"
                     url_register="{{ route('register') }}"
                     url_login="{{ route('login') }}">
        </guestnavbar>
        @else
        <navbar app_name="{{ config('app.name', 'MandeCMS [no env title]') }}"
                txt_logout="{{ __('Logout') }}"
                url_logout="{{ route('logout') }}"
                csrf="{{ csrf_token() }}">
        </navbar>
        @endguest

        <router-view></router-view>
        @yield('content')
    </div>
</body>
</html>

<?php

/*
 * @yield('content')
@include('inc.navbar')
@include('inc.messages')

<main class="container">
    @yield('content')
</main>

@include('inc.scripts')
*/



?>
