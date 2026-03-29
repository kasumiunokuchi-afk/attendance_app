@extends('layouts/app')

@section('css')
    <link rel="stylesheet" href="{{asset('css/staff/list.css')}}">
@endsection

@section('header')
    @include('partials.header')
@endsection

@section('content')
    <div class="page">
        <h1 class="content__title">スタッフ一覧</h1>

        <div class="staff-list staff-list__bar">
            <div class="staff-table-wrapper">
                <table class="staff-table">
                    <thead>
                        <tr>
                            <th>名前</th>
                            <th>メールアドレス</th>
                            <th>月次勤怠</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>
                                    {{ $user->email }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.attendance.staff', $user->id) }}">詳細</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection