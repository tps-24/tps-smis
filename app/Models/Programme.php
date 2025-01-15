<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    /** @use HasFactory<\Database\Factories\ProgrammeFactory> */
    use HasFactory;
    protected $fillable = ['programmeName', 'abbreviation', 'duration', 'department_id', 'studyLevel_id'];
}
