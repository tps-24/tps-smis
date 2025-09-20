<?php
namespace App\Http\Controllers;

use App\Models\Weapon;
use App\Models\WeaponModel;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\WeaponType;
use App\Models\Armory;
use App\Models\Staff;
use App\Models\WeaponHandover;
use Illuminate\Support\Facades\DB;




class WeaponController extends Controller
{
  public function index(Request $request)
{
    $query = Weapon::query();

    // Apply category filter
    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    // Apply search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('serial_number', 'LIKE', "%{$search}%")
              ->orWhere('weapon_model', 'LIKE', "%{$search}%");
        });
    }

    // Paginated results after filtering
    $weapons = $query->paginate(10);

    // Filtered total count
    $totalWeapons = (clone $query)->count();

    // Filtered count per category
    $categoryCounts = (clone $query)
        ->select('category', DB::raw('count(*) as total'))
        ->groupBy('category')
        ->pluck('total', 'category');

    return view('weapons.index', compact('weapons', 'totalWeapons', 'categoryCounts'));
}




    public function create()
    {

        $models = WeaponModel::with('type', 'category')->get();
         $categories = Category::all();
         return view('weapons.create', compact('models', 'categories'));

        
    }

  public function store(Request $request)
{
    $request->validate([
        'serial_number' => 'required|string',
        'specification' => 'nullable|string',
        'category' => 'required|string',
        'weapon_model' => 'required|string',
    ]);

    Weapon::create([
        'serial_number' => $request->serial_number,
        'specification' => $request->specification,
        'category' => $request->category,
        'weapon_model' => $request->weapon_model,
    ]);

    return redirect()->route('weapons.index')->with('success', 'Weapon added successfully!');
}


    public function show(Weapon $weapon)
    {
        $weapon->load('model.type', 'model.category', 'handovers.staff');
        return view('weapons.show', compact('weapon'));
    }

    public function edit(Weapon $weapon)
    {
        $models = WeaponModel::with('type', 'category')->get();
        return view('weapons.edit', compact('weapon', 'models'));
    }

    public function update(Request $request, Weapon $weapon)
    {
        $request->validate([
            'serial_number' => 'required|unique:weapons,serial_number,' . $weapon->id,
            'specification' => 'nullable',
            'weapon_model_id' => 'required|exists:weapon_models,id'
        ]);

        $weapon->update($request->all());

        return redirect()->route('weapons.index')->with('success', 'Weapon updated successfully.');
    }

    public function destroy(Weapon $weapon)
    {
        $weapon->delete();
        return redirect()->route('weapons.index')->with('success', 'Weapon deleted successfully.');
    }

  public function handoverStore(Request $request, Weapon $weapon)
{
    $request->validate([
        'staff_id'       => 'required|exists:staff,id',
        'handover_date'  => 'required|date',
        'return_date'    => 'required|date|after_or_equal:handover_date',
        'remarks'        => 'nullable|string',
    ]);

    // Save to weapon_handovers table
    WeaponHandover::create([
        'weapon_id'      => $weapon->id,
        'staff_id'       => $request->staff_id,
        'handover_date'  => $request->handover_date,
        'return_date'    => $request->return_date,
        'remarks'        => $request->remarks,
        'status'         => 'assigned',
    ]);

    // Update weapon status to taken
    $weapon->update(['status' => 'taken']);

    return redirect()->route('weapons.show', $weapon->id)
        ->with('success', 'Weapon successfully handed over to staff.');
}

public function markAsReturned(WeaponHandover $handover)
{
    // Update handover record
    $handover->update([
        'status' => 'returned'
    ]);

    // Update weapon status to available
    $handover->weapon->update([
        'status' => 'available'
    ]);

    return redirect()->route('weapons.show', $handover->weapon_id)
        ->with('success', 'Weapon marked as returned.');
}



public function handover($id)
{
    $weapon = Weapon::findOrFail($id);

    // Fetch all staff with correct field names
    $staff = Staff::select('id', 'firstName', 'middleName', 'lastName', 'rank')
                  ->orderBy('firstName', 'asc')
                  ->get();

    return view('weapons.handover', compact('weapon', 'staff'));
}




 public function returnWeapon(Request $request, Handover $handover)
{
    $handover->update([
        'status' => 'returned',
        'return_date' => now(),
        'condition_on_return' => $request->condition_on_return ?? 'Good',
    ]);

    // ✅ Update weapon status back to Available
    $handover->weapon->update(['status' => 'Available']);

    return back()->with('success', 'Weapon marked as returned.');
}


}
