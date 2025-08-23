<?php
namespace App\Http\Controllers;

use App\Models\Weapon;
use App\Models\Armory;
use App\Models\WeaponHandover;
use Illuminate\Http\Request;

class WeaponHandoverController extends Controller
{
    public function create(Weapon $weapon)
    {
        $staff = Armory::all();
        return view('weapons.handover', compact('weapon', 'staff'));
    }

    public function store(Request $request, Weapon $weapon)
    {
        $request->validate([
            'staff_id' => 'required|exists:armories,id',
            'handover_date' => 'required|date',
            'remarks' => 'nullable'
        ]);

        WeaponHandover::create([
            'weapon_id' => $weapon->id,
            'staff_id' => $request->staff_id,
            'handover_date' => $request->handover_date,
            'remarks' => $request->remarks,
            'status' => 'assigned'
        ]);

        return redirect()->route('weapons.show', $weapon)->with('success', 'Weapon handed over successfully.');
    }

    public function returnWeapon(WeaponHandover $handover)
    {
        $handover->update([
            'status' => 'returned',
            'return_date' => now()
        ]);

        return back()->with('success', 'Weapon returned.');
    }
}
