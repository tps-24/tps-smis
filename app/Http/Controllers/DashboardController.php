<?php
namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Attendence;
use App\Models\Beat;
use App\Models\Company;
use App\Models\Patient;
use App\Models\Platoon;
use App\Models\SessionProgramme;
use App\Models\Staff;
use App\Models\Student;
use App\Services\GraphDataService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private $selectedSessionId;
    protected $graphDataService;
    public function __construct(GraphDataService $graphDataService)
    {
        $this->graphDataService  = $graphDataService;
        $this->selectedSessionId = session('selected_session');
        if (! $this->selectedSessionId) {
            $this->selectedSessionId = 1;
        }

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
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }
        $pending_message   = session('pending_message');

        $dailyCount = $weeklyCount = $monthlyCount = 0;
        $user       = Auth::user();
        $companies  = [];
        if ($user->hasRole(['Sir Major', 'OC Coy', 'Instructor'])) {
            $companies = [$user->staff->company];
        } else {
            $companies = Company::all();

        }

        $companyId = $request->input('company_id');
        $platoon   = $request->input('platoon');
        if ($companyId) {
            $companies = [Company::find($companyId)];
        }

        foreach ($companies as $company) {
            $admittedNotReleasedCount = $company->sickStudents->where('excuse_type_id', 3)->whereNull('is_discharged')->pluck('id');
            $daily                    = $company->sickStudents()->whereDate('created_at', Carbon::today())->pluck('id');
            $dailyCount += $admittedNotReleasedCount->merge($daily)->unique()->count();

            $weekly = $company->sickStudents()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->pluck('id');
            $weeklyCount += $admittedNotReleasedCount->merge($weekly)->unique()->count();

            $monthly = $company->sickStudents()->whereMonth('created_at', now()->month)->pluck('id');
            $monthlyCount += $admittedNotReleasedCount->merge($monthly)->unique()->count();
        }

        $today     = Carbon::today();
        $thisWeek  = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear  = Carbon::now()->startOfYear();

        // Count statistics based on the selected filters
        // $dailyCount   = (clone $query)->whereDate('created_at', $today)->count();
        // $weeklyCount  = (clone $query)->whereBetween('created_at', [$thisWeek, Carbon::now()])->count();
        // $monthlyCount = (clone $query)->whereBetween('created_at', [$thisMonth, Carbon::now()])->count();

        // // Fetch list of companies
        // $companies = Company::all();

        // // Patient distribution for the selected year (used in Pie Chart)
        // $patientDistribution = (clone $query)
        //     ->whereBetween('created_at', [$thisMonth, Carbon::now()])
        //     ->selectRaw('company_id, COUNT(*) as count')
        //     ->groupBy('company_id')
        //     ->pluck('count', 'company_id');
        if ($companyId) {
            // If a company is selected, show platoon-based statistics
            $patientDistribution = Patient::where('company_id', $companyId)
                ->selectRaw('platoon, COUNT(*) as count')
                ->groupBy('platoon')
                ->whereMonth('created_at', now()->month)
                ->pluck('count', 'platoon');

            $isCompanySelected = true;
        } else {
            // Default: Show statistics grouped by company (HQ, A, B, C)
            if ($user->hasRole(['Sir Major', 'OC Coy', 'Instructor'])) {
                $patientDistribution = Patient::selectRaw('platoon, COUNT(*) as count')
                    ->whereMonth('created_at', now()->month)
                    ->groupBy('platoon')
                    ->pluck('count', 'platoon');
                $isCompanySelected = true;

            } else {
                $patientDistribution = Patient::selectRaw('company_id, COUNT(*) as count')
                    ->groupBy('company_id')
                    ->whereMonth('created_at', now()->month)
                    ->pluck('count', 'company_id');
                $isCompanySelected = false;
            }

        }
        $months = [];

        for ($i = 2; $i >= 0; $i--) {
            $months[] = Carbon::now()->subMonths($i)->format('F Y'); // e.g. "April 2025"
        }

        $recentAnnouncements = Announcement::where('expires_at', '>', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        if (auth()->user()->hasRole('Student')) {
            return view('dashboard.student_dashboard', compact('pending_message', 'selectedSessionId'));
        } else if (auth()->user()->hasRole('Receptionist|Doctor')) {
            return view('dispensary.index', compact('dailyCount', 'weeklyCount', 'monthlyCount', 'patientDistribution', 'companies', 'months'));
        } else {
            $todayStudentReport = $this->todayStudentReport();

            $denttotalCount   = Student::where('session_programme_id', $selectedSessionId ?? 1)->count();
            $dentpresentCount = Student::where('session_programme_id', $selectedSessionId)->where('beat_status', 1)->count();
            $beats            = Beat::where('date', Carbon::today()->toDateString())->get();
            $filteredBeats    = $beats->filter(function ($beat) use ($selectedSessionId) {
                $studentIds = json_decode($beat->student_ids, true);
                return Student::whereIn('id', $studentIds)->where('session_programme_id', $selectedSessionId ?? 1)->exists();
            });
            $totalStudentsInBeats = $filteredBeats->sum(function ($beat) {
                return count(json_decode($beat->student_ids, true));
            });
            // $patientsCount = Patient::where('created_at', Carbon::today()->toDateString())->count('student_id');

            // $patientsCount = Patient::whereDate('created_at', Carbon::today())->count();

            $patientsCount = Patient::where(function ($query) {
                $query->where('excuse_type_id', 1)                
                    ->whereRaw('DATE_ADD(created_at, INTERVAL rest_days DAY) >= ?', [Carbon::today()])
                    ->orWhere(function ($innerQuery) {
                        $innerQuery->where('excuse_type_id', 3)
                            ->whereNull('released_at');
                    });
            })
                ->whereHas('student', function ($q) use ($selectedSessionId) {
                    $q->where('session_programme_id', $selectedSessionId);
                })
                ->count();

            $staffsCount           = Staff::count('forceNumber');
            $beatStudentPercentage = $denttotalCount > 0 ? ($totalStudentsInBeats / $denttotalCount) * 100 : 0;
            $graphData             = $this->graphDataService->getGraphData();
            return view('dashboard.dashboard', compact(
                'selectedSessionId', 'denttotalCount',
                'dentpresentCount', 'totalStudentsInBeats',
                'patientsCount', 'staffsCount', 'beatStudentPercentage',
                'recentAnnouncements', 'todayStudentReport',
                'graphData'));
        }
    }

    public function getContent(Request $request)
    {
        $sessionProgrammeId = session('selected_session'); // Use session variable to get the selected session programme ID
        $denttotalCount     = Student::where('session_programme_id', $sessionProgrammeId)->count();
        $dentpresentCount   = Student::where('session_programme_id', $sessionProgrammeId)->where('beat_status', 1)->count();
        $beats              = Beat::where('date', Carbon::today()->toDateString())->get();
        $filteredBeats      = $beats->filter(function ($beat) use ($sessionProgrammeId) {
            $studentIds = json_decode($beat->student_ids, true);
            return Student::whereIn('id', $studentIds)->where('session_programme_id', $sessionProgrammeId)->exists();
        });
        $totalStudentsInBeats = $filteredBeats->sum(function ($beat) {
            return count(json_decode($beat->student_ids, true));
        });
        // $patientsCount = Patient::where('created_at', Carbon::today()->toDateString())->count('student_id');

        $patientsCount = Patient::where(function ($query) {
            $query->where('excuse_type_id', 1)
                ->whereRaw('DATE_ADD(created_at, INTERVAL rest_days DAY) >= ?', [Carbon::today()])
                ->orWhere(function ($innerQuery) {
                    $innerQuery->where('excuse_type_id', 3)
                        ->whereNull('released_at'); // Checks for released_at = NULL when excuse_type_id = 3
                });
        })->count();
        $staffsCount           = Staff::count('forceNumber');
        $beatStudentPercentage = $denttotalCount > 0 ? ($totalStudentsInBeats / $denttotalCount) * 100 : 0;

        return view('dashboard.partials.dashboard_content', compact('denttotalCount', 'dentpresentCount', 'totalStudentsInBeats', 'patientsCount', 'staffsCount', 'beatStudentPercentage'));
    }

    public function getData(Request $request)
    {
        $timeRange = $request->input('timeRange');
        $startDate = $request->input('startDate');
        $endDate   = $request->input('endDate');

        // Default to today if no date range is selected
        if (! $startDate || ! $endDate) {
            $startDate = Carbon::today()->toDateString();
            $endDate   = Carbon::today()->toDateString();
        }

        // Calculate start and end dates based on time range
        switch ($timeRange) {
            case 'daily':
                $startDate = Carbon::today()->toDateString();
                $endDate   = Carbon::today()->toDateString();
                break;
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek()->toDateString();
                $endDate   = Carbon::now()->endOfWeek()->toDateString();
                break;
            case 'monthly':
                $startDate = Carbon::now()->startOfMonth()->toDateString();
                $endDate   = Carbon::now()->endOfMonth()->toDateString();
                break;
        }

        // Fetch absents, sick, and locked up students data
        $absents = Attendence::where('session_programme_id', session('selected_session'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('absent');

        // $sick = Patient::whereBetween('created_at', [$startDate, $endDate])->count();
        // $sick = Patient::where('created_at', Carbon::today()->toDateString())->count();
        // $sick = Patient::whereDate('created_at', Carbon::today())
        //                     ->where(function ($query) {
        //                         $query->where('excuse_type_id', 1)
        //                             ->orWhere('excuse_type_id', 3);
        //                     })
        //                     ->count();

        $sick = Patient::where(function ($query) {
            $query->where('excuse_type_id', 1)
                ->whereRaw('DATE_ADD(created_at, INTERVAL rest_days DAY) >= ?', [Carbon::today()])
                ->orWhere(function ($innerQuery) {
                    $innerQuery->where('excuse_type_id', 3)
                        ->whereNull('released_at'); // Checks for released_at = NULL when excuse_type_id = 3
                });
        })->count();

        // $lockedUp = Beat::whereBetween('date', [$startDate, $endDate])
        //     ->sum('student_count');

        $labels = CarbonPeriod::create($startDate, '1 day', $endDate)->toArray();
        $labels = array_map(function ($date) {
            return $date->format('Y-m-d');
        }, $labels);

        // Build the data response
        $data = [
            'labels'   => $labels,
            'absents'  => array_fill(0, count($labels), $absents),
            'sick'     => array_fill(0, count($labels), $sick),
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
                $previousWeek       = $weeklyAttendance[$index - 1];
                $weeklyComparison[] = [
                    'year'               => $week->year,
                    'week'               => $week->week,
                    'present_difference' => $week->total_present - $previousWeek->total_present,
                    'absent_difference'  => $week->total_absent - $previousWeek->total_absent,
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
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }

        $present = 0;
        $absent  = 0;
        $sick    = 0;
        $lockUp  = 0;

        $platoons = Platoon::all();

        foreach ($platoons as $platoon) {

            if (count($platoon->today_attendence) > 0) {
                $present += $platoon->today_attendence->get(0)->present + $platoon->today_attendence->get(0)->kazini + $platoon->today_attendence->get(0)->sentry + $platoon->today_attendence->get(0)->messy;
                $absent += $platoon->today_attendence->get(0)->absent;
                $sick += $platoon->today_attendence->get(0)->sick;
                $lockUp += $platoon->today_attendence->get(0)->lockUp;
            }
        }
        $total          = Student::where('session_programme_id', $selectedSessionId)->count();
        $presentPercent = $total == 0 ? 0 : round(($present / ($total) * 100), 1);
        return [
            'present'        => $present,
            'absent'         => $absent,
            'sick'           => $sick,
            'lockUp'         => $lockUp,
            'presentPercent' => $presentPercent . '%',
        ];
    }

}
