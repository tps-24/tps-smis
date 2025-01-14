<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Platoon extends Model
{
    protected $fillable = [
        'company_id',
        'name'
    ];

    public function company(){
        return $this->belongsTo(Company::class,'company_id', 'id');
    }

    public function attendences(){
        return $this->hasMany(Attendence::class,'platoon_id', 'id');
    }
    
}
