<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Rent') }}</title>

  <!-- Scripts -->
  <script src="{{ asset('.dev/js/frontend/main.js') }}"></script>
  <script src="{{ asset('.dev/js/frontend/suggest.js') }}"></script>
  <script src="{{ asset('.dev/js/frontend/storage.js') }}"></script>
  @yield('js')
  <!-- Styles -->
  <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
  @yield('css')
</head>
<body>
  @include('frontend.layouts.header')
  @yield('content')
  <script></script>
</body>
</html>
