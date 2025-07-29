<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentDismissed;

class IntakeHistoryController extends Controller
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
            'totalEnrolled'   => Student::where('session_programme_id', $selectedSessionId)->get(),
            'currentStudents' => Student::where('session_programme_id', $selectedSessionId)->where('enrollment_status', 1)->get(),
            'dismissed'       => Student::where('session_programme_id', $selectedSessionId)->where('enrollment_status', 0)->get(),
            'verified'        => Student::where('session_programme_id', $selectedSessionId)->where('status', 'approved')->get(),
        ];

        $students = Student::where('session_programme_id', $selectedSessionId)->paginate(10);

        return view('students.intake_history.index', compact('stats', 'students'));
    }

    public function filterStudents(Request $request)
    {
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }
        // Get the type from the request, defaulting to 'totalEnrolled'
        $type = $request->get('type');
        if (! in_array($type, ['totalEnrolled', 'currentStudents', 'dismissed', 'verified'])) {
            $type = 'totalEnrolled'; // Default type
        }

        $query = Student::where('session_programme_id', $selectedSessionId);

        switch ($type) {
            case 'currentStudents':
                $query->where('enrollment_status', 1);
                break;

            case 'dismissed':
                $query->where('enrollment_status', 0);
                break;

            case 'verified':
                $query->where('status', 'approved');
                break;

            case 'totalEnrolled':
            default:
                // No additional filter needed
                break;
        }

        $students = $query->paginate(10);

        return response()->json([
            'students' => $students,
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
