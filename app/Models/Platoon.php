<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Platoon extends Model
{
    protected $fillable = [
        'company_id',
        'name'
    ];

    public function company(){
        return $this->belongsTo(Company::class,'company_id', 'id');
    }

    public function attendences(){
        return $this->hasMany(Attendence::class,'platoon_id', 'id');
    }

    public function students(){
        return $this->hasMany(Student::class,'platoon', 'name');
    }

        public function platoon_students($companyId){
        return $this->students()->where('company_id', $companyId)->get();
    }
    public function lockUp(){
        return $this->hasManyThrough(MPS::class, Student::class, 'platoon', 'student_id', 'name', 'id');
    }

    private function sick(){
        return $this->hasManyThrough(Patient::class, Student::class, 'platoon', 'student_id', 'name', 'id');
    }
    public function leaves(){
        return $this->hasManyThrough(LeaveRequest::class, Student::class, 'platoon', 'student_id', 'name', 'id');
    }
public function today_attendence($attendanceType_id = null, $date = null)
{
    $selectedSessionId = session('selected_session', 1); // fallback to 1
    if(!$date){
        $date = \Carbon\Carbon::today();
    }
    $query = $this->attendences()
        ->where('session_programme_id', $selectedSessionId)
        ->whereDate('date', \Carbon\Carbon::parse($date));

    if ($attendanceType_id) {
        $query->where('attendenceType_id', $attendanceType_id);
    }

    return $query->get(); // return actual data, not query builder
}


    public function today_sick(){
        return $this->sick();
    }

    public function today_admitted(){
        $selectedSessionId = session('selected_session');
        if (!$selectedSessionId)
            $selectedSessionId = 1;
       return $this->sick()->where('session_programme_id', $selectedSessionId)->where('excuse_type_id',3)->whereNull('released_at');
    }

        public function todayEd(){
        $selectedSessionId = session('selected_session');
        if (!$selectedSessionId)
            $selectedSessionId = 1;
         $today = Carbon::today();
            return $this->sick()
                ->where('session_programme_id', $selectedSessionId)
                ->where('excuse_type_id', 1) // ED
                ->whereNull('released_at');
                // ->whereDate('patients.created_at', '<=', $today)
                // ->whereRaw("DATE_ADD(patients.created_at, INTERVAL rest_days DAY) >= ?", [$today]);
    }

}
