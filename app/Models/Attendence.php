<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
   protected $fillable = [
      'attendenceType_id',
      'platoon_id',
      'present',
      'sentry',
      'absent',
      'adm',
      'ed',
      'safari',
      'off',
      'mess',
      'female',
      'male',
      'lockUp',
      'kazini',
      'sick',
      'lockUp_students_ids',
      'total',
      'absent_student_ids',
      'adm_student_ids',
      'ed_student_ids',
      'session_programme_id',
      'created_at',
      'updated_at'
   ];
   public function platoon()
   {
      return $this->belongsTo(Platoon::class, 'platoon_id', 'id');
   }
   public function type()
   {
      return $this->belongsTo(AttendenceType::class, 'attendenceType_id', 'id');
   }

   public function setAbsentStudentsAttribute($students)
   {
      $this->attributes['absent_students'] = $students;
   }
   public function sessionProgramme()
{
    return $this->belongsTo(SessionProgramme::class);
}

}
