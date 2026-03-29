<td>
    {{ optional($attendance?->clock_in_at)->format('H:i') }}
</td>
<td>
    {{ optional($attendance?->clock_out_at)->format('H:i') }}
</td>
<td>
    {{ $attendance?->total_rest_time }}
</td>
<td>
    @if($attendance?->clock_out_at)
        {{ $attendance?->work_time }}
    @endif
</td>
<td>
    @if($attendance)
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.attendance', $attendance->id) }}">詳細</a>
        @else
            <a href="{{ route('attendance.detail', $attendance->id) }}">詳細</a>
        @endif
    @else
        <span>詳細</span>
    @endif
</td>