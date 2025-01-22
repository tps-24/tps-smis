<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinalResult extends Model
{
    protected $table = 'final_results'; 
    protected $fillable = [ 'student_id', 'semester_id', 'course_id', 'program_id', 'total_score', 'grade', ];

    public function student() 
    { 
        return $this->belongsTo(Student::class); 
    } 
    public function semester() 
    { 
        return $this->belongsTo(Semester::class); 
    } 
    public function course() 
    { 
        return $this->belongsTo(Course::class); 
    } 
    public function program() 
    { 
        return $this->belongsTo(Program::class); 
    }
}
