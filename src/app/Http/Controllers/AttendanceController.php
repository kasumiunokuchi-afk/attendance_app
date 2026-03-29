<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\AttendanceStatus;
use App\Enums\AttendanceLogType;
use App\Enums\RequestStatus;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\StampCorrection;
use App\Models\Rest;
use App\Models\User;
use App\Http\Requests\AttendanceRequest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

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
        $attendance = Attendance::create([
            'user_id' => auth()->id(),
            'work_date' => now(),
            'clock_in_at' => now(),
            'attendance_status' => AttendanceStatus::WORKING,
        ]);

        AttendanceLog::create([
            'user_id' => auth()->id(),
            'attendance_id' => $attendance->id,
            'log_type' => AttendanceLogType::CLOCK_IN,
            'logged_at' => now()
        ]);

        return redirect('/attendance');
    }

    public function restStart(Request $request)
    {
        $attendance = $this->todayAttendance();

        if ($attendance->attendance_status !== AttendanceStatus::WORKING) {
            abort(403);
        }

        AttendanceLog::create([
            'user_id' => auth()->id(),
            'attendance_id' => $attendance->id,
            'log_type' => AttendanceLogType::REST_START,
            'logged_at' => now()
        ]);

        $attendance->update([
            'updated_at' => now(),
            'attendance_status' => AttendanceStatus::BREAKING,
        ]);

        Rest::create(
            [
                'attendance_id' => $attendance->id,
                'rest_start_at' => now()
            ]
        );

        return redirect('/attendance');
    }

    public function restEnd()
    {
        $attendance = $this->todayAttendance();

        if ($attendance->attendance_status !== AttendanceStatus::BREAKING) {
            abort(403);
        }

        AttendanceLog::create([
            'user_id' => auth()->id(),
            'attendance_id' => $attendance->id,
            'log_type' => AttendanceLogType::REST_END,
            'logged_at' => now()
        ]);

        $rest = Rest::where('attendance_id', $attendance->id)
            ->whereNull('rest_end_at')
            ->latest()
            ->first();

        $rest->update([
            'updated_at' => now(),
            'rest_end_at' => now(),
        ]);

        $attendance->update([
            'updated_at' => now(),
            'attendance_status' => AttendanceStatus::WORKING,
            'rest_minutes' => $attendance->total_rest_minutes
        ]);

        return redirect('/attendance');
    }

    public function clockOut()
    {
        $attendance = $this->todayAttendance();

        if ($attendance->attendance_status !== AttendanceStatus::WORKING) {
            abort(403);
        }

        $now = now();

        AttendanceLog::create([
            'user_id' => auth()->id(),
            'attendance_id' => $attendance->id,
            'log_type' => AttendanceLogType::CLOCK_OUT,
            'logged_at' => $now
        ]);

        $attendance->clock_out_at = $now;
        $work_time = $attendance->work_minutes;
        $rest_time = $attendance->total_rest_minutes;

        // 勤務時間/休憩時間/勤務終了時間の更新
        $attendance->update([
            'updated_at' => now(),
            'clock_out_at' => $now,
            'attendance_status' => AttendanceStatus::AFTER_WORK,
            'rest_minutes' => $rest_time,
            'work_minutes' => $work_time
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

    public function list(Request $request)
    {
        $user_id = auth()->user()->isAdmin() ? $request->id : auth()->id();

        $date = $request->date
            ? Carbon::parse($request->date)
            : Carbon::now();

        $listData = $this->getMonthAttendances($user_id, $date);
        return view(
            'attendance.list',
            [
                'attendances' => $listData['attendances'],
                'dates' => $listData['dates'],
                'currentDate' => $date
            ]
        );
    }

    public function detail(Request $request)
    {
        $attendance = Attendance::where('id', $request->id)
            ->first();

        $stampCorrection = null;
        if (!$attendance->isEditable()) {
            $stampCorrection = StampCorrection::where('attendance_id', $attendance->id)
                ->where('request_status', RequestStatus::PENDING)
                ->latest()
                ->first();
        }
        $action = auth()->user()->isAdmin()
            ? route('admin.attendance.update', $attendance->id)
            : route('attendance.update', $attendance->id);

        return view('attendance.detail', compact('attendance', 'stampCorrection', 'action'));
    }

    public function adminList(Request $request)
    {
        $date = $request->date
            ? Carbon::parse($request->date)
            : Carbon::now();

        // 今日の勤怠
        $attendances = Attendance::where('work_date', $date)
            ->get();

        return view(
            'admin.attendance.list',
            [
                'attendances' => $attendances,
                'currentDate' => $date,
                'show_date' => true
            ]
        );
    }

    public function update(AttendanceRequest $request)
    {
        $attendance = Attendance::findOrFail($request->id);
        $new_clock_in_at = Carbon::parse($request->work_date)
            ->setTimeFromTimeString($request->clock_in_at);
        $new_clock_out_at = Carbon::parse($request->work_date)
            ->setTimeFromTimeString($request->clock_out_at);

        foreach ($request->rests as $rest) {
            // 空行スキップ
            if (empty($rest['start']) && empty($rest['end'])) {
                continue;
            }

            $rest_start_at = Carbon::parse($request->work_date)
                ->setTimeFromTimeString($rest['start']);
            $rest_end_at = Carbon::parse($request->work_date)
                ->setTimeFromTimeString($rest['end']);

            if ($rest['id'] == "") {
                // 行追加
                Rest::create(
                    [
                        'attendance_id' => $attendance->id,
                        'rest_start_at' => $rest_start_at,
                        'rest_end_at' => $rest_end_at
                    ]
                );
            } else {
                $rest = Rest::findOrFail($rest['id']);
                $rest->update([
                    'updated_at' => now(),
                    'rest_start_at' => $rest_start_at,
                    'rest_end_at' => $rest_end_at
                ]);
            }
        }

        $work_time = $attendance->work_minutes;
        $rest_time = $attendance->total_rest_minutes;

        $attendance->update([
            'updated_at' => now(),
            'clock_in_at' => $new_clock_in_at,
            'clock_out_at' => $new_clock_out_at,
            'note' => $request->note,
            'rest_minutes' => $rest_time,
            'work_minutes' => $work_time
        ]);

        return redirect()->route('admin.attendance.list', ['date' => $attendance->work_date->format('Y-m-d')]);
    }

    public function adminStaffList(Request $request)
    {
        $user_id = $request->id;
        $show_user = User::findOrFail($user_id)->name;

        $date = $request->date
            ? Carbon::parse($request->date)
            : Carbon::now();

        $listData = $this->getMonthAttendances($user_id, $date);
        return view(
            '/admin/attendance/staff/list',
            [
                'attendances' => $listData['attendances'],
                'dates' => $listData['dates'],
                'currentDate' => $date,
                'show_user' => $show_user,
                'id' => $user_id
            ]
        );
    }

    // 月次勤怠の取得
    private function getMonthAttendances($user_id, $currentDate)
    {
        $date = $currentDate;
        $start = $date->copy()->startOfMonth();
        $end = $date->copy()->endOfMonth();

        // 今月の勤怠
        $attendances = Attendance::where('user_id', $user_id)
            ->whereBetween('work_date', [$start, $end])
            ->get()
            ->keyBy('work_date'); // ← 日付をキーにする

        // 今月の日付一覧
        $dates = CarbonPeriod::create($start, $end);
        return ['attendances' => $attendances, 'dates' => $dates];
    }
}
