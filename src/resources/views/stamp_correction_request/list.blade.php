@extends('layouts/app')

@section('css')
    <link rel="stylesheet" href="{{asset('css/stamp_correction/list.css')}}">
@endsection

@section('header')
    @include('partials.header')
@endsection

@section('content')
    <div class="page">
        <h1 class="content__title">申請一覧</h1>
        <div class="tabs">
            <a href="{{ route('stamp_correction_requests.index', ['status' => 1]) }}"
                class="{{ $status != 2 ? 'active' : '' }}">
                承認待ち
            </a>

            <a href="{{ route('stamp_correction_requests.index', ['status' => 2]) }}"
                class="{{ $status == 2 ? 'active' : '' }}">
                承認済み
            </a>
        </div>

        <div class="request-list request-list__bar">
            <div class="request-table-wrapper">
                <table class="request-table">
                    <thead>
                        <tr>
                            <th>状態</th>
                            <th>名前</th>
                            <th>対象日時</th>
                            <th>申請理由</th>
                            <th>申請日時</th>
                            <th>詳細</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                            <tr>
                                <td>{{ $request->request_status->label() }}</td>
                                <td>{{ $request->user->name }}</td>
                                <td>
                                    {{ optional($request->attendance->work_date)->format('Y/m/d') }}
                                </td>
                                <td>
                                    {{ $request->note }}
                                </td>
                                <td>
                                    {{ optional($request->request_date)->format('Y/m/d') }}
                                </td>
                                <td>
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('stamp_correction_requests.approve', $request->id) }}">詳細</a>
                                    @else
                                        <a href="{{ route('attendance.detail', $request->attendance->id) }}">詳細</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection