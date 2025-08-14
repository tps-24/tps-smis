<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weapon extends Model
{
    //protected $fillable = ['serial_number', 'specification', 'weapon_model_id'];

     protected $fillable = [
        'serial_number',
        'specification',
        'category',
        'weapon_model'
    ];

    public function model()
    {
        return $this->belongsTo(WeaponModel::class, 'weapon_model_id');
    }

    public function handovers()
    {
        return $this->hasMany(WeaponHandover::class);
    }

    
}
