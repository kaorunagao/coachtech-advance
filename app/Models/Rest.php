<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Attendance;

class Rest extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'start_at',
        'end_at',
        'total_at',
        'rest_in',
        'rest_out',
        'date',
    ];
    protected $dates = [
        'start_at',
        'total_at',
    ];

    public function attendances()
    {
        return $this->hasMany('App\Models\Attendance');
    }
}