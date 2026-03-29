<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\RequestStatus;

class StampCorrection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_id',
        'request_status',
        'note',
        'new_clock_in_at',
        'new_clock_out_at',
        'request_date',
        'approved_at',
        'approved_by'
    ];

    protected $casts = [
        'request_status' => RequestStatus::class,
        'request_date' => 'datetime',
        'new_clock_in_at' => 'datetime',
        'new_clock_out_at' => 'datetime',
    ];

    public function stampCorrectionRests()
    {
        return $this->hasMany(StampCorrectionRest::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
