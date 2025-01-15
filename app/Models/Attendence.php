<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
   protected $fillable =[
   'attendenceType_id',
    'platoon_id',
    'present',
    'sentry',
    'absent',
    'adm',
    'safari',
    'off',
    'mess',
    'female',
    'male',
    'total'
   ]; 
   public function platoon(){
      return $this->belongsTo(Platoon::class, 'platoon_id','id');
   }
   public function type(){
      return $this->belongsTo(AttendenceType::class, 'attendenceType_id','id');
   }
}
