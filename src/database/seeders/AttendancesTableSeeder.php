<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Attendance;
use App\Models\Rest;
use App\Enums\AttendanceStatus;

class AttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 作成期間:先月〜今月の今日まで
        // ① 先月
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // ② 今月（昨日まで）
        $thisMonthStart = Carbon::now()->startOfMonth();
        $yesterday = Carbon::now()->subDay();

        // 期間まとめる
        $periods = [
            CarbonPeriod::create($lastMonthStart, $lastMonthEnd),
            CarbonPeriod::create($thisMonthStart, $yesterday),
        ];

        // データ作成
        foreach ($periods as $period) {
            foreach ($period as $date) {
                $this->createDataInfo($date, 1); // USER1
                $this->createDataInfo($date, 2); // USER2
            }
        }
    }

    private function createDataInfo($date, $userId)
    {
        // 平日のみ（月〜金）
        if ($date->isWeekday()) {
            $attendance = Attendance::create([
                'user_id' => $userId,
                'work_date' => $date->format('Y-m-d'),
                'clock_in_at' => $date->copy()->setTime(9, 0),
                'clock_out_at' => $date->copy()->setTime(18, 0),
                'attendance_status' => AttendanceStatus::AFTER_WORK,
                'rest_minutes' => 60,
                'work_minutes' => 480,
            ]);

            Rest::create(
                [
                    'attendance_id' => $attendance->id,
                    'rest_start_at' => $date->copy()->setTime(12, 0),
                    'rest_end_at' => $date->copy()->setTime(13, 0),
                ]
            );
        }
    }
}
