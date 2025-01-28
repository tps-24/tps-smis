<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beat extends Model
{
    protected $fillable = [
        'beatType_id',
        'area_id',
        'student_id',
        'start_at',
        'end_at'
    ];

    protected $casts = [
        "start_at" => "datetime",
        "end_at" => "datetime"
    ];
    public function area(){
        return $this->belongsTo(Area::class,'area_id', 'id');
    }

    public function students(){
        return $this->hasMany(Student::class);
    }

    public function staff(){
        return $this->hasMany(Staff::class);
    }
}
