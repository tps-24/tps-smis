<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SessionProgramme; 
use App\Models\Student; 
use App\Models\Course;
use App\Models\Attendence;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the weekly attendance data
        $weeklyAttendance = Attendence::selectRaw('YEAR(created_at) as year, WEEK(created_at) as week, SUM(present) as total_present, SUM(absent) as total_absent')
        ->groupBy('year', 'week')
        ->orderBy('year', 'asc')
        ->orderBy('year', 'asc')
        ->get();

        // Calculate the comparison with the previous week 
        $weeklyComparison = []; 
        foreach ($weeklyAttendance as $index => $week) {
             if ($index > 0) {
                 $previousWeek = $weeklyAttendance[$index - 1]; 
                 $weeklyComparison[] = [ 
                    'year' => $week->year, 
                    'week' => $week->week, 
                    'present_difference' => $week->total_present - $previousWeek->total_present, 
                    'absent_difference' => $week->total_absent - $previousWeek->total_absent, 
                ]; 
            } 
        }

        // Get the count of current programs 
        $currentProgramsCount = SessionProgramme::where('is_current', 1)->count(); 
        // Get the count of inactive programs 
        $inactiveProgramsCount = SessionProgramme::where('is_current', 0)->count(); 
        // Get program details 
        $programmes = SessionProgramme::where('is_current', 1)->get(); 
        // Additional data for graphs here

        
        return view('dashboard.default_dashboard', compact('currentProgramsCount', 'inactiveProgramsCount', 'programmes', 'weeklyAttendance', 'weeklyComparison')); 
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
