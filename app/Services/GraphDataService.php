<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\Attendence;
use App\Models\Beat;
use App\Models\Company;
use App\Models\LeaveRequest;
use App\Models\MPS;
use App\Models\Patient;
use App\Models\Platoon;
use App\Models\SessionProgramme;
use App\Models\Staff;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GraphDataService
{
    private $selectedSessionId;
    public function getGraphData()
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
        for ($i = 1; $i <= 5; $i++) {
            $startOfWeek = Carbon::now()->subWeeks($i - 1)->startOfWeek()->format('Y-m-d');
            $endOfWeek   = Carbon::now()->subWeeks($i - 1)->endOfWeek()->format('Y-m-d');
            $weekKey     = "Week {$i}: {$startOfWeek} - {$endOfWeek}";

            $lastFiveWeeks->push($weekKey);
            $weekKeys[$startOfWeek]  = $i - 1; // Use start of week as the key
            $weeklyData['weeks']     = [];
            $weeklyData['absents'][] = 0;
            $weeklyData['sick'][]    = 0;
            $weeklyData['lockUps'][] = 0;
        }

        // Keep the weeks in order (most recent comes last)
        $weeklyData['weeks'] = $lastFiveWeeks->toArray();

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
                             $attendanceData['lockUps'][$index] = (int) MPS::whereDate('arrested_at', $attendanceDate)->count();
                            //$attendanceData['lockUps'][$index] = (int) MPS::where('released_at', null)->count();
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
                             $weeklyData['lockUps'][$weekIndex] = (int) MPS::whereBetween('arrested_at', [$startOfWeek, $endOfWeek])->count();
                            //$weeklyData['lockUps'][$weekIndex] = (int) MPS::where('released_at', null)->count();
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
}