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
        'programme_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }


    public function mps()
    {
        return $this->hasMany(MPS::class, 'student_id', 'id');
    }
    public function platoons()
    {
        return $this->hasMany(Platoon::class, 'name', 'id');
    }

    public function company()
    {
        return $this->hasMany(Company::class, 'name', 'id');
    }

    // public function platoon()
    // {
    //     //return $this->company()->merge($this->platoons());
    // }
    
    // public function courses() 
    // { 
    //     return $this->belongsToMany(Course::class, 'enrollments'); 
    // }

    public function optionalCourseEnrollments()
    {
        return $this->hasMany(OptionalCourseEnrollment::class); //Optional enrollments
    }
}
