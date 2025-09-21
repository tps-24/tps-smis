<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weapon extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'weapon_model_id',
    ];

    // ✅ A weapon belongs to a model (e.g. AK47 serial 1234 belongs to AK47 model)
    public function model()
    {
        return $this->belongsTo(WeaponModel::class, 'weapon_model_id');
    }

    // ✅ Weapons Handover history
    public function handovers()
    {
        return $this->hasMany(Handover::class);
    }
}
