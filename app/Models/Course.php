<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class Course extends Model
{
    protected $fillable = ['courseCode', 'courseName', 'department_id'];

    public function department() 
    { 
        return $this->belongsTo(Department::class); 
    }

    // public function students() 
    // { 
    //     return $this->belongsToMany(Student::class, 'enrollments'); 
    // }

    public function programmes()
    {
        return $this->belongsToMany(Programme::class, 'programme_course_semesters')
                    ->withPivot('semester_id', 'course_type', 'credit_weight', 'session_programme_id');
    }

    public function semesters()
    {
        return $this->belongsToMany(Semester::class, 'programme_course_semesters')
                    ->withPivot('programme_id', 'course_type', 'credit_weight', 'session_programme_id');
    }

    public function courseWorks()
    {
        return $this->hasMany(CourseWork::class);
    }

    public function courseworkResults()
    {
        return $this->hasMany(CourseworkResult::class);
    }


}
