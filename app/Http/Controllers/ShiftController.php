<?php

namespace App\Http\Controllers;

use App\Models\ArmoryShift;
use App\Models\Officer;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
   
   // public function index()
    //{
      //  $shifts = ArmoryShift::latest()->paginate(10);
        //return view('shifts.index', compact('shifts'));
   // }
public function index()
{
    $shifts = ArmoryShift::with('officerInCharge')->get();

    $events = $shifts->map(function ($shift) {
        return [
            'title' => $shift->officerInCharge->full_name ?? 'Shift',
            'start' => $shift->shift_date . 'T' . $shift->shift_start_time,
            'end' => $shift->shift_date . 'T' . $shift->shift_end_time,
            'url' => route('shifts.show', $shift->id),
        ];
    });

    return view('shifts.index', compact('shifts', 'events'));
}



    public function create()
    {
        $officers = Officer::where('status', 'Active')->get();
        return view('shifts.create', compact('officers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shift_id' => 'required|unique:armory_shifts',
            'shift_date' => 'required|date',
            'shift_start_time' => 'required',
            'shift_end_time' => 'required',
            'officer_in_charge_id' => 'required|exists:officers,id',
        ]);

        ArmoryShift::create($request->all());

        return redirect()->route('shifts.index')->with('success', 'Shift scheduled.');
    }

    public function show(ArmoryShift $shift)
    {
        return view('shifts.show', compact('shift'));
    }

    public function edit(ArmoryShift $shift)
    {
        $officers = Officer::where('status', 'Active')->get();
        return view('shifts.edit', compact('shift', 'officers'));
    }

    public function update(Request $request, ArmoryShift $shift)
    {
        $request->validate([
            'shift_date' => 'required|date',
            'shift_start_time' => 'required',
            'shift_end_time' => 'required',
            'officer_in_charge_id' => 'required|exists:officers,id',
        ]);

        $shift->update($request->all());

        return redirect()->route('shifts.index')->with('success', 'Shift updated.');
    }

    public function destroy(ArmoryShift $shift)
    {
        $shift->delete();
        return redirect()->route('shifts.index')->with('success', 'Shift deleted.');
    }
}
