<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name'
    ];

    public function platoons(){
        return $this->hasMany(Platoon::class,'company_id','id');
    }

    public function students(){
        return $this->hasMany(Student::class,'company','name');
    }
    public function areas(){
        return $this->hasMany(Area::class);
    }

    public function patrol_areas(){
        return $this->hasMany(PatrolArea::class,'company_id','id');
    }
}
