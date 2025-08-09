<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Officer extends Model
{
    use HasFactory;

    protected $fillable = [
        'officer_id', 'service_number', 'full_name', 'rank',
        'contact_number', 'email', 'status'
    ];

    public function issuedMovements()
    {
        return $this->hasMany(WeaponMovement::class, 'issued_by_officer_id');
    }

    public function receivedMovements()
    {
        return $this->hasMany(WeaponMovement::class, 'issued_to_officer_id');
    }

    public function returnedMovements()
    {
        return $this->hasMany(WeaponMovement::class, 'returned_by_officer_id');
    }

    public function primaryShifts()
    {
        return $this->hasMany(ArmoryShift::class, 'officer_in_charge_id');
    }

    public function secondaryShifts()
    {
        return $this->hasMany(ArmoryShift::class, 'secondary_officer_id');
    }
}
