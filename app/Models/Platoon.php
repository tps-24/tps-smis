<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Platoon extends Model
{
    protected $fillable = [
        'company_id',
        'name'
    ];

    public function company(){
        return $this->belongsTo(Company::class,'company_id', 'id');
    }

    public function attendences(){
        return $this->hasMany(Attendence::class,'platoon_id', 'id');
    }

    public function students(){
        return $this->hasMany(Student::class,'platoon', 'name');
    }

    public function lockUp(){
        return $this->hasManyThrough(MPS::class, Student::class, 'platoon', 'student_id', 'name', 'id');
    }
}
