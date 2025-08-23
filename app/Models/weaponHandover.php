<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeaponHandover extends Model
{
    protected $fillable = [
        'weapon_id',
        'staff_id',
        'staff_name',
        'staff_rank',
        'handover_date',
        'shift_end',
        'return_date',
        'purpose_before',
        'purpose',
        'remarks',
        'condition_after',
        'status',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function weapon()
    {
        return $this->belongsTo(Weapon::class);
    }
}
