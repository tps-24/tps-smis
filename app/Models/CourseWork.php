<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseWork extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'semester_id', 'coursework_title', 'assessment_type', 'max_score', 'due_date'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function courseworkResults()
    {
        return $this->hasMany(CourseworkResult::class);
    }
}
