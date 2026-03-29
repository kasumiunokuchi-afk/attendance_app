@extends('layouts/app')

@section('css')
    <link rel="stylesheet" href="{{asset('css/attendance/list.css')}}">
@endsection

@section('header')
    @include('partials.header')
@endsection
@php
    $prev = $currentDate->copy()->subMonth()->format('Y-m');
    $next = $currentDate->copy()->addMonth()->format('Y-m');
@endphp

@section('content')
    <div class="page">
        <h1 class="content__title">{{ $show_user }}さんの勤怠</h1>
        <div class="flex justify-between attendance-list__bar">
            <a href="{{ route('admin.attendance.staff', ['id' => $id, 'date' => $prev]) }}">
                ← 前月
            </a>
            <span>
                {{ $currentDate->format('Y年n月') }}
            </span>
            <a href="{{ route('admin.attendance.staff', ['id' => $id, 'date' => $next]) }}">
                翌月 →
            </a>
        </div>

        <div class="attendance-list attendance-list__bar">
            <x-attendance-date-table :attendances="$attendances" :dates="$dates" />
        </div>
        <!-- TODO : csv出力 -->
    </div>
@endsection