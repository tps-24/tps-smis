<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'excuse_type',
        'rest_days',
        'doctor_comment',
        'first_name', 
        'middle_name', 
        'last_name', 
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
