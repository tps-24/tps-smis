<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    
    protected $fillable = [
        "name",
        "number_of_guards",
        "added_by",
        "campus_id"
    ];

    public function beats(){
        return $this->hasMany(Beat::class);
    }
}
