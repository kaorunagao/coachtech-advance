<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rest extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'start_at',
        'date'
    ];
    protected $dates = [
        'start_at',
        'total_at'
    ];

    public function attendance()
    {
        return $this->hasMany('App\Models\Attendance');
    }
}