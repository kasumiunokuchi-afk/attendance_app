<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class AttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'clock_in_at' => ['required', 'date_format:H:i'],
            'clock_out_at' => ['nullable', 'date_format:H:i'],
            'rests.*.start' => ['nullable', 'date_format:H:i'],
            'rests.*.end' => ['nullable', 'date_format:H:i'],
            'note' => ['required', 'string'], // 備考
        ];
    }
    public function messages()
    {
        return [
            'note.required' => '備考を記入してください',
            'clock_in_at.required' => '出勤時刻を入力してください',
            'clock_in_at.date_format' => '出勤時刻はH:i形式で入力してください',
            'clock_out_at.date_format' => '退勤時刻はH:i形式で入力してください',
            'rests.*.start.date_format' => '休憩開始時刻はH:i形式で入力してください',
            'rests.*.end.date_format' => '休憩終了時刻はH:i形式で入力してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $clockIn = $this->clock_in_at ? Carbon::parse($this->clock_in_at) : null;
            $clockOut = $this->clock_out_at ? Carbon::parse($this->clock_out_at) : null;

            // ① 出勤・退勤の前後チェック
            if ($clockIn && $clockOut && $clockIn >= $clockOut) {
                $validator->errors()->add(
                    'clock_in_at',
                    '出勤時間もしくは退勤時間が不適切な値です'
                );
            }

            // ② 休憩時間チェック
            if ($this->rests && $clockIn && $clockOut) {
                foreach ($this->rests as $index => $rest) {

                    if (empty($rest['start']) || empty($rest['end'])) {
                        continue;
                    }

                    $restStart = Carbon::parse($rest['start']);
                    $restEnd = Carbon::parse($rest['end']);

                    if (
                        $restStart < $clockIn ||
                        $restEnd > $clockOut
                    ) {
                        $validator->errors()->add(
                            "rests.$index.start",
                            '休憩時間が勤務時間外です'
                        );
                    } else if ($restStart > $restEnd) {

                        $validator->errors()->add(
                            "rests.$index.start",
                            '休憩時間が不正です'
                        );
                    }
                }
            }
        });
    }
}
