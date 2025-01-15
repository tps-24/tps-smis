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
        'blood_group',
        'rank',
        'height',
        'weight',
        'next_kin_names',
        'next_kin_phone',
        'next_kin_relationship',
        'next_kin_address',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
