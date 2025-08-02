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

    public function filterStudents_old(Request $request)
    {
        $selectedSessionId = session('selected_session') ?? 1;
        $type = $request->get('type');
        
        if (! in_array($type, ['totalEnrolled', 'currentStudents', 'dismissed', 'verified'])) {
            $type = 'totalEnrolled';
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
                // No extra filter
                break;
        }

        // 🎯 Apply dynamic filters

        if ($request->filled('entry_region')) {
            $query->where('entry_region', $request->entry_region);
        }

        if ($request->filled('study_level')) {
            $query->where('study_level_id', $request->study_level);
        }

        if ($request->filled('age_range')) {
            [$minAge, $maxAge] = explode('-', $request->age_range);
            $query->whereBetween('age', [(int) $minAge, (int) $maxAge]);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date
            ]);
        }

        // 🎨 Summary Data
        $summary = [
            'total'     => $query->count(),
            'active'    => (clone $query)->where('enrollment_status', 1)->count(),
            'dismissed' => (clone $query)->where('enrollment_status', 0)->count(),
            'verified'  => (clone $query)->where('status', 'approved')->count(),
        ];

        // 📋 Paginate Results
        $students = $query->paginate(10);

        return response()->json([
            'students' => $students,
            'summary' => $summary,
        ]);
    }

    public function filterStudents(Request $request)
{
    $selectedSessionId = session('selected_session') ?? 1;
    $type = $request->get('type');

    // 🧠 Normalize type
    if (! in_array($type, ['totalEnrolled', 'currentStudents', 'dismissed', 'verified'])) {
        $type = 'totalEnrolled';
    }

    // 🛠️ Base Query
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
            // No extra filter for total
            break;
    }

    // 🔍 Apply filters
    if ($request->filled('entry_region')) {
        $query->where('entry_region', $request->entry_region);
    }

    if ($request->filled('study_level')) {
        $query->where('study_level_id', $request->study_level);
    }

    if ($request->filled('age_range')) {
        [$minAge, $maxAge] = explode('-', $request->age_range);
        $query->whereBetween('age', [(int) $minAge, (int) $maxAge]);
    }

    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [
            $request->start_date,
            $request->end_date
        ]);
    }

    // 📊 Summary counts scoped to current type
    $baseQuery = clone $query;

    $summary = match ($type) {
        'currentStudents' => [
            'total'     => $baseQuery->count(),
            'active'    => $baseQuery->count(),
            'dismissed' => 0,
            'verified'  => (clone $baseQuery)->where('status', 'approved')->count(),
        ],
        'dismissed' => [
            'total'     => $baseQuery->count(),
            'active'    => 0,
            'dismissed' => $baseQuery->count(),
            'verified'  => (clone $baseQuery)->where('status', 'approved')->count(),
        ],
        'verified' => [
            'total'     => $baseQuery->count(),
            'active'    => (clone $baseQuery)->where('enrollment_status', 1)->count(),
            'dismissed' => (clone $baseQuery)->where('enrollment_status', 0)->count(),
            'verified'  => $baseQuery->count(),
        ],
        default => [
            'total'     => $baseQuery->count(),
            'active'    => (clone $baseQuery)->where('enrollment_status', 1)->count(),
            'dismissed' => (clone $baseQuery)->where('enrollment_status', 0)->count(),
            'verified'  => (clone $baseQuery)->where('status', 'approved')->count(),
        ],
    };

    // 📋 Paginated data
    $students = $query->paginate(10);

    return response()->json([
        'students' => $students,
        'summary'  => $summary,
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
