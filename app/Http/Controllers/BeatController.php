<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\GuardArea;
use App\Models\PatrolArea;
use App\Models\Beat;
use App\Models\Company;
use App\Models\BeatReserve;
use App\Models\BeatLeaderOnDuty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Barryvdh\DomPDF\Facade\Pdf;

class BeatController extends Controller
{
    protected $usedStudentIds = [];
    public function beatsByDate(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $companies = Company::whereHas('guardAreas.beats', function ($query) use ($date) {
            $query->where('date', $date);
        })
        ->orWhereHas('patrolAreas.beats', function ($query) use ($date) {
            $query->where('date', $date);
        })
        ->with(['guardAreas.beats' => function ($query) use ($date) {
            $query->where('date', $date);
        }, 'patrolAreas.beats' => function ($query) use ($date) {
            $query->where('date', $date);
        }])
        ->get();

        return view('beats.by_date', compact('companies', 'date'));
    }


    
    // public function showBeats()
    // {
    //     $companies = Company::with(['guardAreas.beats', 'patrolAreas.beats'])->get();

    //     return view('beats.index', compact('companies'));
    // }

      /**
     * Display a listing of beats.
     */
    public function index()
    {
        $beats = Beat::with(['guardArea', 'patrolArea'])->orderBy('date', 'desc')->get();
        return view('beats.index', compact('beats'));
    }

    public function beatCreate()
    {
        $beats = Beat::with(['guardArea', 'patrolArea'])->orderBy('date', 'desc')->get();
        return view('beats.beat_create', compact('beats'));
    }
    

//     public function generatePDF(Request $request, $companyId)
// {
//     $date = $request->input('date', Carbon::today()->toDateString());

//     $company = Company::where('id', $companyId)
//         ->where(function ($query) use ($date) {
//             $query->whereHas('guardAreas.beats', function ($query) use ($date) {
//                 $query->where('date', $date);
//             })
//             ->orWhereHas('patrolAreas.beats', function ($query) use ($date) {
//                 $query->where('date', $date);
//             });
//         })
//         ->with([
//             'guardAreas.beats.students' => function ($query) use ($date) {
//                 $query->where('beats.date', $date);
//             },
//             'patrolAreas.beats.students' => function ($query) use ($date) {
//                 $query->where('beats.date', $date);
//             }
//         ])
//         ->firstOrFail();

//     // 🟢 Step 1: Initialize summary array
//     $summary = [];
//     $totalPlatoonCount = [];

//     // 🟢 Step 2: Group students by time slot and platoon
//     foreach ($company->guardAreas as $area) {
//         foreach ($area->beats as $beat) {
//             $timeSlot = $beat->start_at . ' - ' . $beat->end_at;

//             foreach ($beat->students as $student) {
//                 $platoon = $student->platoon;

//                 if (!isset($summary[$timeSlot][$platoon])) {
//                     $summary[$timeSlot][$platoon] = 0;
//                 }
//                 $summary[$timeSlot][$platoon]++;

//                 // Count total students per platoon
//                 if (!isset($totalPlatoonCount[$platoon])) {
//                     $totalPlatoonCount[$platoon] = 0;
//                 }
//                 $totalPlatoonCount[$platoon]++;
//             }
//         }
//     }

//     foreach ($company->patrolAreas as $area) {
//         foreach ($area->beats as $beat) {
//             $timeSlot = $beat->start_at . ' - ' . $beat->end_at;

//             foreach ($beat->students as $student) {
//                 $platoon = $student->platoon;

//                 if (!isset($summary[$timeSlot][$platoon])) {
//                     $summary[$timeSlot][$platoon] = 0;
//                 }
//                 $summary[$timeSlot][$platoon]++;

//                 // Count total students per platoon
//                 if (!isset($totalPlatoonCount[$platoon])) {
//                     $totalPlatoonCount[$platoon] = 0;
//                 }
//                 $totalPlatoonCount[$platoon]++;
//             }
//         }
//     }

//     // 🟢 Step 3: Sort by time slot and platoon number
//     ksort($summary);
//     ksort($totalPlatoonCount);
//     foreach ($summary as &$platoonData) {
//         ksort($platoonData);
//     }

//     // 🟢 Step 4: Generate PDF and pass summary + total platoon count
//     $pdf = Pdf::loadView('beats.pdf', compact('company', 'date', 'summary', 'totalPlatoonCount'))
//         ->setPaper('a4', 'landscape');

//     return $pdf->download('beats_' . $company->name . '_' . $date . '.pdf');
// }

// public function generatePDF($companyId)
// {
//     $date = "2025-02-19";
    public function generatePDF(Request $request, $companyId)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $company = Company::where('id', $companyId)
            ->where(function ($query) use ($date) {
                $query->whereHas('guardAreas.beats', function ($query) use ($date) {
                    $query->where('date', $date);
                })
                ->orWhereHas('patrolAreas.beats', function ($query) use ($date) {
                    $query->where('date', $date);
                });
            })
            ->with(['guardAreas.beats' => function ($query) use ($date) {
                $query->where('date', $date);
            }, 'patrolAreas.beats' => function ($query) use ($date) {
                $query->where('date', $date);
            }])
            ->firstOrFail();

        $summary = [];
        $totalPlatoonCount = [];

        foreach ($company->guardAreas as $area) {
            foreach ($area->beats as $beat) {
                $this->updateSummary($summary, $totalPlatoonCount, $beat);
            }
        }

        foreach ($company->patrolAreas as $area) {
            foreach ($area->beats as $beat) {
                $this->updateSummary($summary, $totalPlatoonCount, $beat);
            }
        }

        // return view('beats_summary', compact('company', 'date', 'summary', 'totalPlatoonCount'));
        
    //     // 🟢 Step 4: Generate PDF and pass summary + total platoon count
        $pdf = Pdf::loadView('beats.pdf', compact('company', 'date', 'summary', 'totalPlatoonCount'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('beats_' . $company->name . '_' . $date . '.pdf');
    }

private function updateSummary(&$summary, &$totalPlatoonCount, $beat)
{
    $timeSlot = $beat->start_at . " - " . $beat->end_at;
    $studentIds = json_decode($beat->student_ids, true);
    $students = Student::whereIn('id', $studentIds)->get();

    if (!isset($summary[$timeSlot])) {
        $summary[$timeSlot] = [];
    }

    foreach ($students as $student) {
        $platoon = $student->platoon;

        if (!isset($summary[$timeSlot][$platoon])) {
            $summary[$timeSlot][$platoon] = 0;
        }

        $summary[$timeSlot][$platoon]++;

        if (!isset($totalPlatoonCount[$platoon])) {
            $totalPlatoonCount[$platoon] = 0;
        }

        $totalPlatoonCount[$platoon]++;
    }
}



/**
 * Updates the summary of assigned students per platoon per time slot.
 */


    public function generatePDFold($companyId)
    {
        
        // $date = $request->input('date', Carbon::today()->toDateString());
        // $date = Carbon::today()->toDateString();
        $date = "2025-02-19";

        $company = Company::where('id', $companyId)
            ->where(function ($query) use ($date) {
                $query->whereHas('guardAreas.beats', function ($query) use ($date) {
                    $query->where('date', $date);
                })
                ->orWhereHas('patrolAreas.beats', function ($query) use ($date) {
                    $query->where('date', $date);
                });
            })
            ->with(['guardAreas.beats' => function ($query) use ($date) {
                $query->where('date', $date);
            }, 'patrolAreas.beats' => function ($query) use ($date) {
                $query->where('date', $date);
            }])
            ->firstOrFail();


            // Generate summary
            $summary = [];
            foreach ($beats as $beat) {
                $timeSlot = $beat->start_at . " - " . $beat->end_at;
                $studentIds = json_decode($beat->student_ids, true);
                $students = Student::whereIn('id', $studentIds)->get();

                foreach ($students as $student) {
                    $platoon = $student->platoon;

                    if (!isset($summary[$timeSlot])) {
                        $summary[$timeSlot] = [];
                    }

                    if (!isset($summary[$timeSlot][$platoon])) {
                        $summary[$timeSlot][$platoon] = 0;
                    }

                    $summary[$timeSlot][$platoon]++;
                }
            }

        // Select Reserves
        // $reserves = $this->selectReserves($companyId);

        // Select Leaders on Duty
        // $leadersOnDuty = $this->selectLeadersOnDuty($companyId, $reserves);

        // Save reserves to beat_reserves table
        // foreach ($reserves as $reserve) {
        //     BeatReserve::create([
        //         'company_id' => $companyId,
        //         'student_id' => $reserve->id,
        //         'beat_date' => $date,
        //     ]);
        // }

        // Save leaders on duty to beat_leaderon_duties table
        // foreach ($leadersOnDuty as $leader) {
        //     BeatLeaderOnDuty::create([
        //         'company_id' => $companyId,
        //         'student_id' => $leader->id,
        //         'beat_date' => $date,
        //     ]);
        // }

        $pdf = Pdf::loadView('beats.pdf', compact('company', 'date'))
            ->setPaper('a4', 'landscape'); // Set the paper orientation to landscape

        return $pdf->download('beats_' . $company->name . '_' . $date . '.pdf');


        // Generate PDF
        $pdf = Pdf::loadView('pdf.beat_report', compact('beats', 'summary', 'date'));

        return $pdf->download("Beat_Report_{$date}.pdf");
    }

    private function selectReserves($companyId)
    {
        $date = Carbon::today()->toDateString();
        
        // Get the student IDs already selected for the beat of the day
        $assignedStudentIds = Student::whereHas('beats', function ($query) use ($date) {
            $query->where('date', $date);
        })->pluck('id')->toArray();

        $reserves = collect();
        $platoons = range(1, 10);
        
        // Shuffle platoons to ensure random selection
        shuffle($platoons);
        $selectedPlatoons = array_slice($platoons, 0, 10);

        foreach ($selectedPlatoons as $platoon) {
            $students = Student::where('company_id', $companyId)
                ->where('platoon', $platoon)
                ->where('beat_status', 1)
                ->where('session_programme_id', 1)
                ->whereNotIn('id', $assignedStudentIds) // Exclude already assigned students
                ->orderBy('beat_round', 'asc')
                ->orderBy('id', 'asc') // Prioritize students with lower ID
                ->get();
            
            if ($reserves->where('gender', 'M')->count() < 6) {
                $male = $students->where('gender', 'M')->first();
                if ($male) {
                    $reserves->push($male);
                }
            }
            if ($reserves->where('gender', 'F')->count() < 4) {
                $female = $students->where('gender', 'F')->first();
                if ($female) {
                    $reserves->push($female);
                }
            }
        }

        return $reserves->unique('id')->take(10);
    }

    private function selectLeadersOnDuty($companyId, $reserves)
    {
        $date = Carbon::today()->toDateString();
        
        // Get the student IDs already selected for the beat of the day and reserves
        $assignedStudentIds = Student::whereHas('beats', function ($query) use ($date) {
            $query->where('date', $date);
        })->pluck('id')->toArray();
        
        $reserveIds = $reserves->pluck('id')->toArray();

        $students = Student::where('company_id', $companyId)
            ->where('beat_status', 3)
            ->where('session_programme_id', 1)
            ->whereNotIn('id', array_merge($assignedStudentIds, $reserveIds)) // Exclude already assigned students and reserves
            ->orderBy('beat_leader_round', 'asc')
            ->orderBy('id', 'asc') // Prioritize students with lower ID
            ->get();

        $male = $students->where('gender', 'M')->first();
        $female = $students->where('gender', 'F')->first();

        return collect([$male, $female])->filter();
    }

    

    function generateBeats($areas, $studentsByCompany, $studentsByPlatoon, $beatType, $date, &$usedStudentIds)
{
    $beats = [];

    // Dynamically determine platoon groups A & B
    $totalPlatoons = $studentsByPlatoon->keys()->sort()->values();
    $mid = floor($totalPlatoons->count() / 2);
    $groupA = $totalPlatoons->slice(0, $mid)->values();
    $groupB = $totalPlatoons->slice($mid)->values();
    $currentGroup = (Carbon::parse($date)->day % 2 === 1) ? $groupA : $groupB;

    foreach ($areas as $areaData) {
        $area = $areaData['area'];
        $startAt = $areaData['start_at'];
        $endAt = $areaData['end_at'];
        $company_id = $area->company_id;
        $requiredStudents = $area->number_of_guards;
        $assignedStudentIds = [];

        // Fetch eligible students
        $companyStudents = $studentsByCompany[$company_id] ?? collect();
        $companyStudents = $companyStudents
            ->whereIn('platoon', $currentGroup)
            ->whereNotIn('id', $usedStudentIds)
            ->values();

        // Apply Gender Restrictions
        if (!empty($area->beat_exception_ids)) {
            $exceptions = json_decode($area->beat_exception_ids, true);
            if (in_array(1, $exceptions)) {
                $companyStudents = $companyStudents->where('gender', 'F')->values();
            } elseif (in_array(2, $exceptions)) {
                $companyStudents = $companyStudents->where('gender', 'M')->values();
            } elseif (in_array(3, $exceptions)) {
                $femaleStudents = $companyStudents->where('gender', 'F')->values();
                $maleStudents = $companyStudents->where('gender', 'M')->values();

                // Calculate the count for each gender
                $femaleCount = $femaleStudents->count();
                $maleCount = $maleStudents->count();

                // Ensure the number of female students is either greater than or less than the number of male students
                if ($femaleCount !== $maleCount) {
                    // Combine both collections without adjusting
                    $companyStudents = $femaleStudents->merge($maleStudents)->values();
                } else {
                    // Adjust to ensure femaleCount is not equal to maleCount
                    $femaleStudents = $femaleStudents->take($femaleCount + 2);
                    $companyStudents = $maleStudents->merge($femaleStudents)->values();
                }
            }
        } else {
            // Prioritize females during the day and males at night but allow both if necessary
            if ($startAt === '06:00' || $startAt === '12:00') {
                $preferredStudents = $companyStudents->where('gender', 'F');
            } else {
                $preferredStudents = $companyStudents->where('gender', 'M');
            }

            if ($preferredStudents->isNotEmpty()) {
                $companyStudents = $preferredStudents->values();
            }
        }

        // Sort students by beat_round (ascending) and id (ascending)
        $companyStudents = $companyStudents->sort(function ($a, $b) {
            if ($a->beat_round == $b->beat_round) {
                return $a->id <=> $b->id;
            }
            return $a->beat_round <=> $b->beat_round;
        })->values();

        // Group students by platoon
        $studentsByPlatoonInGroup = $companyStudents->groupBy('platoon');
        $platoonsInGroup = $currentGroup->toArray();
        $numPlatoons = count($platoonsInGroup);

        if ($numPlatoons > 0 && $requiredStudents > 0) {
            // Calculate students per platoon
            $studentsPerPlatoon = intdiv($requiredStudents, $numPlatoons);
            $remainingStudents = $requiredStudents % $numPlatoons;

            // dd($platoonsInGroup);
            // Shuffle platoons for fair distribution of remaining students
            shuffle($platoonsInGroup);

            foreach ($platoonsInGroup as $platoon) {
                $studentsNeeded = $studentsPerPlatoon;
                if ($remainingStudents > 0) {
                    $studentsNeeded += 1;
                    $remainingStudents -= 1;
                }

                $platoonStudents = $studentsByPlatoonInGroup[$platoon] ?? collect();
                $platoonStudents = $platoonStudents->whereNotIn('id', $usedStudentIds)->values();
                $availableStudents = $platoonStudents->count();

                // Assign as many students as possible, up to the number needed
                $studentsToAssign = min($studentsNeeded, $availableStudents);
                $selectedStudents = $platoonStudents->take($studentsToAssign)->pluck('id')->toArray();

                $assignedStudentIds = array_merge($assignedStudentIds, $selectedStudents);
                $usedStudentIds = array_merge($usedStudentIds, $selectedStudents);
            }

            // Fill any remaining slots with available students
            $unfilledSpots = $requiredStudents - count($assignedStudentIds);
            if ($unfilledSpots > 0) {
                $remainingStudents = $companyStudents->whereNotIn('id', $usedStudentIds)->pluck('id')->toArray();
                $additionalStudents = array_slice($remainingStudents, 0, $unfilledSpots);
                $assignedStudentIds = array_merge($assignedStudentIds, $additionalStudents);
                $usedStudentIds = array_merge($usedStudentIds, $additionalStudents);
            }



            // Increment beat_round for assigned students
            Student::whereIn('id', $assignedStudentIds)->increment('beat_round');
        }

        if (!empty($assignedStudentIds)) {
            $beats[] = [
                'beatType_id'   => ($beatType === 'guards') ? 1 : 2,
                'guardArea_id'  => ($beatType === 'guards') ? $area->id : null,
                'patrolArea_id' => ($beatType === 'patrols') ? $area->id : null,
                'student_ids'   => json_encode($assignedStudentIds),
                'date'          => $date,
                'start_at'      => $startAt,
                'end_at'        => $endAt,
                'status'        => true,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ];
        }
    }

    return $beats;
}

public function fillBeats(Request $request)
{
    $date = $request->input('date', Carbon::today()->toDateString());

    if (Beat::where('date', $date)->exists()) {
        return response()->json(['message' => 'Beats already generated for ' . $date], 200);
    }

    // Fetch active students (only those eligible for beats)
    $activeStudents = Student::where('beat_status', 1)
        ->where('session_programme_id', 1)
        ->orderBy('beat_round')
        ->orderBy('id')
        ->get();

    // Group students by company and platoon
    $studentsByCompany = $activeStudents->groupBy('company_id');
    $studentsByPlatoon = $activeStudents->groupBy('platoon');

    // Fetch guard and patrol areas with proper time filters
    $guardAreas = $this->filterAreasByTimeExceptions(GuardArea::all());
    $patrolAreas = $this->filterAreasByTimeExceptions(PatrolArea::all());

    // Track used student IDs to prevent duplication across all roles
    $usedStudentIds = [];

    // Generate beats for guards
    $guardBeats = $this->generateBeats($guardAreas, $studentsByCompany, $studentsByPlatoon, 'guards', $date, $usedStudentIds);

    // Generate beats for patrols
    $patrolBeats = $this->generateBeats($patrolAreas, $studentsByCompany, $studentsByPlatoon, 'patrols', $date, $usedStudentIds);

    // Assign reserves for each company
    $reserveStudents = [];
    foreach ($studentsByCompany as $companyId => $students) {
        $reserveStudents = array_merge($reserveStudents, $this->assignReserves($companyId, $date, $usedStudentIds));
    }

    // Assign Leaders on Duty for each company
    $leadersOnDuty = [];
    foreach ($studentsByCompany as $companyId => $students) {
        $leadersOnDuty = array_merge($leadersOnDuty, $this->assignLeadersOnDuty($companyId, $date, $usedStudentIds));
    }

    // Save everything to the database in a single transaction
    DB::transaction(function () use ($guardBeats, $patrolBeats, $reserveStudents, $leadersOnDuty) {
        foreach (array_merge($guardBeats, $patrolBeats) as $beatData) {
            $beat = Beat::create($beatData);
            $beat->students()->attach(json_decode($beatData['student_ids']));
        }

        // Save reserve students
        foreach ($reserveStudents as $reserve) {
            BeatReserve::create($reserve);
        }

        // Save leaders on duty
        foreach ($leadersOnDuty as $leader) {
            BeatLeaderOnDuty::create($leader);
        }
    });

    return response()->json(['message' => 'Beats generated successfully for ' . $date], 200);
}



function filterAreasByTimeExceptions($areas)
{
    $filteredAreas = [];
    $timeRanges = [
        1 => ['start' => '06:00', 'end' => '12:00'],
        2 => ['start' => '12:00', 'end' => '18:00'],
        3 => ['start' => '18:00', 'end' => '00:00'],
        4 => ['start' => '00:00', 'end' => '06:00']
    ];

    foreach ($areas as $area) {
        $exceptions = json_decode($area->beat_time_exception_ids);

        if (empty($exceptions)) {
            // No time exceptions, area is guarded 24hrs
            foreach ($timeRanges as $range) {
                $filteredAreas[] = [
                    'area' => $area,
                    'start_at' => $range['start'],
                    'end_at' => $range['end']
                ];
            }
        } else {
            // Area has time exceptions, filter based on exceptions
            foreach ($exceptions as $exception) {
                if (isset($timeRanges[$exception])) {
                    $filteredAreas[] = [
                        'area' => $area,
                        'start_at' => $timeRanges[$exception]['start'],
                        'end_at' => $timeRanges[$exception]['end']
                    ];
                }
            }
        }
    }

    return $filteredAreas;
}


/**
 * Function to Assign Leaders on Duty
 */

 function assignLeadersOnDuty($companyId, $date, &$usedStudentIds)
{
    // Check if leaders on duty have already been assigned for the given date
    $existingLeadersOnDuty = BeatLeaderOnDuty::where('beat_date', $date)
        ->where('company_id', $companyId)
        ->exists();

    if ($existingLeadersOnDuty) {
        // Leaders on duty already assigned for this date, return an empty array
        return [];
    }

    // Fetch already assigned student IDs for this company (Guard, Patrol, Reserve)
    $assignedStudentIds = Beat::where('date', $date)
        ->whereHas('guardArea', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
        ->orWhereHas('patrolArea', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
        ->pluck('student_ids')
        ->map(fn($ids) => json_decode($ids, true))
        ->flatten()
        ->toArray();

    $assignedReserveIds = BeatReserve::where('beat_date', $date)
        ->where('company_id', $companyId)
        ->pluck('student_id')
        ->toArray();

    $alreadyAssigned = array_merge($assignedStudentIds, $assignedReserveIds, $usedStudentIds);

    // Fetch eligible students
    $eligibleStudents = Student::where('beat_status', 3)
        ->where('company_id', $companyId)
        ->whereNotIn('id', $alreadyAssigned) // Avoid duplication
        ->orderBy('beat_leader_round', 'asc')
        ->orderBy('id', 'asc')
        ->get();

    if ($eligibleStudents->isEmpty()) {
        return []; // Ensure an array is returned
    }

    // Select one male and one female leader
    $maleLeader = $eligibleStudents->where('gender', 'M')->first();
    $femaleLeader = $eligibleStudents->where('gender', 'F')->first();

    $leaders = collect([$maleLeader, $femaleLeader])->filter()->map(function ($leader) use ($companyId, $date, &$usedStudentIds) {
        // Mark leader as used
        $usedStudentIds[] = $leader->id;

        return [
            'student_id' => $leader->id,
            'company_id' => $companyId,
            'beat_date' => $date
        ];
    })->toArray();

    // Update beat_leader_round count for selected leaders
    foreach ($leaders as $leader) {
        Student::where('id', $leader['student_id'])->increment('beat_leader_round');
    }

    return $leaders; // Always return an array
}


/**
 * Function to Assign Reserves (6 Males, 4 Females, One Per Platoon)
 */

 function assignReserves($companyId, $date, &$usedStudentIds)
{
    // Check if reserves have already been assigned for the given date
    $existingReserves = BeatReserve::where('beat_date', $date)
        ->where('company_id', $companyId)
        ->exists();

    if ($existingReserves) {
        // Reserves already assigned for this date, return an empty array
        return [];
    }

    // Fetch already assigned student IDs for this company (Guard, Patrol, Leaders)
    $assignedStudentIds = Beat::where('date', $date)
        ->whereHas('guardArea', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
        ->orWhereHas('patrolArea', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })
        ->pluck('student_ids')
        ->map(fn($ids) => json_decode($ids, true))
        ->flatten()
        ->toArray();

    $assignedLeaderIds = BeatLeaderOnDuty::where('beat_date', $date)
        ->where('company_id', $companyId)
        ->pluck('student_id')
        ->toArray();

    $alreadyAssigned = array_merge($assignedStudentIds, $assignedLeaderIds, $usedStudentIds);

    // Fetch eligible students (ordered by beat_round and id)
    $eligibleStudents = Student::where('beat_status', 1)
        ->where('company_id', $companyId)
        ->whereNotIn('id', $alreadyAssigned) // Avoid duplication
        ->orderBy('beat_round', 'asc')
        ->orderBy('id', 'asc')
        ->get();

    if ($eligibleStudents->isEmpty()) {
        return []; // Ensure an array is returned
    }

    // Dynamically determine platoon groups A & B
    $totalPlatoons = $eligibleStudents->groupBy('platoon')->keys()->sort()->values();
$mid = floor($totalPlatoons->count() / 2);
$groupA = $totalPlatoons->slice(0, $mid)->values();
$groupB = $totalPlatoons->slice($mid)->values();
$currentGroup = (Carbon::parse($date)->day % 2 === 1) ? $groupA : $groupB;

// Group students by platoon in the current group
$studentsByPlatoonInGroup = $eligibleStudents->whereIn('platoon', $currentGroup)->groupBy('platoon');
$platoonsInGroup = $currentGroup->toArray();

$reserves = collect();
$maleReservesNeeded = 6;
$femaleReservesNeeded = 4;

// Ensure each platoon in the current group provides at least one male and one female if needed
foreach ($platoonsInGroup as $platoon) {
    if ($maleReservesNeeded == 0 && $femaleReservesNeeded == 0) {
        break;
    }

    $platoonStudents = $studentsByPlatoonInGroup[$platoon] ?? collect();
    
    // Select one male if needed and available
    if ($maleReservesNeeded > 0) {
        $platoonMales = $platoonStudents->where('gender', 'M')->take(1);
        foreach ($platoonMales as $male) {
            if ($maleReservesNeeded > 0 && $reserves->count() < 10) {
                $reserves->push($male);
                $maleReservesNeeded--;
                break;
            }
        }
    }
    
    // Select one female if needed and available
    if ($femaleReservesNeeded > 0) {
        $platoonFemales = $platoonStudents->where('gender', 'F')->take(1);
        foreach ($platoonFemales as $female) {
            if ($femaleReservesNeeded > 0 && $reserves->count() < 10) {
                $reserves->push($female);
                $femaleReservesNeeded--;
                break;
            }
        }
    }
}

// Ensure total reserves are 6 males and 4 females
$remainingReservesNeeded = 10 - $reserves->count();

// Select additional male and female reserves as needed from different platoons
$additionalMaleReserves = $eligibleStudents->where('gender', 'M')
    ->whereIn('platoon', $currentGroup)
    ->whereNotIn('id', $reserves->pluck('id'))
    ->take($maleReservesNeeded);

$additionalFemaleReserves = $eligibleStudents->where('gender', 'F')
    ->whereIn('platoon', $currentGroup)
    ->whereNotIn('id', $reserves->pluck('id'))
    ->take($femaleReservesNeeded);

$additionalReserves = $additionalMaleReserves->merge($additionalFemaleReserves)
    ->take($remainingReservesNeeded)
    ->values();

    
    $reserves = $reserves->merge($additionalReserves)->map(function ($student) use ($companyId, $date, &$usedStudentIds) {
        // Mark student as used
        $usedStudentIds[] = $student->id;

        return [
            'student_id' => $student->id,
            'company_id' => $companyId,
            'beat_date' => $date
        ];
    })->toArray();

    // Update beat_status for selected reserves (set to 2)
    Student::whereIn('id', collect($reserves)->pluck('student_id'))->update(['beat_status' => 2]);

    return $reserves; // Always return an array
}


    public function showBeat(Beat $beat)
    {
        $students = Student::whereIn('id', $beat->student_ids)->get();

        return view('beats.show', compact('beat', 'students'));
    }

     /**
     * Remove a beat.
     */
    public function destroy($id)
    {
        $beat = Beat::findOrFail($id);
        $beat->delete();
        return redirect()->route('beats.index')->with('success', 'Beat deleted successfully!');
    }
}
