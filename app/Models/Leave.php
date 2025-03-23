<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'sir_major_id', 'inspector_id', 'chief_instructor_id',
        'leave_type', 'start_date', 'end_date', 'reason',
        'status', 'rejection_reason',
    ];

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function sirMajor()
    {
        return $this->belongsTo(Staff::class, 'sir_major_id');
    }

    public function inspector()
    {
        return $this->belongsTo(Staff::class, 'inspector_id');
    }

    public function chiefInstructor()
    {
        return $this->belongsTo(Staff::class, 'chief_instructor_id');
    }
}
