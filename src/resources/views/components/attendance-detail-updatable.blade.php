<table class="attendance-detail-table updatable">
    <tr>
        <th>名前</th>
        <td>
            <div class="detail-grid5">
                <span class="grid-column_2">{{ $attendance->user->name }}</span>
            </div>
            <input type="hidden" name="user_id" value="{{ $attendance->user->id}}" />
        </td>
    </tr>
    <tr>
        <th>日付</th>
        <td>
            <div class="detail-grid5">

                <span class="grid-column_2">{{ optional($attendance?->work_date)->format('Y年') }}</span>
                <span></span>
                <span>{{ optional($attendance?->work_date)->format('n月j日')  }}</span>
            </div>
            <input type="hidden" name="work_date" value="{{ $attendance->work_date }}" />
        </td>
    </tr>
    <tr>
        <th>出勤・退勤</th>
        <td>
            <div class="detail-grid5">
                <input type="text" value="{{ optional($attendance?->clock_in_at)->format('H:i') }}" name="clock_in_at"
                    class="grid-column_2">
                <span>
                    〜
                </span>
                <input type="text" value="{{ optional($attendance?->clock_out_at)->format('H:i') }}"
                    name="clock_out_at">
            </div>
            <div class="detail-grid5">
                @error('clock_in_at')
                    <div class="error-message column_25">{{ $message }}</div>
                @enderror
                @error('clock_out_at')
                    <div class="error-message column_25">{{ $message }}</div>
                @enderror
            </div>
        </td>
    </tr>
    @foreach($attendance->rests as $index => $rest)
        <tr>
            <th>休憩 {{ $attendance->rests->count() > 1 ? $loop->iteration : ''}}</th>
            <td>
                <div class="detail-grid5">
                    <input type="text" value="{{ optional($rest?->rest_start_at)->format('H:i') }}"
                        name="rests[{{ $index }}][start]" class="grid-column_2">
                    <span>
                        〜
                    </span>
                    <input type="text" value="{{ optional($rest?->rest_end_at)->format('H:i') }}"
                        name="rests[{{ $index }}][end]">
                </div>
                <div class="detail-grid5">
                    @error("rests.$index.start")
                        <div class="error-message column_25">{{ $message }}</div>
                    @enderror
                    @error("rests.$index.end")
                        <div class="error-message column_25">{{ $message }}</div>
                    @enderror
                </div>
            </td>
            <input type="hidden" name="rests[{{ $index }}][id]" value="{{ $rest->id }}">
        </tr>
    @endforeach

    <tr>
        @php
            $newIndex = count($attendance->rests) + 1;
        @endphp
        <th>休憩 {{ $newIndex }}</th>
        <td>
            <div class="detail-grid5">
                <input type="text" name="rests[{{ $newIndex }}][start]" value="" class="grid-column_2" />
                <span>
                    〜
                </span>
                <input type="text" name="rests[{{ $newIndex }}][end]" value="" />
            </div>
            <div class="detail-grid5">
                @error("rests.$newIndex.start")
                    <div class="error-message">{{ $message }}</div>
                @enderror
                @error("rests.$newIndex.end")
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </td>
        <input type="hidden" name="rests[{{ $newIndex }}][id]" value="">
    </tr>
    <tr>
        <th>備考</th>
        <td>
            <div class="detail-grid5">
                <textarea name="note">{{ $attendance->note}}</textarea>
            </div>
            <div class="detail-grid5">
                @error('note')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </td>
    </tr>
</table>
<input type="hidden" name="id" value="{{ $attendance->id }}" />