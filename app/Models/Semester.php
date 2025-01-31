<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = ['semester_name']; 
    
    public function courseWorks() 
    { 
        return $this->hasMany(CourseWork::class); 
    } 
    public function semesterExams() 
    { 
        return $this->hasMany(SemesterExam::class); 
    } 
    public function courseworkResults() 
    { 
        return $this->hasMany(CourseworkResult::class); 
    } 
    public function semesterExamResults() 
    { 
        return $this->hasMany(SemesterExamResult::class);
    } 
    public function finalResults() 
    { 
        return $this->hasMany(FinalResult::class); 
    }
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'programme_course_semesters')
                    ->withPivot('programme_id', 'course_type', 'credit_weight');
    }

    // public function programmeCourseSemesters()
    // {
    //     return $this->hasMany(ProgrammeCourseSemester::class);
    // }
}
