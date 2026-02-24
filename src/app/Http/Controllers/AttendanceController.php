<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Rest;


class AttendanceController extends Controller
{
    public function index()
    {
        // attendancesテーブルからloginユーザーの当日の最新1件のレコードを取得
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('work_date', today())
            ->first();

        // 1レコードもない場合・・・勤務外（出勤前）
        // レコードがあった場合・・・最終の状態に応じて画面表示を変更
        $status = $attendance?->attendance_status ?? AttendanceStatus::BEFORE_WORK;
        return view('/attendance/index', compact('status'));
    }

    public function clockIn()
    {
        Attendance::create([
            'user_id' => auth()->id(),
            'work_date' => now(),
            'clock_in_at' => now(),
            'attendance_status' => AttendanceStatus::WORKING,
        ]);

        return redirect('/attendance');
    }

    public function breakStart(Request $request)
    {
        $attendance = $this->todayAttendance();

        if ($attendance->attendance_status !== AttendanceStatus::WORKING) {
            abort(403);
        }

        $attendance->update([
            'updated_at' => now(),
            'attendance_status' => AttendanceStatus::BREAKING,
        ]);

        Rest::create([
            'attendance_id' => $attendance->id,
            'break_start_at' => now(),
        ]);
        return redirect('/attendance');
    }

    public function breakEnd()
    {
        $attendance = $this->todayAttendance();

        if ($attendance->attendance_status !== AttendanceStatus::BREAKING) {
            abort(403);
        }

        $attendance->update([
            'updated_at' => now(),
            'attendance_status' => AttendanceStatus::WORKING,
        ]);

        $rest = $this->todayRest($attendance->id);
        $rest->update([
            'updated_at' => now(),
            'break_end_at' => now(),
        ]);

        return redirect('/attendance');
    }

    public function clockOut()
    {
        $attendance = $this->todayAttendance();

        if ($attendance->attendance_status !== AttendanceStatus::WORKING) {
            abort(403);
        }

        $attendance->update([
            'updated_at' => now(),
            'clock_out_at' => now(),
            'attendance_status' => AttendanceStatus::AFTER_WORK,
        ]);

        return redirect('/attendance');
    }

    private function todayAttendance(): Attendance
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('work_date', today())
            ->first();

        if (!$attendance) {
            abort(404, '本日の勤怠データが存在しません');
        }

        return $attendance;
    }

    private function todayRest($attendance_id): Rest
    {
        $attendance = Rest::where('attendance_id', $attendance_id)
            ->latest('break_start_at')
            ->first();

        if (!$attendance) {
            abort(404, '本日の勤怠データが存在しません');
        }

        return $attendance;
    }
}
