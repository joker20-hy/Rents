@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('.dev/css/login.css') }}">
@endsection

@section('content')
<div class="box">
    <h1 class="text-primary text-center">Rent</h1>
    <br>
    <form method="POST" action="{{ route('login') }}" class="main-form">
        @csrf
        <div class="form-row">
            @error('email')
                <div class="error-feedback" role="alert">{{ $message }}</div>
            @enderror
            <input type="email" name="email" class="input @error('email') error @enderror" placeholder="Emai Address" value="{{ old('email') }}" aria-label="Email Address" autocomplete="email" autofocus required>
        </div>
        <div class="form-row">
            @error('password')
                <div class="error-feedback" role="alert">{{ $message }}</div>
            @enderror
            <input type="password" name="password" class="input @error('password') error @enderror" placeholder="Password" value="{{ old('password') }}" aria-label="Password" required>
        </div>
        <div class="d-flex align-items-center pl-2 text-primary">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>&nbsp;Remember me
        </div>
        <div style="display: flex;align-items: center;padding-top: 20px">
            <button class="w-100 btn ml-auto">Đăng nhập</button>
        </div>
    </form>
    <br>
    <hr>
    <div class="text-center text-muted d-flex align-items-center justify-content-center">
        <i class="far fa-copyright"></i> &nbsp;Rent by joker20 2020
    </div>
</div>
<script>
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('focus', function () {
            this.parentElement.classList.add('active')
        })
        input.addEventListener('blur', function () {
            this.parentElement.classList.remove('active')
        })
    })
</script>
@endsection
