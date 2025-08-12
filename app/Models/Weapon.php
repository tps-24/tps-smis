<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weapon extends Model
{
    protected $table = "weapon";
    protected $fillable = [
        'weapon_id', 'serial_number', 'weapon_type', 'category',
        'make_model', 'caliber_gauge', 'acquisition_date',
        'condition', 'current_status', 'location', 'remarks',
    ];

    public function movements() {
        return $this->hasMany(WeaponMovement::class);
    }
}
