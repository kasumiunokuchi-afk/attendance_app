<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Rest;
use App\Models\StampCorrection;
use App\Models\StampCorrectionRest;
use App\Enums\RequestStatus;

class StampCorrectionController extends Controller
{
    public function update(AttendanceRequest $request)
    {
        $new_clock_in_at = Carbon::parse($request->work_date)
            ->setTimeFromTimeString($request->clock_in_at);
        $new_clock_out_at = Carbon::parse($request->work_date)
            ->setTimeFromTimeString($request->clock_out_at);

        $param = [
            'user_id' => $request->user_id,
            'attendance_id' => $request->id,
            'request_status' => RequestStatus::PENDING,
            'note' => $request->note,
            'new_clock_in_at' => $new_clock_in_at,
            'new_clock_out_at' => $new_clock_out_at,
            'request_date' => Carbon::now(),
        ];
        $stampCorrection = StampCorrection::create($param);

        foreach ($request->rests as $rest) {
            // 空行スキップ
            if (empty($rest['start']) && empty($rest['end'])) {
                continue;
            }
            StampCorrectionRest::create([
                'stamp_correction_id' => $stampCorrection->id,
                'rest_id' => $rest['id'] ?? null, // 既存なら入る
                'rest_start_at' => Carbon::parse($request->work_date)
                    ->setTimeFromTimeString($rest['start']),
                'rest_end_at' => Carbon::parse($request->work_date)
                    ->setTimeFromTimeString($rest['end']),
            ]);
        }
        return redirect('/stamp_correction_request/list');
    }

    public function list(Request $request)
    {
        $status = $request->get('status', RequestStatus::PENDING); // デフォルト

        $requests = null;
        if (auth()->user()->isAdmin()) {
            // 管理者ユーザーの場合
            // 全員分の承認情報を取得
            $requests = StampCorrection::where('request_status', $status)
                ->latest()
                ->get();
        } else {
            // 一般ユーザーの場合
            // 自分の承認情報を取得
            $requests = StampCorrection::where('user_id', auth()->id())
                ->where('request_status', $status)
                ->latest()
                ->get();
        }

        return view('/stamp_correction_request/list', compact('requests', 'status'));
    }

    public function detail(Request $request)
    {

        $stampCorrection = StampCorrection::where('id', $request->id)
            ->latest()
            ->first();

        return view('/stamp_correction_request/detail', compact('stampCorrection'));
    }

    public function approve($id)
    {

        $stampCorrection = StampCorrection::findOrFail($id);
        $attendance = Attendance::findOrFail($stampCorrection->attendance_id);

        foreach ($stampCorrection->stampCorrectionRests as $stampCorrectionRest) {

            if ($stampCorrectionRest['rest_id'] === null) {
                // 行追加
                Rest::create(
                    [
                        'attendance_id' => $attendance->id,
                        'rest_start_at' => $stampCorrectionRest->rest_start_at,
                        'rest_end_at' => $stampCorrectionRest->rest_end_at
                    ]
                );
            } else {
                $rest = Rest::findOrFail($stampCorrectionRest['rest_id']);
                $rest->update([
                    'updated_at' => now(),
                    'rest_start_at' => $stampCorrectionRest->rest_start_at,
                    'rest_end_at' => $stampCorrectionRest->rest_end_at
                ]);
            }
        }

        $work_time = $attendance->work_minutes;
        $rest_time = $attendance->total_rest_minutes;

        $attendance->update([
            'updated_at' => now(),
            'clock_in_at' => $stampCorrection->new_clock_in_at,
            'clock_out_at' => $stampCorrection->new_clock_out_at,
            'note' => $stampCorrection->note,
            'rest_minutes' => $rest_time,
            'work_minutes' => $work_time
        ]);

        // 承認処理
        $stampCorrection->update([
            'request_status' => RequestStatus::APPROVED,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return response()->json([
            'status' => 'approved'
        ]);
    }
}
