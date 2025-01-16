<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\studyLevel;

class Programme extends Model
{
    /** @use HasFactory<\Database\Factories\ProgrammeFactory> */
    use HasFactory;
    protected $fillable = ['programmeName', 'abbreviation', 'duration', 'department_id', 'studyLevel_id'];

    public function department() 
    { 
        return $this->belongsTo(Department::class); 
    }
}
