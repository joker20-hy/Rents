@extends('frontend.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/frontend/home.css') }}">
@endsection
@section('js')

@endsection
@section('content')
@include('frontend.home.search-form')
<section class="d-flex">

</section>
<section style="background-color: #343a40;padding: 20px">
    <span class="text-light" style="font-size: 100px">Rent</span>
</section>
<section style="background-color: #212529;padding: 20px;display: flex;color: #868686">
    <span style="color: #868686">Nền tảng tìm nhà trọ, phòng trọ trên toàn quốc</span>
    <span style="margin-left: auto">
        @joker20 .
        <span style="font-weight: 600">
            Term <i class="fas fa-plus"></i> Privacy
        </span>
    </span>
</section>
<script src="{{ asset('js/frontend/home.js') }}"></script>
@endsection