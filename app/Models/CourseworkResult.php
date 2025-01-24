<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseworkResult extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'coursework_id', 'score', 'semester_id'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function coursework()
    {
        return $this->belongsTo(CourseWork::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
