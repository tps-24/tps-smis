<?php
namespace App\Http\Controllers;

use App\Models\Attendence;
use App\Models\Company;
use App\Models\LeaveRequest;
use App\Models\MPS;
use App\Models\MPSVisitor;
use App\Models\Patient;
use App\Models\SessionProgramme;
use App\Models\Student;
use App\Services\GraphDataService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{

    private $companies;
    private $selectedSessionId;
    protected $graphDataService;
    public function __construct(GraphDataService $graphDataService)
    {
        $this->graphDataService  = $graphDataService;
        $this->selectedSessionId = session('selected_session');
        if (! $this->selectedSessionId) {
            $this->selectedSessionId = 1;
        }
        $this->middleware('permission:report-list', ['only' => ['index','generateAttendanceReport','hospital','leaves','mps','generateHospitalReport','generateLeavesReport']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user  = Auth::user();
        $companies = collect();
        $selectedSessionId = $this->selectedSessionId;
        if($user->hasRole(['Sir Major','Instructor','OC Coy'])){
            $companies         = Company::where('id',$user->staff->company_id)->whereHas('students', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId);
         });
        }else{
            $companies         = Company::whereHas('students', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId);
        });
        }
        $companies = $companies->get();

        $data = $this->getAttendanceData($companies->pluck('id'));

        $dailyCounts = [
            'labels' => $data['dailyCounts']->pluck('date')->toArray(),
            'absent' => $data['dailyCounts']->pluck('total_absent')->map(fn($val) => (int) $val)->toArray(),
            'kazini' => $data['dailyCounts']->pluck('total_kazini')->map(fn($val) => (int) $val)->toArray(),
        ];

        $weeklyCounts = [
            'labels' => $data['weeklyCounts']->pluck('week_start')->toArray(),
            'absent' => $data['weeklyCounts']->pluck('total_absent')->map(fn($val) => (int) $val)->toArray(),
            'kazini' => $data['weeklyCounts']->pluck('total_kazini')->map(fn($val) => (int) $val)->toArray(),
        ];

        $monthlyCounts = [
            'labels' => $data['monthlyCounts']->pluck('month')->toArray(),
            'absent' => $data['monthlyCounts']->pluck('total_absent')->map(fn($val) => (int) $val)->toArray(),
            'kazini' => $data['monthlyCounts']->pluck('total_kazini')->map(fn($val) => (int) $val)->toArray(),
        ];
        $mostAbsentStudent = $data['mostAbsentStudent'];
        $graphData         = $this->graphDataService->getGraphData();
        return view('report.index', compact(
            'companies',
            'dailyCounts',
            'weeklyCounts',
            'monthlyCounts',
            'mostAbsentStudent',
            'graphData'
        ));
    }

    private function getAttendanceData($companyId)
    {
        //$companyId = [1]; // Or pass it in manually

        $rawData = DB::table('attendences')
            ->join('platoons', 'attendences.platoon_id', '=', 'platoons.id')
            ->select(
                DB::raw('DATE(attendences.created_at) as date'),
                DB::raw('SUM(attendences.absent) as total_absent'),
                DB::raw('SUM(attendences.kazini) as total_kazini')
            )
            ->whereIn('platoons.company_id', $companyId)
            ->where('attendences.created_at', '>=', Carbon::today()->subDays(6))
            ->groupBy(DB::raw('DATE(attendences.created_at)'))
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date'); // Key by date for easy merging

// Step 2: Generate last 7 days including today
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $dates->push($date);
        }

// Step 3: Merge data with zero fallback
        $dailyCounts = $dates->map(function ($date) use ($rawData) {
            return [
                'date'         => $date,
                'total_absent' => $rawData->has($date) ? $rawData[$date]->total_absent : 0,
                'total_kazini' => $rawData->has($date) ? $rawData[$date]->total_kazini : 0,
            ];
        });

        $rawData = DB::table('attendences')
            ->join('platoons', 'attendences.platoon_id', '=', 'platoons.id') // Join platoons table
            ->join('companies', 'platoons.company_id', '=', 'companies.id')  // Join companies table
            ->select(
                DB::raw('YEARWEEK(attendences.created_at, 1) as year_week'), // '1' means weeks start on Monday
                DB::raw('MIN(DATE(attendences.created_at)) as week_start'),
                DB::raw('SUM(attendences.absent) as total_absent'),
                DB::raw('SUM(attendences.kazini) as total_kazini'),
                'companies.name as company_name' // Add company name to selection
            )
            ->whereIn('companies.id', $companyId)                                              // Filter by company_id
            ->where('attendences.created_at', '>=', Carbon::now()->startOfWeek()->subWeeks(4)) // From 4 weeks ago
            ->groupBy(DB::raw('YEARWEEK(attendences.created_at, 1)'), 'companies.name')        // Group by week and company name
            ->orderBy('year_week', 'asc')                                                      // Order by year_week ascending
            ->get()
            ->keyBy('year_week'); // Key the

// Step 2: Generate last 5 weeks including current
        $weeks = collect();
        for ($i = 4; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->startOfWeek()->subWeeks($i);
            $yearWeek    = $startOfWeek->format('oW'); // ISO-8601 year + week number (e.g. 202520)
            $weeks->push([
                'year_week'  => $yearWeek,
                'week_start' => $startOfWeek->toDateString(),
            ]);
        }

// Step 3: Merge with raw data and fill missing with zero
        $weeklyCounts = $weeks->map(function ($week) use ($rawData) {
            $yearWeek = $week['year_week'];
            $record   = $rawData->get($yearWeek);

            return [
                'week_start'   => $week['week_start'],
                'year_week'    => $yearWeek,
                'total_absent' => $record ? $record->total_absent : 0,
                'total_kazini' => $record ? $record->total_kazini : 0,
            ];
        });

        $rawData = DB::table('attendences')
            ->join('platoons', 'attendences.platoon_id', '=', 'platoons.id') // Join platoons table
            ->join('companies', 'platoons.company_id', '=', 'companies.id')  // Join companies table
            ->select(
                DB::raw('YEAR(attendences.created_at) as year'),
                DB::raw('MONTH(attendences.created_at) as month'),
                DB::raw('SUM(attendences.absent) as total_absent'),
                DB::raw('SUM(attendences.kazini) as total_kazini'),
                'companies.name as company_name' // Add company name to selection
            )
            ->whereIn('companies.id', $companyId)                                                                          // Filter by company_id
            ->where('attendences.created_at', '>=', Carbon::now()->startOfMonth()->subMonths(2))                           // Last 3 months
            ->groupBy(DB::raw('YEAR(attendences.created_at)'), DB::raw('MONTH(attendences.created_at)'), 'companies.name') // Group by year, month, and company
            ->orderByRaw('YEAR(attendences.created_at), MONTH(attendences.created_at)')                                    // Order by year and month
            ->get()
            ->mapWithKeys(function ($item) {
                $key = $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT); // Format: YYYY-MM
                return [$key => $item];
            });

// Step 2: Generate last 3 months including current
        $months = collect();
        for ($i = 2; $i >= 0; $i--) {
            $date = Carbon::now()->startOfMonth()->subMonths($i);
            $key  = $date->format('Y-m');
            $months->push([
                'month_label' => $date->format('F Y'), // e.g. "May 2025"
                'year_month'  => $key,
            ]);
        }

// Step 3: Merge data and fill in zero values where needed
        $monthlyCounts = $months->map(function ($month) use ($rawData) {
            $key    = $month['year_month'];
            $record = $rawData->get($key);

            return [
                'month'        => $month['month_label'],
                'year_month'   => $key,
                'total_absent' => $record ? $record->total_absent : 0,
                'total_kazini' => $record ? $record->total_kazini : 0,
            ];
        });
// Return the 5-week attendance summary

        $selectedSessionId = session('selected_session') == '' ? 1 : session('selected_session');

        $selectedDate = Carbon::today(); //createFromFormat('d-m-Y', '12-5-2025');

        $totalAbsent = 0;
        $totalMps    = 0;
        $totalLeave  = 0;
        $totalSick   = 0;
        $companies   = Company::whereHas('students', function ($query) use ($selectedSessionId, $companyId) {
            $query->where('session_programme_id', $selectedSessionId)->whereIn('company_id', $companyId); // Filter students by session
        })
            ->with(['platoons.attendences' => function ($query) use ($selectedSessionId, $selectedDate) {
                // Filter attendances for the selected session and date
                $query->where('session_programme_id', $selectedSessionId)
                    ->whereDate('created_at', $selectedDate); // Filter by specific date
            }])
            ->get();

    // Step 1: Get all absent_student_ids values
        $records = DB::table('attendences')->pluck('absent_student_ids');

        $counts = [];

        foreach ($records as $rawValue) {
            $ids = json_decode($rawValue, true);

            if (! is_array($ids)) {
                continue;
            }

            foreach ($ids as $id) {
                if (is_numeric($id)) {
                    $id          = (int) $id;
                    $counts[$id] = ($counts[$id] ?? 0) + 1;
                }
            }
        }

// Step 2: Sort and take top 10
        arsort($counts);
        $top10 = array_slice($counts, 0, 10, true); // Keep keys (student IDs)

// Step 3: Fetch full Student models
        $studentIds = array_keys($top10);
        $students   = Student::whereIn('id', $studentIds)->get()->keyBy('id');

// Step 4: Build result with model and absence count
        $mostAbsentStudent = [];

        foreach ($top10 as $id => $absenceCount) {
            $mostAbsentStudent[] = [
                'student' => $students->get($id),
                'count'   => $absenceCount,
            ];
        }

        return [
            'dailyCounts'       => $dailyCounts,
            'weeklyCounts'      => $weeklyCounts,
            'monthlyCounts'     => $monthlyCounts,
            'mostAbsentStudent' => $mostAbsentStudent,
        ];

    }

    public function generateAttendanceReport(Request $request)
    {
        $company   = Company::find($request->company_id);
        $companyId = $company->id; // your specific company ID


        $attendances = Attendence::with('sessionProgramme') // eager load sessionProgramme relationship
            ->whereIn('platoon_id', function ($query) use ($companyId) {
                $query->select('id')
                    ->from('platoons')
                    ->where('company_id', $companyId);
            })
            ->whereDate('created_at', Carbon::today())
            ->get()
            ->groupBy('session_programme_id');

            if($attendances->isEmpty()){
                return redirect()->back()->with('info', 'No attendances recorded for today.');
            }
// Prepare an array to store session program attendance data
        $sessionProgrammeAttendance = [];
// Loop through the grouped attendance data
$sick_student_ids = [];
        foreach ($attendances as $sessionProgrammeId => $records) {
            $session = $records->first()->sessionProgramme; // Get the first record's sessionProgramme relationship

            // Store the grouped data in the array
            $sessionProgrammeAttendance[] = [
                'session_programme_id'     => $sessionProgrammeId,
                'session_programme'        => [
                    'id'             => $session->id,
                    'programme_name' => $session->session_programme_name, // Check field name for correct session program name
                    'year'           => $session->year,
                    'startDate'      => $session->startDate,
                    'endDate'        => $session->endDate,
                ],
                'total_attendance_records' => $records->count(),  // Get the total number of attendance records for this session programme
                'attendances'              => $records->values(), // Get all the attendance records for this session
            ];

        }
        return $sessionProgrammeAttendance;
        foreach($sessionProgrammeAttendance[0]['attendances'] as $attendance){
          $sick_student_ids =  array_merge(
                    $sick_student_ids,
                    json_decode($attendance->adm_student_ids) ?? [],
                    json_decode($attendance->ed_student_ids) ?? []
                );
            
        }
        $sick_students = Patient::where('company_id', $companyId)->whereIn('excuse_type_id', [3, 1])->whereIn('student_id', $sick_student_ids)->whereNull('released_at')->get();
        //return $sessionProgrammeAttendance;
        $pdf = PDF::loadView('report.attendancePdf', compact('company', 'sessionProgrammeAttendance', 'sick_students'));
        $pdf->set_option('margin_top', 10);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isPhpEnabled', true);
        return $pdf->stream("attendance_report.pdf");
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

    public function hospital()
    {

        $data = $this->getHospitalData();

        $dailyCounts          = $data['dailyCounts'];
        $weeklyCounts         = $data['weeklyCounts'];
        $monthlyCounts        = $data['monthlyCounts'];
        $mostOccurredStudents = $data['mostOccurredStudents'];

        $dailyCounts = [
            'labels' => array_column($dailyCounts, 'date'),
            'total'  => array_column($dailyCounts, 'total'),
            'ED'     => array_column($dailyCounts, 'ED'),
            'LD'     => array_column($dailyCounts, 'LD'),
            'Adm'    => array_column($dailyCounts, 'Adm'),
        ];

        $weeklyCounts = [
            'labels' => array_column($weeklyCounts, 'date'),
            'total'  => array_column($weeklyCounts, 'total'),
            'ED'     => array_column($weeklyCounts, 'ED'),
            'LD'     => array_column($weeklyCounts, 'LD'),
            'Adm'    => array_column($weeklyCounts, 'Adm'),
        ];

        $monthlyCounts = [
            'labels' => array_column($monthlyCounts, 'month'),
            'total'  => array_column($monthlyCounts, 'total'),
            'ED'     => array_column($monthlyCounts, 'ED'),
            'LD'     => array_column($monthlyCounts, 'LD'),
            'Adm'    => array_column($monthlyCounts, 'Adm'),
        ];

        $date = Carbon::today()->format('d F, Y');

        return view('report.hospital',
            compact(
                'date',
                'dailyCounts',
                'weeklyCounts',
                'monthlyCounts',
                'mostOccurredStudents'
            ));
    }

    public function leaves()
    {
        $data          = $this->getLeavesData();
        $leaveRequests = $data['leaveRequests'];
        $dailyCounts   = $data['dailyCounts'];

        $dailyCounts = [
            'labels'   => array_column($dailyCounts, 'date'),
            'returned' => array_column($dailyCounts, 'returned'),
            'on_leave' => array_column($dailyCounts, 'on_leave'),
            'late'     => array_column($dailyCounts, 'late'),
        ];
        $weeklyCounts = $data['weeklyCounts'];

        $weeklyCounts = [
            'labels'   => array_column($weeklyCounts, 'week_start_date'),
            'returned' => array_column($weeklyCounts, 'returned'),
            'on_leave' => array_column($weeklyCounts, 'on_leave'),
            'late'     => array_column($weeklyCounts, 'late'),
        ];
        $monthlyCounts = $data['monthlyCounts'];

        $monthlyCounts = [
            'labels'   => array_column($monthlyCounts, 'month_start_date'),
            'returned' => array_column($monthlyCounts, 'returned'),
            'on_leave' => array_column($monthlyCounts, 'on_leave'),
            'late'     => array_column($monthlyCounts, 'late'),
        ];
        return view('report.leaves', compact(
            'leaveRequests',
            'dailyCounts',
            'weeklyCounts',
            'monthlyCounts'
        ));
    }

    public function mps()
    {

        $data        = $this->getMPSData();
        $dailyCounts = [
            'labels'         => array_column($data['dailyCounts'], 'date'),
            'mps_counts'     => array_column($data['dailyCounts'], 'mps_count'),
            'visitor_counts' => array_column($data['dailyCounts'], 'visitor_count'),
        ];
        $weeklyCounts = [
            'labels'         => array_column($data['weeklyCounts'], 'week_start'),
            'mps_counts'     => array_column($data['weeklyCounts'], 'mps_count'),
            'visitor_counts' => array_column($data['weeklyCounts'], 'visitor_count'),
        ];
        $monthlyCounts = [
            'labels'         => array_column($data['monthlyCounts'], 'month_label'),
            'mps_counts'     => array_column($data['monthlyCounts'], 'mps_count'),
            'visitor_counts' => array_column($data['monthlyCounts'], 'visitor_count'),
        ];

        $currentLockedUpStudents = $data['currentLockedUpStudents'];
        $topLockedUpStudents     = $data['topLockedUpStudents'];
        $topVisitedStudents      = $data['topVisitedStudents'];

        return view('report.mps',
            compact(
                'currentLockedUpStudents',
                'topLockedUpStudents',
                'topVisitedStudents',
                'dailyCounts',
                'weeklyCounts',
                'monthlyCounts'
            ));
    }

    private function getWeekNumber($date)
    {
        $sessionProgramme = SessionProgramme::find($this->selectedSessionId);
        // Define the specified start date (September 30, 2024)
        $startDate = Carbon::createFromFormat('d-m-Y', Carbon::parse($sessionProgramme->startDate)->format('d-m-Y'));
                                      //dd($date);
                                      // Define the target date for which you want to calculate the week number
        $date = Carbon::parse($date); // This could be the current date, or any specific date

                                                          // Calculate the difference in weeks between the start date and the target date
        $weekNumber = $startDate->diffInWeeks($date) + 1; // Adding 1 to make it 1-based (Week 1, Week 2, ...)
        return (int) $weekNumber;
    }

    public function generateHospitalReport()
    {
        $data = $this->getHospitalData();

        $pdf = PDF::loadView('report.hospitalPdf', compact('data'));
        $pdf->set_option('margin_top', 10);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isPhpEnabled', true);

        return $pdf->stream("hospital_report.pdf");
    }

    public function generateLeavesReport()
    {
        $data = $this->getLeavesData();
        $pdf  = PDF::loadView('report.leavesPdf', compact('data'));
        $pdf->set_option('margin_top', 10);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isPhpEnabled', true);
        return $pdf->stream("leaves_report.pdf");
    }

    public function generateMPSReport()
    {
        $data = $this->getMPSData();
        //return $data['monthlyCounts'];
        $pdf = PDF::loadView('report.mpsPdf', compact('data'));
        $pdf->set_option('margin_top', 10);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isPhpEnabled', true);
        return $pdf->stream("leaves_report.pdf");
    }
    private function getHospitalData()
    {
        $user = Auth::user();
        $company_ids = [];
        if($user->hasRole(['Sir Major','Instructor','OC Coy'])){
            $company_ids = [$user->staff->company_id];
        }else{
            $company_ids = Company::pluck('id');
        }
        $selectedSessionId = session('selected_session') == '' ? 1 : session('selected_session');
        $sevenDaysAgo      = Carbon::today()->subDays(6); // 6 days ago + today = 7 days

        $rawCounts = Patient::selectRaw("
            DATE(created_at) as date,
            COUNT(*) as total,
            SUM(CASE WHEN excuse_type_id = 1 THEN 1 ELSE 0 END) as ED,
            SUM(CASE WHEN excuse_type_id = 2 THEN 1 ELSE 0 END) as LD,
            SUM(CASE WHEN excuse_type_id = 3 THEN 1 ELSE 0 END) as Adm
            ")
            ->whereHas('student', function ($query) use ($selectedSessionId,$company_ids) {
                $query->where('session_programme_id', $selectedSessionId)->whereIn('company_id', $company_ids);
            })
            ->whereIn('excuse_type_id', [1, 2, 3])
            ->whereDate('created_at', '>=', $sevenDaysAgo)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->date => [
                        'date'  => $item->date,
                        'total' => (int) $item->total,
                        'ED'    => (int) $item->ED,
                        'LD'    => (int) $item->LD,
                        'Adm'   => (int) $item->Adm,
                    ],
                ];
            })
            ->toArray();

        $weeklyCounts = [];
        for ($i = 0; $i < 7; $i++) {
            $date           = Carbon::today()->subDays(6 - $i)->toDateString(); // from oldest to newest
            $weeklyCounts[] = [
                "date"  => $date,
                "total" => 0,
                "ED"    => 0,
                "LD"    => 0,
                "Adm"   => 0,
            ];
        }
        $dailyCounts = array_map(function ($weeklyItem) use ($rawCounts) {
            $date = $weeklyItem['date'];

            return [
                'date'  => $date,
                'total' => $rawCounts[$date]['total'] ?? $weeklyItem['total'], // Use rawCounts if available, else default to 0
                'ED'    => $rawCounts[$date]['ED'] ?? $weeklyItem['ED'],
                'LD'    => $rawCounts[$date]['LD'] ?? $weeklyItem['LD'],
                'Adm'   => $rawCounts[$date]['Adm'] ?? $weeklyItem['Adm'],
            ];
        }, $weeklyCounts);
                                                      // Get the date 5 weeks ago
        $fiveWeeksAgo = Carbon::today()->subWeeks(5); // 5 weeks ago

        $rawCounts = Patient::selectRaw("
        DATE(created_at) as date,
        COUNT(*) as total,
        SUM(CASE WHEN excuse_type_id = 1 THEN 1 ELSE 0 END) as ED,
        SUM(CASE WHEN excuse_type_id = 2 THEN 1 ELSE 0 END) as LD,
        SUM(CASE WHEN excuse_type_id = 3 THEN 1 ELSE 0 END) as Adm
    ")
            ->whereHas('student', function ($query) use ($selectedSessionId ,$company_ids) {
                $query->where('session_programme_id', $selectedSessionId)->whereIn('company_id', $company_ids);
            })
            ->whereIn('excuse_type_id', [1, 2, 3])
            ->whereDate('created_at', '>=', $fiveWeeksAgo)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->date => [
                        'date'  => $item->date,
                        'total' => (int) $item->total,
                        'ED'    => (int) $item->ED,
                        'LD'    => (int) $item->LD,
                        'Adm'   => (int) $item->Adm,
                    ],
                ];
            })
            ->toArray();

// Generate the weekly count structure
        $weeklyCounts = [];
        for ($i = 0; $i < 5; $i++) {
            $date           = Carbon::today()->subWeeks(4 - $i)->startOfWeek()->toDateString(); // Week starting from 5 weeks ago
            $weeklyCounts[] = [
                "date"  => $date,
                "total" => 0,
                "ED"    => 0,
                "LD"    => 0,
                "Adm"   => 0,
            ];
        }

        $threeMonthsAgo = Carbon::now()->startOfMonth()->subMonths(2); // Includes current + 2 previous

        $rawCounts = Patient::selectRaw("
        DATE_FORMAT(created_at, '%Y-%m') as month_key,
        COUNT(*) as total,
        SUM(CASE WHEN excuse_type_id = 1 THEN 1 ELSE 0 END) as ED,
        SUM(CASE WHEN excuse_type_id = 2 THEN 1 ELSE 0 END) as LD,
        SUM(CASE WHEN excuse_type_id = 3 THEN 1 ELSE 0 END) as Adm
    ")
            ->whereHas('student', function ($query) use ($selectedSessionId, $company_ids) {
                $query->where('session_programme_id', $selectedSessionId)->whereIn('company_id', $company_ids);
            })
            ->whereIn('excuse_type_id', [1, 2, 3])
            ->whereDate('created_at', '>=', $threeMonthsAgo)
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy('month_key')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->month_key => [
                        'month' => $item->month_key,
                        'total' => (int) $item->total,
                        'ED'    => (int) $item->ED,
                        'LD'    => (int) $item->LD,
                        'Adm'   => (int) $item->Adm,
                    ],
                ];
            })
            ->toArray();

        $monthlyCounts = [];
        for ($i = 0; $i < 3; $i++) {
            $month = Carbon::now()->startOfMonth()->subMonths(2 - $i)->format('Y-m');

            $monthlyCounts[] = [
                'month' => $month,
                'total' => 0,
                'ED'    => 0,
                'LD'    => 0,
                'Adm'   => 0,
            ];
        }

        $monthlyCounts = array_map(function ($monthItem) use ($rawCounts) {
            $month = $monthItem['month'];
            return [
                'month' => Carbon::parse($month)->format('F Y'),
                'total' => $rawCounts[$month]['total'] ?? 0,
                'ED'    => $rawCounts[$month]['ED'] ?? 0,
                'LD'    => $rawCounts[$month]['LD'] ?? 0,
                'Adm'   => $rawCounts[$month]['Adm'] ?? 0,
            ];
        }, $monthlyCounts);

        $mostOccurredStudents = Patient::select('student_id', DB::raw('COUNT(*) as occurrence_count'))
            ->whereHas('student', function ($query) use ($selectedSessionId, $company_ids)  {
                $query->where('session_programme_id', $selectedSessionId) ->whereIn('company_id', $company_ids);
            })
           
            ->groupBy('student_id')           // Group by student_id to count occurrences
            ->orderByDesc('occurrence_count') // Sort by occurrence count in descending order
            ->limit(10)                       // Limit to the top 10 most occurred students
            ->get()
            ->map(function ($item) {
                                           // Retrieve the student associated with the patient by student_id
                $student = $item->student; // Assumes a relationship named 'student' in the Patient model

                return [
                    'student_id' => $student->id,            // Access student ID
                    'student'    => $student,                // Access student's name or any other student details
                    'count'      => $item->occurrence_count, // The number of occurrences
                ];
            });
        return [
            'mostOccurredStudents' => $mostOccurredStudents,
            'dailyCounts'          => $dailyCounts,
            'weeklyCounts'         => $weeklyCounts,
            'monthlyCounts'        => $monthlyCounts,
        ];
    }

    private function getLeavesData()
    {
        $user = Auth::user();
        $company_ids = [];
        if($user->hasRole(['Sir Major','Instructor','OC Coy'])){
            $company_ids = [$user->staff->company_id];
        }else{
            $company_ids = Company::pluck('id');
        }
        $selectedSessionId = session('selected_session') == '' ? 1 : session('selected_session');

        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();

        $rawCounts = LeaveRequest::whereDate('created_at', '>=', $sevenDaysAgo)
            ->selectRaw("
        DATE(created_at) AS date,
        COUNT(CASE WHEN return_date IS NOT NULL AND return_date < end_date THEN 1 END) AS returned,
        COUNT(CASE WHEN return_date IS NULL AND end_date > NOW() THEN 1 END) AS on_leave,
        COUNT(CASE WHEN return_date IS NULL AND end_date <= NOW() THEN 1 END) AS late
        ")
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->whereIn('company_id', $company_ids)
            ->keyBy('date') // <-- group result by date for easier mapping
            ->toArray();

        // Step 2: Initialize daily counts with default values
        $dailyCounts = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->subDays(6 - $i)->toDateString();

            $raw = $rawCounts[$date] ?? null;

            $dailyCounts[] = [
                'date'     => $date,
                'returned' => $raw['returned'] ?? 0,
                'on_leave' => $raw['on_leave'] ?? 0,
                'late'     => $raw['late'] ?? 0,
            ];
        }

// Step 1: Determine start of current week and build weekly range
        $startOfCurrentWeek = Carbon::now()->startOfWeek(); // Monday
        $weeksAgo           = 4;                            // This week + 4 previous = 5 weeks

        $weeklyRange = [];
        for ($i = $weeksAgo; $i >= 0; $i--) {
            $weeklyRange[] = $startOfCurrentWeek->copy()->subWeeks($i)->startOfWeek(); // Always Monday
        }

// Step 2: Query the DB for counts within the 5-week window
        $startDate = $startOfCurrentWeek->copy()->subWeeks($weeksAgo)->startOfWeek(); // 4 weeks ago Monday
        $endDate   = Carbon::now()->endOfWeek();                                      // End of current week

        $rawCounts = LeaveRequest::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("
        YEARWEEK(created_at, 1) AS year_week, -- mode 1 = weeks start on Monday
        MIN(DATE(created_at)) as week_start_date, -- not reliable for empty weeks, used just as backup
        COUNT(CASE WHEN return_date IS NOT NULL AND return_date < end_date THEN 1 END) AS returned,
        COUNT(CASE WHEN return_date IS NULL AND end_date > NOW() THEN 1 END) AS on_leave,
        COUNT(CASE WHEN return_date IS NULL AND end_date <= NOW() THEN 1 END) AS late
    ")
            ->groupBy(DB::raw("YEARWEEK(created_at, 1)"))
            ->orderBy('year_week')
            ->get()
            ->keyBy('year_week');

        $weeklyCounts = [];

        foreach ($weeklyRange as $startOfWeek) {
            $weekKey       = $startOfWeek->format('oW'); // 'o' = ISO year, 'W' = ISO week number (matches YEARWEEK(..., 1))
            $weekStartDate = $startOfWeek->toDateString();

            $raw = $rawCounts[$weekKey] ?? null;

            $weeklyCounts[] = [
                'week_start_date' => $this->getWeekNumber($weekStartDate) . ' Week',
                'returned'        => $raw->returned ?? 0,
                'on_leave'        => $raw->on_leave ?? 0,
                'late'            => $raw->late ?? 0,
            ];
        }

// Setup dates
        $today               = Carbon::today();
        $startOfCurrentMonth = $today->copy()->startOfMonth();
        $monthsAgo           = 2;

        $monthlyRange = [];
        for ($i = $monthsAgo; $i >= 0; $i--) {
            $monthlyRange[] = $startOfCurrentMonth->copy()->subMonths($i)->startOfMonth();
        }

// Fetch counts from DB
        $rawCounts = LeaveRequest::whereBetween('created_at', [
            $startOfCurrentMonth->copy()->subMonths($monthsAgo),
            $today->endOfDay(),
        ])
            ->selectRaw("
        DATE_FORMAT(created_at, '%Y-%m-01') AS month_start_date,
        COUNT(CASE WHEN return_date IS NOT NULL AND return_date < end_date THEN 1 END) AS returned,
        COUNT(CASE WHEN return_date IS NULL AND end_date > NOW() THEN 1 END) AS on_leave,
        COUNT(CASE WHEN return_date IS NULL AND end_date <= NOW() THEN 1 END) AS late
    ")
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-01')"))
            ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-01')"))
            ->whereIn('company_id', $company_ids)
            ->get()
            ->keyBy('month_start_date');

// Initialize structure with all months
        $monthlyCounts = [];
        foreach ($monthlyRange as $startOfMonth) {
            $startOfMonthDate = $startOfMonth->toDateString();

            $raw = $rawCounts[$startOfMonthDate] ?? null;

            $monthlyCounts[] = [
                'month_start_date' => Carbon::parse($startOfMonthDate)->format('F Y'),
                'returned'         => $raw->returned ?? 0,
                'on_leave'         => $raw->on_leave ?? 0,
                'late'             => $raw->late ?? 0,
            ];
        }

        $leaveRequests = LeaveRequest::select('student_id', DB::raw('COUNT(*) as occurrence_count'))
            ->whereHas('student', function ($query) use ($selectedSessionId, $company_ids) {
                $query->where('session_programme_id', $selectedSessionId)->whereIn('company_id', $company_ids);
            })
            ->groupBy('student_id')           // Group by student_id to count occurrences
            ->orderByDesc('occurrence_count') // Sort by occurrence count in descending order
            ->limit(10)                       // Limit to the top 10 most occurred students
            ->get()
            ->map(function ($item) {
                                           // Retrieve the student associated with the patient by student_id
                $student = $item->student; // Assumes a relationship named 'student' in the Patient model

                return [
                    'student_id' => $student->id,            // Access student ID
                    'student'    => $student,                // Access student's name or any other student details
                    'count'      => $item->occurrence_count, // The number of occurrences
                ];
            });
        return [
            'leaveRequests' => $leaveRequests,
            'dailyCounts'   => $dailyCounts,
            'weeklyCounts'  => $weeklyCounts,
            'monthlyCounts' => $monthlyCounts,
        ];
    }

    private function getMPSData()
    {
        $sevenDaysAgo = Carbon::now()->subDays(6)->toDateString();
                $user = Auth::user();
        $company_ids = [];
        if($user->hasRole(['Sir Major','Instructor','OC Coy'])){
            $company_ids = [$user->staff->company_id];
        }else{
            $company_ids = Company::pluck('id');
        }
        // Fetch daily counts from both tables

$mpsCounts = DB::table('m_p_s as mps')
    ->join('students as s', 'mps.student_id', '=', 's.id')
    ->selectRaw('DATE(mps.arrested_at) as date, COUNT(DISTINCT mps.student_id) as mps_count')
    ->where('mps.arrested_at', '>=', $sevenDaysAgo)
    ->whereIn('s.company_id', $company_ids)
    ->groupByRaw('DATE(mps.arrested_at)')
    ->orderByRaw('DATE(mps.arrested_at)')
    ->get()
    ->keyBy('date');


$mpsVisitorCounts = DB::table('m_p_s_visitors as v')
    ->join('students as s', 'v.student_id', '=', 's.id')
    ->selectRaw('DATE(v.visited_at) as date, COUNT(DISTINCT v.student_id) as visitor_count')
    ->where('v.visited_at', '>=', $sevenDaysAgo)
    ->whereIn('s.company_id', $company_ids)
    ->groupByRaw('DATE(v.visited_at)')
    ->orderByRaw('DATE(v.visited_at)')
    ->get()
    ->keyBy('date');

        // Merge counts from both tables
        $dailyCounts = [];
        for ($i = 0; $i < 7; $i++) {
            $date          = Carbon::now()->subDays(6 - $i)->toDateString();
            $dailyCounts[] = [
                'date'          => $date,
                'mps_count'     => $mpsCounts[$date]->mps_count ?? 0,
                'visitor_count' => $mpsVisitorCounts[$date]->visitor_count ?? 0,
            ];
        }

        // ---------- WEEKLY COUNTS: LAST 5 WEEKS ----------
        $startOfFiveWeeksAgo = Carbon::now()->startOfWeek()->subWeeks(4);

        // Fetch weekly counts from both tables
        $weeklyRaw = DB::table('m_p_s')
            ->selectRaw("YEARWEEK(arrested_at, 1) as year_week, COUNT(DISTINCT student_id) as mps_count")
            ->whereDate('arrested_at', '>=', $startOfFiveWeeksAgo->toDateString())
            ->groupBy(DB::raw('YEARWEEK(arrested_at, 1)'))
            ->orderBy(DB::raw('YEARWEEK(arrested_at, 1)'))
            ->get()
            ->keyBy('year_week');

        $weeklyVisitorRaw = DB::table('m_p_s_visitors') // Changed table name here
            ->selectRaw("YEARWEEK(visited_at, 1) as year_week, COUNT(DISTINCT student_id) as visitor_count")
            ->whereDate('visited_at', '>=', $startOfFiveWeeksAgo->toDateString())
            ->groupBy(DB::raw('YEARWEEK(visited_at, 1)'))
            ->orderBy(DB::raw('YEARWEEK(visited_at, 1)'))
            ->get()
            ->keyBy('year_week');

        // Merge counts from both tables
        $weeklyCounts = [];
        for ($i = 0; $i < 5; $i++) {
            $weekStart   = Carbon::now()->startOfWeek()->subWeeks(4 - $i);
            $yearWeekKey = intval($weekStart->format('oW')); // ISO week key
            $label       = 'Week of ' . $weekStart->format('M d');

            $weeklyCounts[] = [
                'week_start'    => $label,
                'mps_count'     => $weeklyRaw[$yearWeekKey]->mps_count ?? 0,
                'visitor_count' => $weeklyVisitorRaw[$yearWeekKey]->visitor_count ?? 0,
            ];
        }

        // ---------- MONTHLY COUNTS: LAST 3 MONTHS ----------
        $startOfThreeMonthsAgo = Carbon::now()->startOfMonth()->subMonths(2);

        // Fetch monthly counts from both tables
        $monthlyRaw = DB::table('m_p_s')
            ->selectRaw('YEAR(arrested_at) as year, MONTH(arrested_at) as month, COUNT(DISTINCT student_id) as mps_count')
            ->whereDate('arrested_at', '>=', $startOfThreeMonthsAgo->toDateString())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                $key = sprintf('%04d-%02d', $item->year, $item->month);
                return [$key => $item->mps_count];
            });

$monthlyRaw = DB::table('m_p_s as mps')
    ->join('students as s', 'mps.student_id', '=', 's.id')
    ->selectRaw('YEAR(mps.arrested_at) as year, MONTH(mps.arrested_at) as month, COUNT(DISTINCT mps.student_id) as mps_count')
    ->whereDate('mps.arrested_at', '>=', $startOfThreeMonthsAgo->toDateString())
    ->whereIn('s.company_id', $company_ids) // Filter by one or more company IDs
    ->groupByRaw('YEAR(mps.arrested_at), MONTH(mps.arrested_at)')
    ->orderByRaw('YEAR(mps.arrested_at), MONTH(mps.arrested_at)')
    ->get()
    ->mapWithKeys(function ($item) {
        $key = sprintf('%04d-%02d', $item->year, $item->month);
        return [$key => $item->mps_count];
    });


        // Merge counts from both tables
        $monthlyCounts = [];
        for ($i = 0; $i < 3; $i++) {
            $month    = Carbon::now()->startOfMonth()->subMonths(2 - $i);
            $monthKey = $month->format('Y-m');

            $monthlyCounts[] = [
                'month_label'   => $month->format('F Y'),
                'mps_count'     => $monthlyRaw[$monthKey] ?? 0,
                'visitor_count' => $monthlyVisitorRaw[$monthKey] ?? 0,
            ];
        }

$currentLockedUpStudents = Mps::whereNull('released_at')
    ->whereHas('student', function($query) use ($company_ids) {
        $query->whereIn('company_id', $company_ids);
    })
    ->with('student')
    ->get();


$topLockedUpStudents = Mps::select(
        'student_id',
        DB::raw('COUNT(*) as count'),
        'students.first_name',
        'students.last_name',
        'students.company_id'
    )
    ->join('students', 'm_p_s.student_id', '=', 'students.id')
    ->whereIn('students.company_id', $company_ids)
    ->groupBy('student_id', 'students.first_name', 'students.last_name', 'students.company_id')
    ->orderByDesc('count')
    ->take(10)
    ->get();

$topVisitedStudents = MpsVisitor::select(
        'student_id',
        DB::raw('COUNT(*) as count'),
        'students.first_name',
        'students.last_name',
        'students.company_id'
    )
    ->join('students', 'm_p_s_visitors.student_id', '=', 'students.id')
    ->whereIn('students.company_id', $company_ids)
    ->groupBy('student_id', 'students.first_name', 'students.last_name', 'students.company_id')
    ->orderByDesc('count')
    ->take(10)
    ->get();

        return [
            'dailyCounts'             => $dailyCounts,
            'weeklyCounts'            => $weeklyCounts,
            'monthlyCounts'           => $monthlyCounts,
            'currentLockedUpStudents' => $currentLockedUpStudents,
            'topLockedUpStudents'     => $topLockedUpStudents,
            'topVisitedStudents'      => $topVisitedStudents,
        ];
    }
}
