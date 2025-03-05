<?php

namespace App\Http\Controllers;

use App\Models\TimeSheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class TimeSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timesheets = [];
        $user = Auth::user();
          if(!Gate::allows('viewAny', $user)){
            $timesheets = TimeSheet::where('staff_id', $user->staff->id)->get();
            //abort(403);
          }else{
            $timesheets = TimeSheet::all();
          }               
        return view('time_sheets.index', compact('timesheets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('time_sheets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'task' => 'required|string|max:500',
            'time_in' => 'required|date_format:H:i',
            'time_out' => 'required|date_format:H:i',
            'date' => 'required|date'
        ]);

        TimeSheet::create([
            'staff_id' => $request->user()->staff->id,
            'task' => $request->task,
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
            'date' => $request->date
        ]);
        return redirect()->route('timesheets.index')->with('success', 'Time sheet created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TimeSheet $timeSheet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TimeSheet $timeSheet, $timeSheetId)
    {
    
        $timeSheet = TimeSheet::findOrFail($timeSheetId);
        if(!Gate::allows('view-timesheet', $timeSheet)){
            abort(403);
          }
        return view('time_sheets.edit', compact('timeSheet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TimeSheet $timeSheet, $timeSheetId)
    {
        $validated = $request->validate([
            'task' => 'required|string|max:255',
            'time_in' => 'required|date_format:H:i',
            'time_out' => 'required|date_format:H:i',
            'date' => 'required|date',
        ]);    
        $timeSheet = TimeSheet::findOrFail($timeSheetId);
        if(!Gate::allows('update-timesheet', $timeSheet)){
            abort(403);
          }
        $timeSheet -> task= $request->task;
        $timeSheet -> time_in= $request->time_in;
        $timeSheet -> time_out= $request->time_out;
        $timeSheet -> date= $request->date;
        $timeSheet->save();
        return redirect()->route('timesheets.index')->with('success', 'Timesheet updated successfully.');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeSheet $timeSheet, $timeSheetId)
    {
        $timeSheet = TimeSheet::findOrFail($timeSheetId);
        $timeSheet->delete();
        return redirect()->route('timesheets.index')->with('success', 'Timesheet deleted successfully.');

    }

    public function approve($timeSheetId){
        $user = Auth::user();
        if(!Gate::allows('viewAny', $user)){
            abort(403);
        }
        $timeSheet = TimeSheet::findOrFail($timeSheetId);

        $timeSheet->status = "approved";
        $timeSheet->approved_by = $user->staff->id?? '1';
        $timeSheet->save();

        return redirect()->route('timesheets.index')->with('success', 'Timesheet approved successfully.');

    }
}
