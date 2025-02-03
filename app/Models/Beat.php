<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beat extends Model
{
    protected $fillable = [
        'beatType_id',
        'area_id',
        'student_id',
        'status',
        'round',
        'date',
        'start_at',
        'end_at'
    ];

    protected $casts = [
        'date'=> 'datetime',
    ];
    public function area(){
        return $this->belongsTo(Area::class,'area_id', 'id');
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function staff(){
        return $this->hasMany(Staff::class);
    }
    public function beatType(){
        return $this->belongsTo(BeatType::class,'beatType_id', 'id');
    }
}
