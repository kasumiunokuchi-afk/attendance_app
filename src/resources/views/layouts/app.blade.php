<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>attendance app</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{ asset('/css/common.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('css')
    @yield('js')
</head>

<body>
    <div class="app">
        <header class="header">
            @if(auth() && auth()->user() && auth()->user()->isAdmin())
                <a href="/attendance">
                    <img src="{{ '/img/COACHTECHヘッダーロゴ.png' }}" class="header__img">
                </a>
            @else
                <a href="/admin/attendance/list">
                    <img src="{{ '/img/COACHTECHヘッダーロゴ.png' }}" class="header__img">
                </a>
            @endif
            @yield('header')
        </header>
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>

</html>