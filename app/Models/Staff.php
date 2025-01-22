<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
            'force_number',
            'rank',
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
            'blood_group',
            'height',
            'weight'
        ];
}
