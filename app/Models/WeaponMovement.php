<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeaponMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'movement_id', 'weapon_id', 'movement_type', 'purpose',
        'issue_date_time', 'return_date_time', 'issued_by_officer_id',
        'issued_to_officer_id', 'returned_by_officer_id', 'return_condition', 'remarks'
    ];

    public function weapon()
    {
        return $this->belongsTo(Weapon::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(Officer::class, 'issued_by_officer_id');
    }

    public function issuedTo()
    {
        return $this->belongsTo(Officer::class, 'issued_to_officer_id');
    }

    public function returnedBy()
    {
        return $this->belongsTo(Officer::class, 'returned_by_officer_id');
    }
}
