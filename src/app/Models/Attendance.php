<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\AttendanceStatus;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_date',
        'clock_in_at',
        'clock_out_at',
        'attendance_status'
    ];

    protected $casts = [
        'attendance_status' => AttendanceStatus::class,
    ];



    public function user()
    {
        return $this->hasOne(User::class);
    }
}
