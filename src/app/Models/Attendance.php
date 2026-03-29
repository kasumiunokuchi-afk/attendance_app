<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\AttendanceStatus;
use App\Enums\RequestStatus;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_date',
        'clock_in_at',
        'clock_out_at',
        'attendance_status',
        'rest_minutes',
        'work_minutes',
        'note'
    ];

    protected $casts = [
        'attendance_status' => AttendanceStatus::class,
        'work_date' => 'datetime',
        'clock_in_at' => 'datetime',
        'clock_out_at' => 'datetime',
    ];

    public function rests()
    {
        return $this->hasMany(Rest::class);
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function stampCorrections()
    {
        return $this->hasMany(StampCorrection::class);
    }

    public function getTotalRestMinutesAttribute()
    {
        return $this->rests->sum(function ($rest) {
            if (!$rest->rest_end_at)
                return 0;

            return $rest->rest_start_at
                ->diffInMinutes($rest->rest_end_at);
        });
    }
    public function getWorkMinutesAttribute()
    {
        if (!$this->clock_out_at)
            return 0;

        $total = $this->clock_in_at
            ->diffInMinutes($this->clock_out_at);

        return $total - $this->total_rest_minutes;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getWorkTimeAttribute(): ?string
    {
        if ($this->work_minutes === null) {
            return null;
        }

        return sprintf(
            '%d:%02d',
            intdiv($this->work_minutes, 60),
            $this->work_minutes % 60
        );
    }

    public function getTotalRestTimeAttribute(): ?string
    {
        if ($this->rest_minutes === null) {
            return null;
        }

        return sprintf(
            '%d:%02d',
            intdiv($this->rest_minutes, 60),
            $this->rest_minutes % 60
        );
    }

    public function isEditable()
    {
        return !$this->stampCorrections()
            ->where('request_status', RequestStatus::PENDING)
            ->exists();
    }
}
