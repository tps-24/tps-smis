<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDismissal extends Model
{
    protected $fillable = ['student_id', 'reason_id', 'dismissed_at'];
public function student()
{
    return $this->belongsTo(Student::class, 'student_id');
}
}
