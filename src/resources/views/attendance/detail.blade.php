@extends('layouts/app')

@section('css')
    <link rel="stylesheet" href="{{asset('css/attendance/detail.css')}}">
@endsection

@section('header')
    @include('partials.header')
@endsection
@php

@endphp

@section('content')
    <div class="page">
        <h1 class="content__title">勤怠詳細</h1>
        <div class="attendance-detail">
            @if($attendance->isEditable())
                <form method="POST" action="{{ $action }}" class="address-form__form">
                    @csrf
                    @if(auth()->user()->isAdmin())
                        @method('PUT')
                    @endif
                    <x-attendance-detail-updatable :attendance="$attendance"></x-attendance-detail-updatable>
                    <div class="detail__footer">
                        <input class=" btn" type="submit" value="修正">
                    </div>
                </form>
            @else
                <x-attendance-detail :stampCorrection="$stampCorrection"></x-attendance-detail>
                <div class="detail__footer">
                    <span>※承認待ちのため修正はできません</span>
                </div>
            @endif
        </div>
    </div>
@endsection