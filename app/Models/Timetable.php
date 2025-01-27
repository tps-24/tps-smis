<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'day',
        'venue',
        'subject',
        'teacher',
        'start_time',
        'end_time',
    ];
}
