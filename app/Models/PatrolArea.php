<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatrolArea extends Model
{
    protected $fillable =[
        'number_of_guards',
        'company_id',
        'start_area',
        'end_area'
    ];

    public function start(){
        return $this->belongsTo(Area::class,'start_area', 'id');
    }

    public function end(){
        return $this->belongsTo(Area::class,'end_area', 'id');
    }
    public function beats(){
        return $this->hasMany(Beat::class,'patrolArea_id', 'id');
    }
}
