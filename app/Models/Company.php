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


    public function students(){
        return $this->hasMany(Student::class);
    }
    

    public function guardAreas()
    {
        return $this->hasMany(GuardArea::class);
    }

    public function patrolAreas()
    {
        return $this->hasMany(PatrolArea::class);
    }
    public function platoons(){
        return $this->hasMany(Platoon::class);
    } 

    public function beatRound(){
        return $this->hasMany(BeatRound::class);
    } 
    public function guardBeats()
    {
        return $this->hasManyThrough(Beat::class, GuardArea::class, 'company_id', 'guardArea_id', 'id', 'id');
    }

    public function patrolBeats()
    {
        return $this->hasManyThrough(Beat::class, PatrolArea::class, 'company_id', 'patrolArea_id', 'id', 'id');
    }

    public function lockUp(){
        return $this->hasManyThrough(MPS::class, Student::class, 'company_id', 'student_id', 'id', 'id');
 
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
    
    public function sickStudents(){
        return $this->hasMany(Patient::class);
    }
}
