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
use App\Models\Handover;
use Illuminate\Support\Facades\DB;

class WeaponController extends Controller
{
public function index(Request $request)
{
    // Base query with eager loading
    $query = Weapon::with(['model.type', 'model.category']);

    // Apply filters
    if ($request->filled('category')) {
        $query->whereHas('model.category', function ($q) use ($request) {
            $q->where('name', $request->category);
        });
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('serial_number', 'like', "%{$search}%")
              ->orWhereHas('model', function ($q2) use ($search) {
                  $q2->where('name', 'like', "%{$search}%");
              });
        });
    }

    // Paginated weapons
    $weapons = $query->paginate(50);

    // ✅ FIXED: Stats with proper joins
    $stats = Weapon::selectRaw('categories.name as category, COUNT(*) as total')
        ->join('weapon_models', 'weapons.weapon_model_id', '=', 'weapon_models.id')
        ->join('categories', 'weapon_models.category_id', '=', 'categories.id')
        ->groupBy('categories.name')
        ->get();

    // ✅ Count filtered weapons
    $totalWeapons = $query->count();

    // ✅ Modal category counts with same filters
    $categoryCounts = Weapon::join('weapon_models', 'weapons.weapon_model_id', '=', 'weapon_models.id')
        ->join('categories', 'weapon_models.category_id', '=', 'categories.id')
        ->select('categories.name as category', \DB::raw('COUNT(*) as total'))
        ->groupBy('categories.name');

    if ($request->filled('category')) {
        $categoryCounts->where('categories.name', $request->category);
    }
    if ($request->filled('search')) {
        $search = $request->search;
        $categoryCounts->where(function ($q) use ($search) {
            $q->where('weapons.serial_number', 'like', "%{$search}%")
              ->orWhere('weapon_models.name', 'like', "%{$search}%");
        });
    }

    $categoryCounts = $categoryCounts->pluck('total', 'category')->toArray();

    return view('weapons.index', compact(
        'weapons',
        'stats',
        'totalWeapons',
        'categoryCounts'
    ));
}

    public function create()
{
    $categories = \App\Models\Category::with('types.models')->get();

    return view('weapons.create', compact('categories'));
}


  public function store(Request $request)
{
    $request->validate([
        'serial_number' => 'required|unique:weapons,serial_number',
        'weapon_model_id' => 'required|exists:weapon_models,id',
    ]);

    Weapon::create([
        'serial_number' => $request->serial_number,
        'weapon_model_id' => $request->weapon_model_id,
    ]);

    return redirect()->route('weapons.index')
                     ->with('success', 'Weapon added successfully!');
}

    public function show(Weapon $weapon)
    {
        $weapon->load('model.type', 'model.category', 'handovers.staff');
        return view('weapons.show', compact('weapon'));
    }

   public function edit(Weapon $weapon)
{
    // Eager-load the weapon's model, type, and category
    $weapon->load('model.type', 'model.category');

    // Fetch all models with their type & category (for dropdown)
    $models = WeaponModel::with(['type', 'category'])->get();

    return view('weapons.edit', compact('weapon', 'models'));
}

public function update(Request $request, Weapon $weapon)
{
    $request->validate([
        'serial_number'    => 'required|string|max:255',
        'weapon_model_id'  => 'required|exists:weapon_models,id',
    ]);

    $weapon->update([
        'serial_number'    => $request->serial_number,
        'weapon_model_id'  => $request->weapon_model_id,
    ]);

    return redirect()->route('weapons.index')
                     ->with('success', 'Weapon updated successfully!');
}

    public function destroy(Weapon $weapon)
    {
        $weapon->delete();
        return redirect()->route('weapons.index')->with('success', 'Weapon deleted successfully.');
    }

    public function storeHandover(Request $request, Weapon $weapon)
{
    $request->validate([
        'staff_id' => 'required|exists:staff,id',
        'handover_date' => 'required|date',
        'return_date' => 'required|date|after:handover_date',
        'purpose' => 'required|string',
        'remarks' => 'nullable|string',
    ]);

    $handover = Handover::create([
        'weapon_id'    => $weapon->id,
        'staff_id'     => $request->staff_id,
        'handover_date'=> $request->handover_date,
        'return_date'  => $request->return_date,
        'purpose'      => $request->purpose,
        'remarks'      => $request->remarks,
        'status'       => 'assigned',
    ]);

    $weapon->update(['status' => 'taken']);

    return redirect()->route('weapons.show', $weapon)
                     ->with('success', 'Weapon handover recorded successfully.');
}
    // Show handover form

    public function handover(Weapon $weapon)
    {
        return view('weapons.handover', compact('weapon'));
    }

   
    // Mark weapon as returned
    public function returnWeapon(Handover $handover)
    {
        $handover->update([
            'status'      => 'returned',
            'return_date' => now(), // auto-fill when returned
        ]);

        // Mark weapon back as available
        $handover->weapon->update(['status' => 'available']);

        return redirect()->route('weapons.show', $handover->weapon)
                         ->with('success', 'Weapon marked as returned.');
    }
}
