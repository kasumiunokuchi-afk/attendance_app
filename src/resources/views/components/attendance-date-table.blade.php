<div class="attendance-table-wrapper">
    <table class="attendance-table">
        <thead>
            <tr>
                <th class="attendance-table__date">日付</th>
                <x-attendance-header :isShowDate />
            </tr>
        </thead>
        <tbody>
            @foreach ($dates as $date)
                @php
                    $attendance = $attendances[$date->format('Y-m-d 00:00:00')] ?? null;
                @endphp
                <tr>
                    <td>{{ $date->isoFormat('MM/DD (ddd)') }}</td>
                    <x-attendance-row :attendance="$attendance" />
                </tr>
            @endforeach
        </tbody>
    </table>
</div>