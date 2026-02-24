@extends('layouts/app')

@section('css')
    <link rel="stylesheet" href="{{asset('css/attendance/index.css')}}">
@endsection

@section('header')
    @include('partials.header')
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            function updateTime() {
                const now = new Date();

                const yyyy = now.getFullYear();
                const mm = now.getMonth() + 1;
                const dd = now.getDate();
                const hh = String(now.getHours()).padStart(2, '0');
                const mi = String(now.getMinutes()).padStart(2, '0');
                const weekday = now.toLocaleDateString('ja-JP', {
                    weekday: 'short'
                });

                document.getElementById('today').textContent = `${yyyy}年${mm}月${dd}日(${weekday})`;

                document.getElementById('current-time').textContent = `${hh}:${mi}`;
            }

            updateTime();
            setInterval(updateTime, 1000);
        });
    </script>

@endsection

@section('content')
    <div class="page">
        <div class="attendance__status">
            <p>{{ $status->label() }}</p>
        </div>
        <div class="today">
            <div id="today"></div>
        </div>
        <div class="current__time">
            <div id="current-time"></div>
        </div>
        <!-- ボタン表示 -->
        <div class="attendance__buttons">
            @if($status === \App\Enums\AttendanceStatus::BEFORE_WORK)
                <form method="POST" action="{{ route('attendance.clockIn') }}">
                    @csrf
                    <button class="btn btn-short">出勤</button>
                </form>
            @elseif($status === \App\Enums\AttendanceStatus::WORKING)
                <form method="POST" action="{{ route('attendance.clockOut') }}">
                    @csrf
                    <button class="btn btn-short">退勤</button>
                </form>
                <form method="POST" action="{{ route('attendance.breakStart') }}">
                    @csrf
                    <button class="btn btn-short btn-white">休憩入</button>
                </form>

            @elseif($status === \App\Enums\AttendanceStatus::BREAKING)
                <form method="POST" action="{{ route('attendance.breakEnd') }}">
                    @csrf
                    <button class="btn btn-short btn-white">休憩戻</button>
                </form>
            @elseif($status === \App\Enums\AttendanceStatus::AFTER_WORK)
                <p>お疲れ様でした。</p>
            @endif
        </div>

    </div>


@endsection