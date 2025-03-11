<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'company_id',
        'leave_start_date',
        'leave_end_date',
        'status',
        'sir_major_approval_status',
        'inspector_approval_status',
        'chief_instructor_approval_status',
        'rejection_reason',
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function sirMajor() {
        return $this->belongsTo(SirMajor::class);
    }

    public function inspector() {
        return $this->belongsTo(Inspector::class);
    }

    public function chiefInstructor() {
        return $this->belongsTo(ChiefInstructor::class);
    }
}
