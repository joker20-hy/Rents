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
      const $room_filter = {!! json_encode($room_filter) !!}
      const $room_sort = {!! json_encode($room_sort) !!}
      clickTarget = id => document.querySelector(id).click()
      function select(arr, cols) {
        let result = []
        arr.forEach(obj => {
          let item = {}
          cols.forEach(col => item[col]=obj[col])
          result.push(item)
        })
        return result
      }
      const $config = {
        user: {!! json_encode(config('const.USER')) !!},
        TRUE: 1,
        FALSE: 0,
        MAX_SIZE_IMAGE: '2MB',
        SERVICE_TYPE: {
          PER_UNIT: {
            value: {!! config('const.SERVICE_TYPE.PER_UNIT') !!},
            name: 'Theo đơn vị'
          },
          PERIODIC: {
            value: {!! config('const.SERVICE_TYPE.PERIODIC') !!},
            name: 'Định kỳ'
          }
        },
        REVIEW: {!! json_encode(config('const.REVIEW')) !!},
        ROOM_STATUS: {!! json_encode(config('const.ROOM_STATUS')) !!}
      }
    </script>
</body>
</html>
