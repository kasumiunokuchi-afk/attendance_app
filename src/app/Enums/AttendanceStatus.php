<?php

namespace App\Enums;

enum AttendanceStatus: int
{
    case BEFORE_WORK = 1; // 勤務外
    case WORKING = 2;     // 出勤中
    case BREAKING = 3;    // 休憩中
    case AFTER_WORK = 4;  // 退勤済

    // 表示名を返す
    public function label(): string
    {
        return match ($this) {
            self::BEFORE_WORK => '勤務外',
            self::WORKING => '出勤中',
            self::BREAKING => '休憩中',
            self::AFTER_WORK => '退勤済',
        };
    }

}