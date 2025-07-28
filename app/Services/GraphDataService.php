<?php
namespace App\Services;

use App\Models\Attendence;
use App\Models\Company;
use App\Models\LeaveRequest;
use App\Models\MPS;
use App\Models\Patient;
use App\Models\SessionProgramme;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class GraphDataService
{
    private $selectedSessionId;

    public function getGraphData($start_date = null, $end_date = null)
    {
        $selectedSessionId = session('selected_session') ?: 1;

        // Handle date range
        $today        = Carbon::today();
        $defaultStart = $today->copy()->subDays(6)->startOfDay(); // last 7 days
        $defaultEnd   = $today->copy()->endOfDay();

        $start = $start_date ? Carbon::parse($start_date)->startOfDay() : $defaultStart;
        $end   = $end_date ? Carbon::parse($end_date)->endOfDay() : $defaultEnd;

        // Initialize arrays to store the data for each period
        $attendanceData = [
            'dates'   => [],
            'absents' => [],
            'sick'    => [],
            'lockUps' => [],
            'leaves'  => [],
        ];

        $weeklyData = [
            'weeks'   => [],
            'absents' => [],
            'sick'    => [],
            'lockUps' => [],
            'leaves'  => [],
        ];

        $monthlyData = [
            'months'  => [],
            'absents' => [],
            'sick'    => [],
            'lockUps' => [],
            'leaves'  => [],
        ];

        // Prepare daily data
        $lastSevenDays = collect();
        $dateKeys      = [];

        for ($i = 0; $i <= ceil($start->diffInDays($end)) - 1; $i++) {
            $date = $start->copy()->addDays($i)->format('Y-m-d');
            $lastSevenDays->push($date);
            $dateKeys[$date]               = $i;
            $attendanceData['absents'][$i] = 0;
            $attendanceData['sick'][$i]    = 0;
            $attendanceData['lockUps'][$i] = 0;
            $attendanceData['leaves'][$i]  = 0;
        }
        //dd(ceil($start->diffInDays($end)));
        //$attendanceData['labels'] = $lastSevenDays->toArray();
        $attendanceData['labels'] = array_reverse(collect(CarbonPeriod::create($start, $end))
                ->map(fn($date) => $date->toDateString())
                ->toArray());

        // Prepare weekly data

        $numberOfWeeks = $start_date === null ? 5 : $start->diffInWeeks($end) + 1; // include start week
        $current       = $start->copy()->startOfWeek();                            // <-- fixed here

        $current = Carbon::now()->startOfWeek(); // start of this week

        for ($i = 4; $i >= 0; $i--) {
            $weekStart = $current->copy()->subWeeks($i); // go back 4, 3, ..., 0 weeks
            $weekKey   = $weekStart->format('Y-m-d');
            $weekLabel = "Week {$this->getWeekNumber($weekStart)}";

            $weekKeys[$weekKey]      = 4 - $i; // map week start to index 0–4
            $weeklyData['labels'][]  = $weekLabel;
            $weeklyData['absents'][] = 0;
            $weeklyData['sick'][]    = 0;
            $weeklyData['lockUps'][] = 0;
            $weeklyData['leaves'][]  = 0;
        }
        $numberOfMonths = $start_date == null ? 3 : $start->diffInMonths($end);
        // Prepare monthly data
        $lastThreeMonths = collect();
        $monthKeys       = [];
        for ($i = 0; $i < $numberOfMonths; $i++) {
            $monthName = $start_date == null ? $start->copy()->subMonths($i)->format('F Y') : $start->copy()->addMonths($i)->format('F Y');
            $lastThreeMonths->push($monthName);
            $monthKeys[$monthName]      = $i;
            $monthlyData['absents'][$i] = 0;
            $monthlyData['sick'][$i]    = 0;
            $monthlyData['lockUps'][$i] = 0;
            $monthlyData['leaves'][$i]  = 0;
        }
        $monthlyData['labels'] = $start_date != null ? array_reverse($lastThreeMonths->toArray()) : $lastThreeMonths->toArray();

        $companies = Company::all();

        foreach ($companies as $company) {
            foreach ($company->platoons as $platoon) {
                $attendances = $platoon->attendences()
                    ->where('session_programme_id', $selectedSessionId)
                    ->whereBetween('created_at', [$start, $end])
                    ->get();

                foreach ($attendances as $attendance) {
                    $attendanceDate = Carbon::parse($attendance->created_at)->format('Y-m-d');
                    if (isset($dateKeys[$attendanceDate])) {
                        $index = $dateKeys[$attendanceDate];
                        $attendanceData['absents'][$index] += (int) $attendance->absent;

                        if ($attendanceData['sick'][$index] == 0) {
                            $date                           = $attendance->created_at;
                            $attendanceData['sick'][$index] = (int) Patient::whereDate('created_at', '<=', $date)
                                ->where(function ($query) use ($date) {
                                    $query->where(function ($subQuery) use ($date) {
                                        $subQuery->where('excuse_type_id', 1)
                                            ->whereRaw('DATE_ADD(created_at, INTERVAL rest_days DAY) >= ?', [$date]);
                                    })
                                        ->orWhere(function ($subQuery) use ($date) {
                                            $subQuery->where('excuse_type_id', 3)
                                                ->where(function ($q) use ($date) {
                                                    $q->whereNull('released_at')
                                                        ->orWhereDate('released_at', '>=', $date);
                                                });
                                        });
                                })->count();

                        }

                        if ($attendanceData['lockUps'][$index] == 0) {
                            $attendanceData['lockUps'][$index] = (int) MPS::whereDate('created_at', '<=', $attendanceDate)
                                ->where(function ($query) use ($attendanceDate) {
                                    $query->whereNull('released_at')
                                        ->orWhereDate('released_at', '>=', $attendanceDate);
                                })->count();
                        }

                        if ($attendanceData['leaves'][$index] == 0) {
                            $attendanceData['leaves'][$index] = (int) LeaveRequest::whereDate('created_at', '<=', $attendanceDate)
                                ->where(function ($query) use ($attendanceDate) {
                                    $query->whereNull('return_date')
                                        ->orWhereDate('return_date', '>=', $attendanceDate);
                                })->count();
                        }
                    }

                    $attendanceWeek = Carbon::parse($attendance->created_at)->startOfWeek()->format('Y-m-d');
                    if (isset($weekKeys[$attendanceWeek])) {
                        $weekIndex = $weekKeys[$attendanceWeek];
                        $weeklyData['absents'][$weekIndex]  += (int) $attendance->absent;

                        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
                        $endOfWeek   = Carbon::now()->endOfWeek()->toDateString();

                        if ($weeklyData['sick'][$weekIndex] == 0) {
                            $date                           = Carbon::parse($attendance->date)->endOfWeek()->format('Y-m-d');
                            $weeklyData['sick'][$weekIndex] = (int) Patient::where(function ($query) use ($date) {
                                $query->where(function ($subQuery) use ($date) {
                                    $subQuery->where('excuse_type_id', 1)
                                        ->whereRaw('DATE_ADD(created_at, INTERVAL rest_days DAY) >= ?', [$date]);
                                })
                                    ->orWhere(function ($subQuery) use ($date) {
                                        $subQuery->where('excuse_type_id', 3)
                                            ->where(function ($q) use ($date) {
                                                $q->whereNull('released_at')
                                                    ->orWhereDate('released_at', '>=', $date);
                                            });
                                    });
                            })->count();

                        }

                        if ($weeklyData['lockUps'][$weekIndex] == 0) {
                            $weeklyData['lockUps'][$weekIndex] = (int) MPS::whereDate('created_at', '<=', $endOfWeek)
                                ->where(function ($query) use ($endOfWeek) {
                                    $query->whereNull('released_at')
                                        ->orWhereDate('released_at', '>=', $endOfWeek);
                                })->count();
                        }

                        if ($weeklyData['leaves'][$weekIndex] == 0) {
                            $weeklyData['leaves'][$weekIndex] = (int) LeaveRequest::whereDate('created_at', '<=', $endOfWeek)
                                ->where(function ($query) use ($endOfWeek) {
                                    $query->whereNull('return_date')
                                        ->orWhereDate('return_date', '>=', $endOfWeek);
                                })->count();
                        }
                    }

$attendances = Attendence::whereBetween('date', [$start, $end])->get();
// For monthly data, check which month the attendance falls into
                    $attendanceMonth = Carbon::parse($attendance->date)->format('F Y');
                    if (isset($monthKeys[$attendanceMonth])) {
                        $monthIndex = $monthKeys[$attendanceMonth];
                        $monthlyData['absents'][$monthIndex] += (int) $attendance->absent;
                        $carbonDate = Carbon::parse($attendanceMonth);
                        $month      = $carbonDate->month;
                        $year       = $carbonDate->year;
                        if ($monthlyData['sick'][$monthIndex] == 0) {
                            //    $monthlyData['sick'][$monthIndex] = (int) Patient::whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
                            // $monthlyData['sick'][$monthIndex] = (int) Patient::whereMonth('created_at', $month)->whereYear('created_at', $year)
                            //     ->where(function ($query) {
                            //         $query->where('excuse_type_id', 1)
                            //             ->orWhere('excuse_type_id', 3);
                            //     })
                            //     ->count();

                                $monthlyData['sick'][$monthIndex] = (int) Patient::whereMonth('created_at', $month)->whereYear('created_at', $year)->where(function ($query) use ($month) {
                                $query->where(function ($subQuery) use ($month) {
                                    $subQuery->where('excuse_type_id', 1)
                                        ->whereRaw('DATE_ADD(created_at, INTERVAL rest_days DAY) >= ?', [$month]);
                                })
                                    ->orWhere(function ($subQuery) use ($month) {
                                        $subQuery->where('excuse_type_id', 3)
                                            ->where(function ($q) use ($month) {
                                                $q->whereNull('released_at')
                                                    ->orWhereMonth('released_at', '>=', $month);
                                            });
                                    });
                            })->count();

                        }
                        if ($monthlyData['lockUps'][$monthIndex] == 0) {
                            //$monthlyData['lockUps'][$monthIndex] = (int) MPS::whereMonth('arrested_at', $month)->whereYear('arrested_at', $year)->count();
                            $monthlyData['lockUps'][$monthIndex] = (int) MPS::whereMonth('arrested_at', $month)->whereYear('arrested_at', $year)
                                ->where(function ($query) use ($month) {
                                    $query->whereNull('released_at')
                                        ->orWhereMonth('released_at', '>=', $month);
                                })->count();
                        }
                        if ($monthlyData['leaves'][$monthIndex] == 0) {
                            $monthlyData['leaves'][$monthIndex] = (int) LeaveRequest::whereDate('created_at', '<=', $month)
                                ->where(function ($query) use ($month) {
                                    $query->whereNull('return_date')
                                        ->orWhereMonth('return_date', '>=', $month);
                                })->count();
                        }
                    }

// Step 4: Fill in sick, lockUps, and leaves for each of the 3 months
foreach ($monthKeys as $monthLabel => $index) {
    $carbonDate = Carbon::createFromFormat('F Y', $monthLabel);
    $month = $carbonDate->month;
    $year  = $carbonDate->year;

    // Sick (patients with excuse_type_id 1 or 3)
    $monthlyData['sick'][$index] = Patient::whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->where(function ($query) {
            $query->where('excuse_type_id', 1)
                  ->orWhere('excuse_type_id', 3);
        })->count();

    // LockUps
    $monthlyData['lockUps'][$index] = MPS::whereMonth('arrested_at', $month)
        ->whereYear('arrested_at', $year)
        ->count();

    // Leave Requests
    $monthlyData['leaves'][$index] = LeaveRequest::whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->count();
}
                }
            }
        }

// Reverse to ensure recent data comes last
        foreach (['absents', 'sick', 'lockUps', 'leaves', 'labels'] as $key) {
            //$attendanceData[$key] = array_reverse($attendanceData[$key]);
        }
        $attendanceData['labels'] = array_reverse($attendanceData['labels']);
        foreach (['absents', 'sick', 'lockUps', 'leaves'] as $key) {
            $weeklyData[$key] = array_reverse($weeklyData[$key]);
        }
        $weeklyData['absents'] = array_reverse($weeklyData['absents']);
        $weeklyData['sick']    = array_reverse($weeklyData['sick']);
        $weeklyData['lockUps'] = array_reverse($weeklyData['lockUps']);
        $weeklyData['leaves']  = array_reverse($weeklyData['leaves']);
        $weeklyData['weeks']   = [];

        $weekKeys = array_reverse(array_flip($weekKeys));
        for ($i = count($weekKeys) - 1; $i >= 0; $i--) {
            //$weeklyData['weeks'][] = "Week " . $this->getWeekNumber($weekKeys[$i]);
        }

        foreach (['absents', 'sick', 'lockUps', 'leaves', 'labels'] as $key) {
            $monthlyData[$key] = array_reverse($monthlyData[$key]);
        }

        // Leaves (Daily)
        $sevenDaysAgo = Carbon::today()->subDays(6);
        $rawCounts    = LeaveRequest::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereHas('student', function ($query) use ($selectedSessionId) {
                $query->where('session_programme_id', $selectedSessionId);
            })
            ->whereBetween('created_at', [$start, $end])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date')->toArray();

        $weeklyCounts = [];
        for ($i = 0; $i < 7; $i++) {
            $date           = Carbon::today()->subDays(6 - $i)->toDateString();
            $weeklyCounts[] = ['date' => $date, 'total' => 0];
        }

        $dailyCounts = array_map(function ($weeklyItem) use ($rawCounts) {
            $date = $weeklyItem['date'];
            return [
                'date'  => $date,
                'total' => $rawCounts[$date]['total'] ?? $weeklyItem['total'],
            ];
        }, $weeklyCounts);

        // Leaves (Monthly)
        $startDate = Carbon::now()->subMonths(3)->startOfMonth();
        $endDate   = Carbon::now()->endOfMonth();

        $leaves_monthly_count = LeaveRequest::whereHas('student', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId);
        })
            ->whereBetween('created_at', [$start, $end])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->get()
            ->map(function ($item) {
                return [
                    'month' => Carbon::create()->month($item->month)->format('m'),
                    'year'  => $item->year,
                    'total' => $item->total,
                ];
            })
            ->toArray();

        $months = [];
        for ($i = 2; $i >= 0; $i--) {
            $date     = new \DateTime("-$i month");
            $months[] = [
                'month' => $date->format('m'),
                'year'  => $date->format('Y'),
                'total' => 0,
            ];
        }

        $merged = [];
        foreach (array_merge($months, $leaves_monthly_count) as $item) {
            $key = $item['month'] . '-' . $item['year'];
            if ($item['total'] !== 0) {
                $merged[$key] = ['total' => $item['total']];
            } elseif (! isset($merged[$key])) {
                $merged[$key] = ['total' => 0];
            }
        }

        // Leaves (Weekly)
        $startDate = Carbon::now()->startOfWeek()->subWeeks(4);
        $endDate   = Carbon::now()->endOfWeek();

        $rawWeeks = LeaveRequest::selectRaw('YEARWEEK(created_at, 1) as week_key, COUNT(*) as total')
            ->whereHas('student', function ($query) use ($selectedSessionId) {
                $query->where('session_programme_id', $selectedSessionId);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('YEARWEEK(created_at, 1)'))
            ->orderBy('week_key')
            ->get()
            ->keyBy('week_key')->toArray();

        $weeklyCounts = [];
        for ($i = 0; $i < 5; $i++) {
            $weekStart = Carbon::now()->startOfWeek()->subWeeks(4 - $i);
            $weekKey   = $weekStart->format('W');

            $weeklyCounts[] = [
                'week'  => $weekStart->format('Y-m-d'),
                'total' => isset($rawWeeks[$weekKey]) ? $rawWeeks[$weekKey]->total : 0,
            ];
        }

        $weekly   = [];
        $rawWeeks = array_map(function ($item) {
            if (isset($item['week_key'])) {
                $year         = (int) substr($item['week_key'], 0, 4);
                $weekNumber   = (int) substr($item['week_key'], -2);
                $startOfWeek  = Carbon::now()->setISODate($year, $weekNumber)->startOfWeek();
                $item['week'] = $startOfWeek->format('Y-m-d');
                unset($item['week_key']);
            }
            return $item;
        }, $rawWeeks);

        foreach (array_merge($weeklyCounts, array_values($rawWeeks)) as $item) {
            if (! isset($item['week'])) {
                continue;
            }

            $key = $item['week'];
            if ($item['total'] !== 0) {
                $weekly[$key] = ['total' => $item['total']];
            } elseif (! isset($merged[$key])) {
                $weekly[$key] = ['total' => 0];
            }
        }

        return [
            'dailyData'   => $attendanceData,
            'weeklyData'  => $weeklyData,
            'monthlyData' => $monthlyData,
            'daily'       => array_column($dailyCounts, 'total'),
            'weekly'      => array_column($weekly, 'total'),
            'monthly'     => array_column($merged, 'total'),
        ];
    }

    protected function getDailyData($start, $end, $programmeId)
    {
        $dates = CarbonPeriod::create($start, $end)->toArray();

        $data = collect($dates)->map(function ($date) use ($programmeId) {
            return [
                'date'    => $date->format('Y-m-d'),

                'absents' => (int) Attendence::where('session_programme_id', $programmeId)
                    ->whereDate('created_at', $date)
                    ->sum('absent'),

                'sick'    => Patient::whereDate('created_at', $date)
                    ->count(),

                'lockUps' => MPS::whereDate('created_at', $date)
                    ->count(),

                'leaves'  => LeaveRequest::whereDate('created_at', $date)
                    ->count(),
            ];
        });

        return [
            'labels'  => $data->pluck('date')->toArray(),
            'data'    => $data->toArray(),
            'absents' => $data->pluck('absents')->toArray(),
            'sick'    => $data->pluck('sick')->toArray(),
            'lockUps' => $data->pluck('lockUps')->toArray(),
            'leaves'  => $data->pluck('leaves')->toArray(),
        ];
    }

    private function getWeekNumber($date)
    {
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }
        $sessionProgramme = SessionProgramme::find($selectedSessionId);
        // Define the specified start date (September 30, 2024)
        $startDate = Carbon::createFromFormat('d-m-Y', Carbon::parse($sessionProgramme->startDate)->format('d-m-Y'));
                                      //dd($date);
                                      // Define the target date for which you want to calculate the week number
        $date = Carbon::parse($date); // This could be the current date, or any specific date

                                                          // Calculate the difference in weeks between the start date and the target date
        $weekNumber = $startDate->diffInWeeks($date) + 1; // Adding 1 to make it 1-based (Week 1, Week 2, ...)
        return (int) $weekNumber;
    }

    public function getGraphData2()
    {

        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }

        // Initialize arrays to store the data for each period
        $attendanceData = [
            'dates'   => [],
            'absents' => [],
            'sick'    => [],
            'lockUps' => [],
        ];

        $weeklyData = [
            'weeks'   => [],
            'absents' => [],
            'sick'    => [],
            'lockUps' => [],
        ];

        $monthlyData = [
            'months'  => [],
            'absents' => [],
            'sick'    => [],
            'lockUps' => [],
        ];

        // Get the last 7 days and initialize the arrays for daily data
        $lastSevenDays = collect();
        $dateKeys      = []; // To map the dates directly to indices
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $lastSevenDays->push($date);
            $dateKeys[$date]               = $i;
            $attendanceData['absents'][$i] = 0;
            $attendanceData['sick'][$i]    = 0;
            $attendanceData['lockUps'][$i] = 0;
        }

        // Keep the dates in the order as is (latest comes last)
        $attendanceData['dates'] = $lastSevenDays->toArray();

        // Get the last 5 weeks and initialize the arrays for weekly data
        $lastFiveWeeks = collect();
        $weekKeys      = []; // To map weeks to indices
        for ($i = 0; $i < 5; $i++) {
            $startOfWeek = Carbon::now()->subWeek()->subWeeks($i)->startOfWeek()->format('Y-m-d');
            $endOfWeek   = Carbon::now()->subWeek()->subWeeks($i)->endOfWeek()->format('Y-m-d');
            $weekIndex   = 5 - $i; // Week 5 is oldest, Week 1 is most recent
            $weekKey     = "Week {$weekIndex}: {$startOfWeek} - {$endOfWeek}";

            $lastFiveWeeks->prepend($weekKey); // prepend to maintain order: Week 1 to Week 5
            $weekKeys[$startOfWeek] = $weekIndex - 1;

            $weeklyData['weeks'][]   = $weekKey;
            $weeklyData['absents'][] = 0;
            $weeklyData['sick'][]    = 0;
            $weeklyData['lockUps'][] = 0;
        }

        // Keep the weeks in order (most recent comes last)
        //$weeklyData['weeks'] = $lastFiveWeeks->toArray();

        // Get the last 3 months and initialize the arrays for monthly data
        $lastThreeMonths = collect();
        $monthKeys       = []; // To map months to indices
        for ($i = 0; $i < 3; $i++) {
            $monthName = Carbon::now()->subMonths($i)->format('F Y');
            $lastThreeMonths->push($monthName);
            $monthKeys[$monthName] = $i;

            $monthlyData['absents'][$i] = 0;
            $monthlyData['sick'][$i]    = 0;
            $monthlyData['lockUps'][$i] = 0;
        }

        // Keep the months in order (most recent comes last)
        $monthlyData['months'] = $lastThreeMonths->toArray();

        // Retrieve all companies and their platoons
        $companies = Company::all();

        // Loop through each company and its platoons
        foreach ($companies as $company) {

            //dd($selectedSessionId);
            foreach ($company->platoons as $platoon) {
                // Get attendance records for the last 7 days
                //$sick = Patient::where('platoon', $platoon->id)->where('created_at', '>=', Carbon::now()->subDays(7))->count();
                $attendances = $platoon->attendences()->where('session_programme_id', $selectedSessionId)
                    ->where('created_at', '>=', Carbon::now()->subDays(7))
                    ->get();
                // Process attendance data for the last 7 days
                foreach ($attendances as $attendance) {
                    $attendanceDate = Carbon::parse($attendance->created_at)->format('Y-m-d');
                    if (isset($dateKeys[$attendanceDate])) {
                        $index = $dateKeys[$attendanceDate];
                        $attendanceData['absents'][$index] += (int) $attendance->absent;
                        if ($attendanceData['sick'][$index] == 0) {
                            // $attendanceData['sick'][$index] = (int) Patient::whereDate('created_at',$attendanceDate)->whereNotNull('excuse_type_id')->count();
                            // $attendanceData['sick'][$index] = (int) Patient::whereDate('created_at', $attendanceDate)
                            // ->where(function ($query) {
                            //     $query->where('excuse_type_id', 1)
                            //           ->orWhere('excuse_type_id', 3);
                            // })
                            // ->count();

                            $attendanceData['sick'][$index] = (int) Patient::
                                where(function ($query) {
                                $query->where(function ($subQuery) {
                                    $subQuery->where('excuse_type_id', 1)
                                        ->whereRaw('DATE_ADD(created_at, INTERVAL rest_days DAY) >= ?', [Carbon::today()]);
                                })
                                    ->orWhere(function ($subQuery) {
                                        $subQuery->where('excuse_type_id', 3)
                                            ->whereNull('released_at'); // Checks for released_at = NULL when excuse_type_id = 3
                                    });
                            })
                                ->count();

                        }

                        if ($attendanceData['lockUps'][$index] == 0) {
                            // $attendanceData['lockUps'][$index] = (int) MPS::whereDate('arrested_at', $attendanceDate)->count();
                            $attendanceData['lockUps'][$index] = (int) MPS::where('released_at', null)->count();
                        }
                    }

                    // For weekly data, check which week the attendance falls into
                    $attendanceWeek = Carbon::parse($attendance->created_at)->startOfWeek()->format('Y-m-d');
                    if (isset($weekKeys[$attendanceWeek])) {
                        $weekIndex = $weekKeys[$attendanceWeek];
                        $weeklyData['absents'][$weekIndex] += (int) $attendance->absent;
                        $startOfWeek = Carbon::parse(Carbon::now()->startOfWeek())->toDateString(); // Set a start time
                        $endOfWeek   = Carbon::parse(Carbon::now()->endOfWeek())->toDateString();   // Set a start time
                        if ($weeklyData['sick'][$weekIndex] == 0) {
                            // $weeklyData['sick'][$weekIndex] = (int) Patient::whereBetween('created_at', [$startOfWeek, $endOfWeek])->whereNotNull('excuse_type_id')->count();

                            $weeklyData['sick'][$weekIndex] = (int) Patient::
                                where(function ($query) {
                                $query->where(function ($subQuery) {
                                    $subQuery->where('excuse_type_id', 1)
                                        ->whereRaw('DATE_ADD(created_at, INTERVAL rest_days DAY) >= ?', [Carbon::today()]);
                                })
                                    ->orWhere(function ($subQuery) {
                                        $subQuery->where('excuse_type_id', 3)
                                            ->whereNull('released_at'); // Checks for released_at = NULL when excuse_type_id = 3
                                    });
                            })
                                ->count();
                        }
                        if ($weeklyData['lockUps'][$weekIndex] == 0) {
                            // $weeklyData['lockUps'][$weekIndex] = (int) MPS::whereBetween('arrested_at', [$startOfWeek, $endOfWeek])->count();
                            $weeklyData['lockUps'][$weekIndex] = (int) MPS::where('released_at', null)->count();
                        }
                    }

                    for ($i = 0; $i < count($attendanceData['dates']); $i++) {
                        $attendanceData['dates'][$i] = Carbon::parse($attendanceData['dates'][$i])->format('d-m-Y');
                    }
                    // For monthly data, check which month the attendance falls into
                    $attendanceMonth = Carbon::parse($attendance->created_at)->format('F Y');
                    if (isset($monthKeys[$attendanceMonth])) {
                        $monthIndex = $monthKeys[$attendanceMonth];
                        $monthlyData['absents'][$monthIndex] += (int) $attendance->absent;
                        $carbonDate = Carbon::parse($attendanceMonth);
                        $month      = $carbonDate->month;
                        $year       = $carbonDate->year;
                        if ($monthlyData['sick'][$monthIndex] == 0) {
                            //    $monthlyData['sick'][$monthIndex] = (int) Patient::whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
                            $monthlyData['sick'][$monthIndex] = (int) Patient::whereMonth('created_at', $month)->whereYear('created_at', $year)
                                ->where(function ($query) {
                                    $query->where('excuse_type_id', 1)
                                        ->orWhere('excuse_type_id', 3);
                                })
                                ->count();
                        }
                        if ($monthlyData['lockUps'][$monthIndex] == 0) {
                            $monthlyData['lockUps'][$monthIndex] = (int) MPS::whereMonth('arrested_at', $month)->whereYear('arrested_at', $year)->count();
                        }
                    }
                }
            }
        }

        // Reverse the daily data arrays to ensure the most recent data comes last
        $attendanceData['absents'] = array_reverse($attendanceData['absents']);
        $attendanceData['sick']    = array_reverse($attendanceData['sick']);
        $attendanceData['lockUps'] = array_reverse($attendanceData['lockUps']);
        $attendanceData['dates']   = array_reverse($attendanceData['dates']);

        // Reverse the weekly data arrays to ensure the most recent data comes last
        $weeklyData['absents'] = array_reverse($weeklyData['absents']);
        $weeklyData['sick']    = array_reverse($weeklyData['sick']);
        $weeklyData['lockUps'] = array_reverse($weeklyData['lockUps']);
        $weeklyData['weeks']   = [];
        $weekKeys              = array_flip($weekKeys);
        for ($i = count($weekKeys) - 1; $i >= 0; $i--) {
            // Use the getWeekNumber function to get the week number and push it to the 'weeks' array
            array_push($weeklyData['weeks'], "Week " . $this->getWeekNumber($weekKeys[$i]));
        }

        // Reverse the monthly data arrays to ensure the most recent data comes last
        $monthlyData['absents'] = array_reverse($monthlyData['absents']);
        $monthlyData['sick']    = array_reverse($monthlyData['sick']);
        $monthlyData['lockUps'] = array_reverse($monthlyData['lockUps']);
        $monthlyData['months']  = array_reverse($monthlyData['months']);
        $selectedSessionId      = 1;
        $selectedSessionId      = session('selected_session');

// Step 1: Fetch raw daily totals for the last 7 days (including today)
        $sevenDaysAgo = Carbon::today()->subDays(6); // 6 days ago + today = 7 days

        $rawCounts = LeaveRequest::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereHas('student', function ($query) use ($selectedSessionId) {
                $query->where('session_programme_id', $selectedSessionId);
            })
            ->whereDate('created_at', '>=', $sevenDaysAgo)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date')->toArray(); // Use date as array key for easy lookup
//dd(array_values($rawCounts));
// Step 2: Build complete 7-day result set
        $weeklyCounts = [];
        for ($i = 0; $i < 7; $i++) {
            $date           = Carbon::today()->subDays(6 - $i)->toDateString(); // from oldest to newest
            $weeklyCounts[] = [
                'date'  => $date,
                'total' => 0,
            ];
        }
        $dailyCounts = array_map(function ($weeklyItem) use ($rawCounts) {
            $date = $weeklyItem['date'];

            return [
                'date'  => $date,
                'total' => $rawCounts[$date]['total'] ?? $weeklyItem['total'], // Use rawCounts if available, else default to 0
            ];
        }, $weeklyCounts);
        // $leaves_weekly_count = LeaveRequest::whereHas('student', function ($query) use ($selectedSessionId) {
        //     $query->where('session_programme_id', $selectedSessionId);
        // })->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

        $startDate = Carbon::now()->subMonths(3)->startOfMonth(); // 3 months ago
        $endDate   = Carbon::now()->endOfMonth();                 // end of current month

        $leaves_monthly_count = LeaveRequest::whereHas('student', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId);
        })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->get()
            ->map(function ($item) {
                return [
                    'month' => Carbon::create()->month($item->month)->format('m'),
                    'year'  => $item->year,
                    'total' => $item->total,
                ];
            })
            ->toArray();

        $months = [];
        for ($i = 2; $i >= 0; $i--) {
            $date     = new \DateTime("-$i month");
            $months[] = [
                'month' => $date->format('m'), // Full month name, e.g., "May"
                'year'  => $date->format('Y'),
                'total' => 0, // Four-digit year, e.g., "2025"
            ];
        }
        $merged = [];

        foreach (array_merge($months, $leaves_monthly_count) as $item) {
            $key = $item['month'] . '-' . $item['year']; // composite key
                                                         // Only overwrite if total is not zero
            if ($item['total'] !== 0) {
                $merged[$key] = [
                    'total' => $item['total'],
                ];
            } elseif (! isset($merged[$key])) {
                // If the entry hasn't been set yet, set it with total = 0
                $merged[$key] = [
                    'total' => 0,
                ];
            }

        }
        $startDate = Carbon::now()->startOfWeek()->subWeeks(4);
        $endDate   = Carbon::now()->endOfWeek();

// Step 2: Fetch weekly totals from DB
        $rawWeeks = LeaveRequest::selectRaw('YEARWEEK(created_at, 1) as week_key, COUNT(*) as total')
            ->whereHas('student', function ($query) use ($selectedSessionId) {
                $query->where('session_programme_id', $selectedSessionId);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('YEARWEEK(created_at, 1)'))
            ->orderBy('week_key')
            ->get()
            ->keyBy('week_key')->toArray();

        $weeklyCounts = [];

        for ($i = 0; $i < 5; $i++) {
            $weekStart = Carbon::now()->startOfWeek()->subWeeks(4 - $i);
            $weekKey   = $weekStart->format('W'); // e.g., "202518"

            $weeklyCounts[] = [
                'week'  => $weekStart->format('Y-m-d'), // start date of week
                'total' => isset($rawWeeks[$weekKey]) ? $rawWeeks[$weekKey]->total : 0,
            ];
        }
//dd(array_values($rawWeeks));
        $weekly = [];

        $rawWeeks = array_map(function ($item) {
            if (isset($item['week_key'])) {
                // Extract year and week number
                $year       = (int) substr($item['week_key'], 0, 4);
                $weekNumber = (int) substr($item['week_key'], -2);

                // Convert to first day of the week (Monday)
                $startOfWeek = Carbon::now()->setISODate($year, $weekNumber)->startOfWeek();

                // Rename `week_key` to `week`, replacing it with formatted start of the week
                $item['week'] = $startOfWeek->format('Y-m-d');
                unset($item['week_key']); // Remove old key
            }
            return $item;
        }, $rawWeeks);

        foreach (array_merge($weeklyCounts, array_values($rawWeeks)) as $item) {
            if (! isset($item['week'])) {
                continue; // Skip items without "week" key
            }
            $key = $item['week']; // composite key
                                  // Only overwrite if total is not zero
            if ($item['total'] !== 0) {
                $weekly[$key] = [
                    'total' => $item['total'],
                ];
            } elseif (! isset($merged[$key])) {
                // If the entry hasn't been set yet, set it with total = 0
                $weekly[$key] = [
                    'total' => 0,
                ];
            }

        }
        $leaves_weekly_count  = array_values($weeklyCounts);
        $leaves_daily_count   = array_column($weeklyCounts, 'total');
        $leaves_monthly_count = array_column($merged, 'total');
        // dd([
        //     'dailyData'   => $attendanceData,
        //     'weeklyData'  => $weeklyData,
        //     'monthlyData' => $monthlyData,
        //     'daily'       => array_column($dailyCounts, 'total'),
        //     'weekly'      => array_column($weekly, 'total'),
        //     'monthly'     => $leaves_monthly_count,
        // ]);
        // Combine all three sets of data into the final response
        return [
            'dailyData'   => $attendanceData,
            'weeklyData'  => $weeklyData,
            'monthlyData' => $monthlyData,
            'daily'       => array_column($dailyCounts, 'total'),
            'weekly'      => array_column($weekly, 'total'),
            'monthly'     => $leaves_monthly_count,
        ];
    }

}
