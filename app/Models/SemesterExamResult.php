<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterExamResult extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'exam_id', 'score', 'semester_id'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function exam()
    {
        return $this->belongsTo(SemesterExam::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
