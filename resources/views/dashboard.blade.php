<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.Laravel = { csrfToken: '{{ csrf_token() }}'}; </script>

    <title>{{ config('app.name', 'MandeCMS') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- <script src="{{ asset('js/mdb/mdb.min.js') }}" defer></script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('css/mdb/mdb.min.css') }}" rel="stylesheet"> -->
</head>
<body>
    <div id="app"></div>
</body>
</html>

<?php

      /*
       Encryption keys generated successfully.
        Personal access client created successfully.
        Client ID: 3
        Client secret: YEonT5GXLrhHUepQPDmoMt0yOm5jZEFmcEcCWSeH
        Password grant client created successfully.
        Client ID: 4
        Client secret: HzZZM0kUwFTOriLGMAxRRpWEhnMCkq8DiW1VQfTj
*/
?>