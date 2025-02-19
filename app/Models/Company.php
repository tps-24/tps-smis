<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    
    protected $fillable = ['name'];

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
    public function areas(){
        return $this->hasMany(Area::class);
    }

    public function patrol_areas(){
        return $this->hasMany(PatrolArea::class,'company_id','id');
    }
}
