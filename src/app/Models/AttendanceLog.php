<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\AttendanceLogType;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'user_id',
        'log_type',
        'logged_at'
    ];

    protected $casts = [
        'log_type' => AttendanceLogType::class,
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
