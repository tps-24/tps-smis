<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
class WeaponModel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'weapon_type_id', 'category_id'];

    public function type()
    {
        return $this->belongsTo(WeaponType::class, 'weapon_type_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function weapons()
{
    return $this->hasMany(Weapon::class, 'weapon_model_id');
}

}

