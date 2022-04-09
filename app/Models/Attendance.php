<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Attendance extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
    ];
    protected $dates=[
        'start_time','date'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}