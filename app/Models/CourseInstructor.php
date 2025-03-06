<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseInstructor extends Model
{
    protected $table = 'course_instructors';
    
    protected $fillable = [
        'programme_course_semester_id',
        'user_id',
        'academic_year',
    ];

    public function programmeCourseSemester()
    {
        return $this->belongsTo(ProgrammeCourseSemester::class);
    }
}
