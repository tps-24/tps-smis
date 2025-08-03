<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArmoryShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_id', 'shift_date', 'shift_start_time',
        'shift_end_time', 'officer_in_charge_id',
        'secondary_officer_id', 'remarks'
    ];

    public function officerInCharge()
    {
        return $this->belongsTo(Officer::class, 'officer_in_charge_id');
    }

    public function secondaryOfficer()
    {
        return $this->belongsTo(Officer::class, 'secondary_officer_id');
    }
}
