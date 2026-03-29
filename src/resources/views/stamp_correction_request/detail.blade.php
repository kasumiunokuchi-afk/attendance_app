@extends('layouts/app')

@section('css')
    <link rel="stylesheet" href="{{asset('css/attendance/detail.css')}}">
@endsection

@section('header')
    @include('partials.header')
@endsection
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.approve-btn').forEach(button => {
                button.addEventListener('click', async () => {
                    const id = button.dataset.id;
                    const response = await fetch(`/stamp_correction_request/approve/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    });

                    if (response.ok) {
                        button.textContent = '承認済み';
                        button.disabled = true;
                    }
                });
            });
        });
    </script>

@endsection

@section('content')
    <div class="page">
        <h1 class="content__title">勤怠詳細</h1>
        <div class="attendance-detail">
            <x-attendance-detail :stampCorrection="$stampCorrection"></x-attendance-detail>
            <div class="detail__footer">
                @if($stampCorrection->request_status == App\Enums\RequestStatus::PENDING)
                    <button class="approve-btn btn" data-id="{{ $stampCorrection->id }}">
                        承認
                    </button>
                @else
                    <button class="approve-btn btn" disabled data-id="{{ $stampCorrection->id }}">
                        承認済み
                    </button>
                @endif
            </div>
        </div>
    </div>
@endsection