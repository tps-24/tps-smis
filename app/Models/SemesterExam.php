<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterExam extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'semester_id', 'exam_date', 'max_score'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function semesterExamResults()
    {
        return $this->hasMany(SemesterExamResult::class);
    }
}
