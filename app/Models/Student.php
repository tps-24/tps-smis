<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;


class Student extends Model
{
    protected $casts = [
        'next_of_kin' => 'array',
    ];

    protected $fillable = [
        'force_number', 'rank', 'first_name', 'middle_name', 'last_name',
        'user_id', 'vitengo_id', 'gender', 'blood_group', 'phone', 'nin', 
        'dob', 'education_level', 'home_region', 'company_id', 'programme_id', 'session_programme_id',
        'height', 'weight', 'platoon', 'next_kin_names', 'next_kin_phone', 
        'next_kin_relationship', 'next_kin_address', 'next_of_kin', 'profile_complete', 'photo', 
        'status', 'approved_at', 'rejected_at', 'reject_reason', 'approved_by', 
        'rejected_by', 'transcript_printed', 'certificate_printed', 'printed_by', 
        'reprint_reason','beat_exclusion_vitengo_id','beat_emergency'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function admittedStudent()
    {
        return $this->hasOne(AdmittedStudent::class);
    }
    public function studyLevel()
    {
        return $this->belongsTo(studyLevel::class, 'studyLevel_id');
    }

    public function finalResults()
    {
        return $this->hasMany(FinalResult::class);
    }
    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function vitengo()
    {
        return $this->belongsTo(Vitengo::class);
    }

    public function mps()
    {
        return $this->hasMany(MPS::class, 'student_id', 'id');
    }
    public function platoons()
    {
        return $this->hasMany(Platoon::class, 'name', 'id');
    }


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
    // public function programme()
    // {
    //     return $this->hasOne(Programme::class, 'id', 'programme_id');
    // }

    // public function platoon()
    // {
    //     //return $this->company()->merge($this->platoons());
    // }

    public function optionalCourseEnrollments()
    {
        return $this->hasMany(OptionalCourseEnrollment::class); //Optional enrollments
    }

    public function optionalCourses()
    {
        return $this->optionalCourseEnrollments()->with('course')->get()->pluck('course');
    }

    public function courses()
    {
        $programmeCourses = $this->programme->courses()->get(); // Fixed here
        $optionalCourses = $this->optionalCourses();
        // return $programmeCourses->merge($optionalCourses);
        return $programmeCourses;
    }

    public function approve()
    {
        $this->status = 'approved';
        $this->approved_at = now();
        $this->approved_by = Auth::user()->id;
        $this->save();
    }

    public function beats() 
    {
        return $this->belongsToMany(Beat::class, 'student_beat', 'student_id', 'beat_id')
                    ->withTimestamps();
    }

    // public function getPhotoUrlAttribute()
    // {
    //     return Storage::url($this->photo);
    // }

    public function safari(){
        return $this->hasMany(SafariStudent::class);
    }

    public function pendingSafari(){
        return $this->safari()->where('status','safari');
    }

    public function sick(){
        return $this->hasMany(Patient::class);
    }
}