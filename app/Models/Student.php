<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
   protected $fillable = [
    'force_number',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'phone',
        'nin',
        'dob',
        'home_region',
        'company',
        'platoon',
        'education_level',
        'rank',
        'height',
        'weight',
    ];
}
