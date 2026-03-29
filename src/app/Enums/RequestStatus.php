<?php

namespace App\Enums;

enum RequestStatus: int
{
    case PENDING = 1;
    case APPROVED = 2;

    // 表示名を返す
    public function label(): string
    {
        return match ($this) {
            self::PENDING => '承認待ち',
            self::APPROVED => '承認済み',
        };
    }
}