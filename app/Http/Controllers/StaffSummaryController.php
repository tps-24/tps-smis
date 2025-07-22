<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Staff;
use App\Models\StudentDismissed;
use Illuminate\Support\Facades\Log;

class StaffSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if a session ID has been submitted
        if (request()->has('session_id')) {
            // Store the selected session ID in the session
            session(['selected_session' => request()->session_id]);
        }
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }
        
        // Aggregate summary counts
        $stats = [
            'total'   => Staff::all(),
            'active' => Staff::where('status', 'active')->get(),
            'dismissed'       => Staff::where('status', 'dismissed')->get(),
            'study'        => Staff::where('status', 'study')->get(),
            'leave'        => Staff::where('status', 'leave')->get(),
            'trip'        => Staff::where('status', 'trip')->get(),
        ];
        return view('staffs.summary.index', compact('stats'));
    }

    public function filterStaff(Request $request)
    {
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }
        // Get the type from the request, defaulting to 'totalEnrolled'
        $type = $request->get('type');
        if (! in_array($type, ['active','total', 'study', 'leave','trip', 'dismissed'])) {
            $type = 'total'; // Default type
        }

        $query = Staff::query();

        switch ($type) {
            case 'active':
                $query->where('status', 'active');
                break;
            case 'study':
                $query->where('status', 'study');
                break;

            case 'leave':
                $query->where('status', 'leave');
                break;

            case 'trip':
                $query->where('status', 'trip');
                break;

            case 'dismissed':
                $query->where('status', 'dismissed');
                break;

            case 'total':
            default:
                $query->get();
                break;
        }

        $staffs = $query->paginate(10);

        return response()->json([
            'staffs' => $staffs,
        ]);
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
