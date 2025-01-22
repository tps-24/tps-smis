<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SessionProgramme extends Model
{
    use HasFactory;
  
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'programme_name','description','year','startDate','endDate','is_current','is_active'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
