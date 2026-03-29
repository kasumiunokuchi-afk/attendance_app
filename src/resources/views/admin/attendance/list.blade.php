@extends('layouts/app')

@section('css')
    <link rel="stylesheet" href="{{asset('css/attendance/list.css')}}">
@endsection

@section('header')
    @include('partials.header')
@endsection
@php
    $yesterday = $currentDate->copy()->subDay()->format('Y-m-d');
    $tomorrow = $currentDate->copy()->addDay()->format('Y-m-d');
@endphp

@section('content')
    <div class="page">
        <h1 class="content__title">{{ $currentDate->format('Y年n月j日') }}の勤怠</h1>
        <div class="flex justify-between attendance-list__bar">
            <a href="{{ route('admin.attendance.list', ['date' => $yesterday]) }}">
                ← 前日
            </a>
            <span>
                <svg width="20" height="20" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M19 4h-1V2h-2v2H8V2H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2m0 16H5V9h14z" />
                </svg>
                {{ $currentDate->format('Y/m/d') }}
            </span>
            <a href="{{ route('admin.attendance.list', ['date' => $tomorrow]) }}">
                翌日 →
            </a>
        </div>
        <div class="attendance-list">
            <x-attendance-users-table :attendances="$attendances" />
        </div>
    </div>
@endsection