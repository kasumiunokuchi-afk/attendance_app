@extends('layouts/app')

@section('css')
    <link rel="stylesheet" href="{{asset('css/auth/login.css')}}">
@endsection

@php
    $type = $type ?? 'user';
@endphp

@section('content')
    <div class="login-form">
        <h1 class="content__heading">
            {{ $type === 'admin' ? '管理者ログイン' : 'ログイン' }}
        </h1>
        <div class="login-form__inner">
            <form method="POST" action="/login" class="login-form__form">
                @csrf
                <div class="login-form__group">
                    <label class="login-form__label label" for="email">メールアドレス</label>
                    <input class="login-form__input" type="email" name="email" id="email">
                    @error('email')
                        <p class="error-message">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div class="login-form__group">
                    <label class="login-form__label label" for="password">パスワード</label>
                    <input class="login-form__input" type="password" name="password" id="password">
                    @error('password')
                        <p class="error-message">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <input class="login-form__btn btn" type="submit" value="{{ $type === 'admin' ? '管理者' : '' }}ログインする">
                @if ($type === 'admin')
                    <input type="hidden" name="is_admin" value="1">
                @endif

            </form>
        </div>
    </div>
    @if($type === 'user')
        <div class="content__link">
            <a href="/register">会員登録はこちら</a>
        </div>
    @endif

@endsection