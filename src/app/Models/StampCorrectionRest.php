<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StampCorrectionRest extends Model
{
    use HasFactory;

    protected $fillable = [
        'stamp_correction_id',
        'rest_id',
        'rest_start_at',
        'rest_end_at',
    ];

    protected $casts = [
        'rest_start_at' => 'datetime',
        'rest_end_at' => 'datetime',
    ];
    public function stampCorrectionRequest()
    {
        return $this->belongsTo(StampCorrection::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
