@extends('layouts/app')

@section('css')
    <link rel="stylesheet" href="{{asset('css/auth/register.css')}}">
@endsection

@section('content')
    <div class="register-form">
        <h2 class="content__heading">会員登録</h2>
        <div class="register-form__inner">
            <form action="/register" method="post" class="register-form__form">
                @csrf
                <div class="register-form__group">
                    <label class="register-form__label label" for="name">名前</label>
                    <input class="register-form__input" type="text" name="name" id="name">
                    @error('name')
                        <p class="error-message">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div class="register-form__group">
                    <label class="register-form__label label" for="email">メールアドレス</label>
                    <input class="register-form__input" type="email" name="email" id="email">
                    @error('email')
                        <p class="error-message">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div class="register-form__group">
                    <label class="register-form__label label" for="password">パスワード</label>
                    <input class="register-form__input" type="password" name="password" id="password">
                    @error('password')
                        <p class="error-message">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div class="register-form__group">
                    <label class="register-form__label label" for="password_confirmation">パスワード確認</label>
                    <input class="register-form__input" type="password" name="password_confirmation"
                        id="password_confirmation">
                    @error('password_confirmation')
                        <p class="error-message">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <input class="register-form__btn btn" type="submit" value="登録する">
            </form>
        </div>
    </div>
    <div class="content__link">
        <a href="/login">ログインはこちら</a>
    </div>
@endsection