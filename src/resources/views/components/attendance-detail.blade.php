<table class="attendance-detail-table">
    <tr>
        <th>名前</th>
        <td>
            <div class="detail-grid">
                <span>{{ $stampCorrection->user->name }}</span>
            </div>
        </td>
    </tr>
    <tr>
        <th>日付</th>
        <td>
            <div class="detail-grid">

                <span>{{ optional($stampCorrection?->attendance->work_date)->format('Y年') }}</span>
                <span></span>
                <span>{{ optional($stampCorrection?->attendance->work_date)->format('n月j日')  }}</span>
            </div>
        </td>
    </tr>
    <tr>
        <th>出勤・退勤</th>
        <td>
            <div class="detail-grid">
                <span>
                    {{ optional($stampCorrection?->new_clock_in_at)->format('H:i')}}
                </span>
                <span>
                    〜
                </span>
                <span>
                    {{ optional($stampCorrection?->new_clock_out_at)->format('H:i')}}
                </span>
            </div>
        </td>
    </tr>
    @foreach($stampCorrection->stampCorrectionRests as $rest)
        <tr>
            <th>休憩 {{ $stampCorrection->stampCorrectionRests->count() > 1 ? $loop->iteration : ''}}</th>
            <td>
                <div class="detail-grid">
                    <span>
                        {{ optional($rest?->rest_start_at)->format('H:i') }}
                    </span>
                    <span>
                        〜
                    </span>
                    <span>
                        {{ optional($rest?->rest_end_at)->format('H:i') }}
                    </span>
                </div>
            </td>
        </tr>
    @endforeach
    <tr>
        <th>備考</th>
        <td>
            <div class="detail-grid">
                <span>{{ $stampCorrection->note}}</span>
            </div>
        </td>
    </tr>
</table>