<?php
namespace App\Http\Controllers;
use App\Models\Weapon;
use Illuminate\Http\Request;

class WeaponController extends Controller
{
    public function index()
    {
        $weapons = Weapon::latest()->paginate(10);
        return view('weapons.index', compact('weapons'));
    }

    public function create()
    {
        return view('weapons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'weapon_id' => 'required|unique:weapons',
            'serial_number' => 'required|unique:weapons',
            'weapon_type' => 'required',
            'category' => 'required',
            'make_model' => 'required',
            'acquisition_date' => 'required|date',
            'condition' => 'required',
            'current_status' => 'required',
        ]);

        Weapon::create($request->all());

        return redirect()->route('weapons.index')->with('success', 'Weapon added successfully.');
    }

    public function show(Weapon $weapon)
    {
        return view('weapons.show', compact('weapon'));
    }

    public function edit(Weapon $weapon)
    {
        return view('weapons.edit', compact('weapon'));
    }

    public function update(Request $request, Weapon $weapon)
    {
        $request->validate([
            'weapon_type' => 'required',
            'category' => 'required',
            'make_model' => 'required',
            'acquisition_date' => 'required|date',
            'condition' => 'required',
            'current_status' => 'required',
        ]);

        $weapon->update($request->all());

        return redirect()->route('weapons.index')->with('success', 'Weapon updated successfully.');
    }

    public function destroy(Weapon $weapon)
    {
        $weapon->delete();
        return redirect()->route('weapons.index')->with('success', 'Weapon deleted successfully.');
    }
}
