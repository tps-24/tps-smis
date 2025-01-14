<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name'
    ];

    public function platoons(){
        return $this->hasMany(Platoon::class,'company_id','id');
    }
}
