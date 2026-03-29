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
        <h1 class="content__title">勤怠一覧</h1>
        <div class="flex justify-between attendance-list__bar">

            <a href="{{ route('attendance.list', ['date' => $prev]) }}">
                ← 前月
            </a>

            <span>
                <svg width="20" height="20" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M19 4h-1V2h-2v2H8V2H6v2H5a2
                                2 0 0 0-2 2v14a2 2 0 0 0 2
                                2h14a2 2 0 0 0 2-2V6a2
                                2 0 0 0-2-2m0
                                16H5V9h14z" />
                </svg>
                {{ $currentDate->format('Y/m') }}
            </span>

            <a href="{{ route('attendance.list', ['date' => $next]) }}">
                翌月 →
            </a>
        </div>

        <div class="attendance-list">
            <x-attendance-date-table :attendances="$attendances" :dates="$dates" />
        </div>
    </div>
@endsection