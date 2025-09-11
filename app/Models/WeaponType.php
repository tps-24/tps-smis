<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeaponType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * A weapon type can have many weapon models.
     */
   
    public function models()
{
    return $this->hasMany(WeaponModel::class, 'weapon_type_id');
}



public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}

}
