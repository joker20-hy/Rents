<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Rent') }}</title>

  <!-- Scripts -->
  <!-- Styles -->
  <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
  @include('layouts.header')
  @yield('content')
</body>
</html>
