<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SessionProgramme;
use App\Models\Company;
use App\Models\Student;
use App\Models\Platoon;
use App\Models\Attendence;
use App\Models\Beat;
use App\Models\Patient;
use App\Models\Staff;
use App\Models\Announcement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private $selectedSessionId;
    public function __construct()
    {
        $this->selectedSessionId = session('selected_session');
        if (!$this->selectedSessionId)
            $this->selectedSessionId = 1;
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


        $recentAnnouncements = Announcement::where('expires_at', '>', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();






        if (auth()->user()->hasRole('Student')) {
            return view('dashboard.student_dashboard', compact('pending_message', 'selectedSessionId'));
        } else if (auth()->user()->hasRole('Receptionist|Doctor')) {
            return view('dispensary.index', compact('dailyCount', 'weeklyCount', 'monthlyCount', 'patientDistribution', 'companies'));
        } else {
            $todayStudentReport = $this->todayStudentReport();

            $denttotalCount = Student::where('session_programme_id', $selectedSessionId)->count();// $todayStudentReport['present'];
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
            $graphData = $this->getGraphData();
            return view('dashboard.dashboard', compact('selectedSessionId', 'denttotalCount', 'dentpresentCount', 'totalStudentsInBeats', 'patientsCount', 'staffsCount', 'beatStudentPercentage', 'recentAnnouncements', 'todayStudentReport', 'graphData'));
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
        $labels = array_map(function ($date) {
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


    private function todayStudentReport()
    {
        $present = 0;
        $absent = 0;
        $sick = 0;
        $lockUp = 0;

        $platoons = Platoon::all();

        foreach ($platoons as $platoon) {

            if (count($platoon->today_attendence) > 0) {
                $present += $platoon->today_attendence->get(0)->present + $platoon->today_attendence->get(0)->kazini + $platoon->today_attendence->get(0)->sentry + $platoon->today_attendence->get(0)->messy;
                $absent += $platoon->today_attendence->get(0)->absent;
                $sick += $platoon->today_attendence->get(0)->sick;
                $lockUp += $platoon->today_attendence->get(0)->lockUp;
            }
        }
        $total = Student::where('session_programme_id', $this->selectedSessionId)->count();
        $presentPercent = round(($present / ($total) * 100), 1);
        return [
            'present' => $present,
            'absent' => $absent,
            'sick' => $sick,
            'lockUp' => $lockUp,
            'presentPercent' => $presentPercent . '%'
        ];
    }
    public function getGraphData()
    {
        // Initialize arrays to store the data for each period
        $attendanceData = [
            'dates' => [],
            'absents' => [],
            'sick' => [],
            'lockUps' => [],
        ];

        $weeklyData = [
            'weeks' => [],
            'absents' => [],
            'sick' => [],
            'lockUps' => [],
        ];

        $monthlyData = [
            'months' => [],
            'absents' => [],
            'sick' => [],
            'lockUps' => [],
        ];

        // Get the last 7 days and initialize the arrays for daily data
        $lastSevenDays = collect();
        $dateKeys = []; // To map the dates directly to indices
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $lastSevenDays->push($date);
            $dateKeys[$date] = $i;
            $attendanceData['absents'][$i] = 0;
            $attendanceData['sick'][$i] = 0;
            $attendanceData['lockUps'][$i] = 0;
        }

        // Keep the dates in the order as is (latest comes last)
        $attendanceData['dates'] = $lastSevenDays->toArray();

        // Get the last 5 weeks and initialize the arrays for weekly data
        $lastFiveWeeks = collect();
        $weekKeys = []; // To map weeks to indices
        for ($i = 1; $i <= 5; $i++) {
            $startOfWeek = Carbon::now()->subWeeks($i - 1)->startOfWeek()->format('Y-m-d');
            $endOfWeek = Carbon::now()->subWeeks($i - 1)->endOfWeek()->format('Y-m-d');
            $weekKey = "Week {$i}: {$startOfWeek} - {$endOfWeek}";

            $lastFiveWeeks->push($weekKey);
            $weekKeys[$startOfWeek] = $i - 1;  // Use start of week as the key
            $weeklyData['weeks'] =[];
            $weeklyData['absents'][] = 0;
            $weeklyData['sick'][] = 0;
            $weeklyData['lockUps'][] = 0;
        }

        // Keep the weeks in order (most recent comes last)
        $weeklyData['weeks'] = $lastFiveWeeks->toArray();

        // Get the last 3 months and initialize the arrays for monthly data
        $lastThreeMonths = collect();
        $monthKeys = []; // To map months to indices
        for ($i = 0; $i < 3; $i++) {
            $monthName = Carbon::now()->subMonths($i)->format('F Y');
            $lastThreeMonths->push($monthName);
            $monthKeys[$monthName] = $i;

            $monthlyData['absents'][$i] = 0;
            $monthlyData['sick'][$i] = 0;
            $monthlyData['lockUps'][$i] = 0;
        }

        // Keep the months in order (most recent comes last)
        $monthlyData['months'] = $lastThreeMonths->toArray();

        // Retrieve all companies and their platoons
        $companies = Company::all();

        // Loop through each company and its platoons
        foreach ($companies as $company) {
            foreach ($company->platoons as $platoon) {
                // Get attendance records for the last 7 days
                $attendances = $platoon->attendences()
                    ->where('created_at', '>=', Carbon::now()->subDays(7))
                    ->get();

                // Process attendance data for the last 7 days
                foreach ($attendances as $attendance) {
                    $attendanceDate = Carbon::parse($attendance->created_at)->format('Y-m-d');
                    if (isset($dateKeys[$attendanceDate])) {
                        $index = $dateKeys[$attendanceDate];
                        $attendanceData['absents'][$index] += (int) $attendance->absent;
                        $attendanceData['sick'][$index] += (int) $attendance->sick;
                        $attendanceData['lockUps'][$index] += (int) $attendance->lockUp;
                    }

                    // For weekly data, check which week the attendance falls into
                    $attendanceWeek = Carbon::parse($attendance->created_at)->startOfWeek()->format('Y-m-d');
                    if (isset($weekKeys[$attendanceWeek])) {
                        $weekIndex = $weekKeys[$attendanceWeek];
                        $weeklyData['absents'][$weekIndex] += (int) $attendance->absent;
                        $weeklyData['sick'][$weekIndex] += (int) $attendance->sick;
                        $weeklyData['lockUps'][$weekIndex] += (int) $attendance->lockUp;
                    }

                    for($i = 0;  $i < count($attendanceData['dates']); $i++){
                        $attendanceData['dates'][$i] = Carbon::parse($attendanceData['dates'][$i])->format('d-m-Y');
                    }
                    // For monthly data, check which month the attendance falls into
                    $attendanceMonth = Carbon::parse($attendance->created_at)->format('F Y');
                    if (isset($monthKeys[$attendanceMonth])) {
                        $monthIndex = $monthKeys[$attendanceMonth];
                        $monthlyData['absents'][$monthIndex] += (int) $attendance->absent;
                        $monthlyData['sick'][$monthIndex] += (int) $attendance->sick;
                        $monthlyData['lockUps'][$monthIndex] += (int) $attendance->lockUp;
                    }
                }
            }
        }

        // Reverse the daily data arrays to ensure the most recent data comes last
        $attendanceData['absents'] = array_reverse($attendanceData['absents']);
        $attendanceData['sick'] = array_reverse($attendanceData['sick']);
        $attendanceData['lockUps'] = array_reverse($attendanceData['lockUps']);
        $attendanceData['dates'] = array_reverse($attendanceData['dates']);

        // Reverse the weekly data arrays to ensure the most recent data comes last
        $weeklyData['absents'] = array_reverse($weeklyData['absents']);
        $weeklyData['sick'] = array_reverse($weeklyData['sick']);
        $weeklyData['lockUps'] = array_reverse($weeklyData['lockUps']);
        $weeklyData['weeks'] = [];
        $weekKeys = array_flip($weekKeys);
        for ($i = count($weekKeys) - 1; $i >= 0; $i--) {
            // Use the getWeekNumber function to get the week number and push it to the 'weeks' array
            array_push($weeklyData['weeks'], "Week ".$this->getWeekNumber($weekKeys[$i]));
        }
        
        // Reverse the monthly data arrays to ensure the most recent data comes last
        $monthlyData['absents'] = array_reverse($monthlyData['absents']);
        $monthlyData['sick'] = array_reverse($monthlyData['sick']);
        $monthlyData['lockUps'] = array_reverse($monthlyData['lockUps']);
        $monthlyData['months'] = array_reverse($monthlyData['months']);

        // Combine all three sets of data into the final response
        return [
            'dailyData' => $attendanceData,
            'weeklyData' => $weeklyData,
            'monthlyData' => $monthlyData,
        ];
    }

    private function getWeekNumber($date){
        $sessionProgramme = SessionProgramme::find($this->selectedSessionId);
        // Define the specified start date (September 30, 2024)
        $startDate = Carbon::createFromFormat('d-m-Y', Carbon::parse($sessionProgramme->startDate)->format('d-m-Y'));
        //dd($date);
        // Define the target date for which you want to calculate the week number
        $date = Carbon::parse($date); // This could be the current date, or any specific date
        
        // Calculate the difference in weeks between the start date and the target date
        $weekNumber = $startDate->diffInWeeks($date) + 1;  // Adding 1 to make it 1-based (Week 1, Week 2, ...)
        return (int) $weekNumber;
        }


}
