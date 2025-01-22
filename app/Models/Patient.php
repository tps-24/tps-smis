<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';

    protected $fillable = [
        'student_id',
        'excuse_type',
        'rest_days',
        'doctor_comment',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
