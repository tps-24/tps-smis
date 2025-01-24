<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['departmentName']; 

    public function staffs() 
    { 
        return $this->hasMany(Staff::class); 
    }

    public function programmes() 
    { 
        return $this->hasMany(Programme::class); 
    }
    
    public function courses() 
    { 
        return $this->hasMany(Course::class); 
    }
}
