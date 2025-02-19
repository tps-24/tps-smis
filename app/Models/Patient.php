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
       
        'company',
        'platoon',
        'status', // Workflow status: pending, approved, rejected, treated
        'receptionist_comment', // Comments from the receptionist
        'updated_by', // User ID of the last person who updated the record
    ];

    /**
     * Define the relationship to the Student model.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    /**
     * Define the relationship to the User model for tracking updates.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope for filtering by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by company and platoon.
     */
    public function scopeByCompanyAndPlatoon($query, $company, $platoon)
    {
        return $query->where('company', $company)->where('platoon', $platoon);
    }

    /**
     * Set default attributes for new records.
     */
    protected static function booted()
    {
        static::creating(function ($patient) {
            $patient->status = $patient->status ?? 'pending';
        });
    }
}
