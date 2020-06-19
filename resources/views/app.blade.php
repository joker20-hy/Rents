<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rent') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('.dev/js/main.js') }}"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .content-html img {
            max-width: 100%!important;
        }
    </style>
</head>
<body>
    <div id="app"></div>
    <!-- built files will be auto injected -->
    <script>
        const $config = {
            user: {!! json_encode(config('const.USER')) !!},
            TRUE: 1,
            FALSE: 0,
            MAX_SIZE_IMAGE: '2MB',
            SERVICE_TYPE: {
              PER_UNIT: {
                value: {{ config('const.SERVICE_TYPE.PER_UNIT') }},
                name: 'Theo đơn vị'
              },
              PERIODIC: {
                value: {{ config('const.SERVICE_TYPE.PERIODIC') }},
                name: 'Định kỳ'
              }
            }
        }
    </script>
</body>
</html>
