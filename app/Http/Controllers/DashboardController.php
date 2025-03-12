<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SessionProgramme;
use App\Models\Company;
use App\Models\Student; 
use App\Models\Course;
use App\Models\Attendence;
use App\Models\Beat;
use App\Models\Patient;
use App\Models\Staff;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'check_active_session']);
    }

    public function index(Request $request)
    {
        // Check if a session ID has been submitted
        if (request()->has('session_id')) {
            // Store the selected session ID in the session
            session(['selected_session' => request()->session_id]);
        }
        // Get the selected session ID from the session
        $selectedSessionId = session('selected_session');
        $pending_message = session('pending_message');



        $query = Patient::query();

        // Filter by company_id if provided
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Filter by platoon if provided
        if ($request->filled('platoon')) {
            $query->where('platoon', $request->platoon);
        }

        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        // Count statistics based on the selected filters
        $dailyCount = (clone $query)->whereDate('created_at', $today)->count();
        $weeklyCount = (clone $query)->whereBetween('created_at', [$thisWeek, Carbon::now()])->count();
        $monthlyCount = (clone $query)->whereBetween('created_at', [$thisMonth, Carbon::now()])->count();

        // Fetch list of companies
        $companies = Company::all();

        // Patient distribution for the selected year (used in Pie Chart)
        $patientDistribution = (clone $query)
            ->whereBetween('created_at', [$thisYear, Carbon::now()])
            ->selectRaw('platoon, COUNT(*) as count')
            ->groupBy('platoon')
            ->pluck('count', 'platoon');





        if (auth()->user()->hasRole('Student')) {
            return view('dashboard.student_dashboard', compact('pending_message', 'selectedSessionId'));
        } else if (auth()->user()->hasRole('Receptionist|Doctor')){
            return view('dispensary.index', compact('dailyCount', 'weeklyCount', 'monthlyCount', 'patientDistribution', 'companies'));
        }else {
            $denttotalCount = Student::where('session_programme_id', $selectedSessionId)->count();
            $dentpresentCount = Student::where('session_programme_id', $selectedSessionId)->where('beat_status', 1)->count();
            $beats = Beat::where('date', Carbon::today()->toDateString())->get();
            $filteredBeats = $beats->filter(function ($beat) use ($selectedSessionId) {
                $studentIds = json_decode($beat->student_ids, true);
                return Student::whereIn('id', $studentIds)->where('session_programme_id', $selectedSessionId)->exists();
            });
            $totalStudentsInBeats = $filteredBeats->sum(function ($beat) {
                return count(json_decode($beat->student_ids, true));
            });
            $patientsCount = Patient::where('created_at', Carbon::today()->toDateString())->count('student_id');
            $staffsCount = Staff::count('forceNumber');
            $beatStudentPercentage = $denttotalCount > 0 ? ($totalStudentsInBeats / $denttotalCount) * 100 : 0;

            return view('dashboard.dashboard', compact('selectedSessionId', 'denttotalCount', 'dentpresentCount', 'totalStudentsInBeats', 'patientsCount', 'staffsCount', 'beatStudentPercentage'));
        }
    }

    public function getContent(Request $request)
    {
        $sessionProgrammeId = session('selected_session'); // Use session variable to get the selected session programme ID
        $denttotalCount = Student::where('session_programme_id', $sessionProgrammeId)->count();
        $dentpresentCount = Student::where('session_programme_id', $sessionProgrammeId)->where('beat_status', 1)->count();
        $beats = Beat::where('date', Carbon::today()->toDateString())->get();
        $filteredBeats = $beats->filter(function ($beat) use ($sessionProgrammeId) {
            $studentIds = json_decode($beat->student_ids, true);
            return Student::whereIn('id', $studentIds)->where('session_programme_id', $sessionProgrammeId)->exists();
        });
        $totalStudentsInBeats = $filteredBeats->sum(function ($beat) {
            return count(json_decode($beat->student_ids, true));
        });
        $patientsCount = Patient::where('created_at', Carbon::today()->toDateString())->count('student_id');
        $staffsCount = Staff::count('forceNumber');
        $beatStudentPercentage = $denttotalCount > 0 ? ($totalStudentsInBeats / $denttotalCount) * 100 : 0;

        return view('dashboard.partials.dashboard_content', compact('denttotalCount', 'dentpresentCount', 'totalStudentsInBeats', 'patientsCount', 'staffsCount', 'beatStudentPercentage'));
    }

    public function getData(Request $request)
    {
        $timeRange = $request->input('timeRange');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Default to today if no date range is selected
        if (!$startDate || !$endDate) {
            $startDate = Carbon::today()->toDateString();
            $endDate = Carbon::today()->toDateString();
        }

        // Calculate start and end dates based on time range
        switch ($timeRange) {
            case 'daily':
                $startDate = Carbon::today()->toDateString();
                $endDate = Carbon::today()->toDateString();
                break;
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek()->toDateString();
                $endDate = Carbon::now()->endOfWeek()->toDateString();
                break;
            case 'monthly':
                $startDate = Carbon::now()->startOfMonth()->toDateString();
                $endDate = Carbon::now()->endOfMonth()->toDateString();
                break;
        }

        // Fetch absents, sick, and locked up students data
        $absents = Attendence::where('session_programme_id', session('selected_session'))
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('absent');

        $sick = Patient::whereBetween('created_at', [$startDate, $endDate])
                    ->count();

        $lockedUp = Beat::whereBetween('date', [$startDate, $endDate])
                    ->sum('student_count');

        $labels = CarbonPeriod::create($startDate, '1 day', $endDate)->toArray();
        $labels = array_map(function($date) {
            return $date->format('Y-m-d');
        }, $labels);

        // Build the data response
        $data = [
            'labels' => $labels,
            'absents' => array_fill(0, count($labels), $absents),
            'sick' => array_fill(0, count($labels), $sick),
            'lockedUp' => array_fill(0, count($labels), $lockedUp),
        ];

        return response()->json($data);
    }


    public function indexold()
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
}
