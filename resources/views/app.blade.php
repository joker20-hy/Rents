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
  <!-- Styles -->
  <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    @php
      $room_filter = [
        'price' => array_column(config('const.ROOM_FILTER.PRICE'), 'title'),
        'acreage' => array_column(config('const.ROOM_FILTER.ACREAGE'), 'title')
      ];
      $room_sort = array_column(config('const.ROOM_SORT'), 'title');
    @endphp
    <div id="app"></div>
    <!-- built files will be auto injected -->
    <script>
      const TRUE = 1;
      const FALSE = 0;
      const $config = {
        TRUE: 1,
        FALSE: 0,
        APP_NAME: `{!! env('APP_NAME') !!}`,
        USER: {!! json_encode(config('const.USER')) !!},
        ANONYMOUS: `{!! asset('images/anonymous.svg') !!}`,
        REVIEW: {!! json_encode(config('const.REVIEW')) !!},
        REVIEW: {!! json_encode(config('const.REVIEW')) !!},
        SEARCH: {!! json_encode(config('const.SEARCH')) !!},
        IMAGES: {
          MAX_SIZE: '2MB',
          DEFAULT: `{!! asset('images/default.png') !!}`
        },
        ROOM: {
          SORT: {!! json_encode($room_sort) !!},
          FILTER: {!! json_encode($room_filter) !!},
          STATUS: {!! json_encode(config('const.ROOM_STATUS')) !!}
        },
        SERVICE_TYPE: {
          PERIODIC: {value: {!! config('const.SERVICE_TYPE.PERIODIC') !!}, name: 'Định kỳ'},
          PER_UNIT: {value: {!! config('const.SERVICE_TYPE.PER_UNIT') !!}, name: 'Theo đơn vị'},
          BY_RENTERS: {value: {!! config('const.SERVICE_TYPE.BY_RENTERS') !!}, name: 'Theo người thuê trọ'}
        }
      }
    </script>
</body>
</html>
