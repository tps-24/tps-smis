<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    
    // use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function platoons(){
        return $this->hasMany(Platoon::class,'company_id','id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'company_id');
    }
    

    public function guardAreas()
    {
        return $this->hasMany(GuardArea::class);
    }

    public function patrolAreas()
    {
        return $this->hasMany(PatrolArea::class);
    }
    // public function platoons(){
    //     return $this->hasMany(Platoon::class);
    // } 
}
