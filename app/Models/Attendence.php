<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
   protected $fillable =[
    'platoon_id',
    'present',
    'sentry',
    'absent',
    'excuse_duty',
    'kazini',
    'adm',
    'safari',
    'off',
    'mess',
    'sick',
    'female',
    'male',
    'total'
   ]; 
   public function platoon(){
      return $this->belongsTo(Platoon::class, 'platoon_id','id');
   }
}
