@props(['attendances'])
@props(['show_date'])

<div class="attendance-table-wrapper">
    <table class="attendance-table">
        <thead>
            <tr>
                @if($show_date)
                    <th>日付</th>
                @else
                    <th>名前</th>
                @endif
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    @if($show_date)
                        <td>日付</td>
                    @else
                        <td>名前</td>
                    @endif
                    <td>出勤</td>
                    <td>退勤</td>
                    <td>休憩</td>
                    <td>合計</td>
                    <td>詳細</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>