<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class Course extends Model
{
    protected $fillable = ['programmeName', 'abbreviation', 'duration', 'department_id', 'studyLevel_id'];

    public function department() 
    { 
        return $this->belongsTo(Department::class); 
    }
}
