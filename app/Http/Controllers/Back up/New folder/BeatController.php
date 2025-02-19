<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\GuardArea;
use App\Models\PatrolArea;
use App\Models\Beat;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Barryvdh\DomPDF\Facade\Pdf;

class BeatController extends Controller
{
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

    public function generatePDF($companyId)
    {
        $date = Carbon::today()->toDateString();

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

        $pdf = Pdf::loadView('beats.pdf', compact('company', 'date'))
            ->setPaper('a4', 'landscape') // Set the paper orientation to landscape
            ->setOptions(['margin-top' => 6, 'margin-bottom' => 2]); // Set the top and bottom margins

        return $pdf->download('beats_' . $company->name . '_' . $date . '.pdf');
    }


function generateBeats($areas, $studentsByCompany, $studentsByPlatoon, $beatType, $date)
{
    $beats = [];
    $assignedStudents = []; // Track assigned students to avoid duplication
    $platoonAssignments = []; // Track assignments by platoon

    foreach ($areas as $areaData) {
        $area = $areaData['area'];
        $startAt = $areaData['start_at'];
        $endAt = $areaData['end_at'];
        $company_id = $area->company_id;
        $requiredStudents = $area->number_of_guards;
        $assignedStudentIds = [];
        $platoonAssignments[$area->id] = []; // Initialize platoon assignments for the area

        // Fetch students for the company, ordered by beat_round and shuffle them
        $companyStudents = $studentsByCompany[$company_id] ?? collect();
        $companyStudents = $companyStudents->sortBy('beat_round')->values()->shuffle();

        // Apply Gender Restrictions
        if (isset($area->beat_exception_ids)) {
            $exceptions = json_decode($area->beat_exception_ids);

            if (in_array(1, $exceptions)) {
                $companyStudents = $companyStudents->where('gender', 'F');
            } elseif (in_array(2, $exceptions)) {
                $companyStudents = $companyStudents->where('gender', 'M');
            } elseif (in_array(3, $exceptions)) {
                $femaleStudents = $companyStudents->where('gender', 'F');
                $maleStudents = $companyStudents->where('gender', 'M');
                $companyStudents = $femaleStudents->merge($maleStudents);
            }
        } else {
            // Prioritize females during day and males at night
            if ($startAt === '06:00' || $startAt === '12:00') {
                $companyStudents = $companyStudents->where('gender', 'F');
            } else {
                $companyStudents = $companyStudents->where('gender', 'M');
            }
        }

        // Apply Platoon Ratios and shuffle them
        $platoonRatios = $companyStudents->groupBy('platoon')->map(function ($students) {
            return $students->shuffle();
        });

        // Assign students to the area ensuring mixed platoons
        $platoonCount = $platoonRatios->count();
        foreach ($platoonRatios as $platoon => $students) {
            foreach ($students as $student) {
                if (count($assignedStudentIds) < $requiredStudents &&
                    !in_array($student->id, $assignedStudents) &&
                    (!isset($platoonAssignments[$area->id][$platoon]) || $platoonAssignments[$area->id][$platoon] < ceil($requiredStudents / $platoonCount))
                ) {
                    $assignedStudentIds[] = $student->id;
                    $assignedStudents[] = $student->id;
                    $platoonAssignments[$area->id][$platoon] = ($platoonAssignments[$area->id][$platoon] ?? 0) + 1;
                }
            }
        }

        // Generate beat record
        if (!empty($assignedStudentIds)) {
            $beats[] = [
                'beatType_id' => ($beatType === 'guards') ? 1 : 2,
                'guardArea_id' => ($beatType === 'guards') ? $area->id : null,
                'patrolArea_id' => ($beatType === 'patrols') ? $area->id : null,
                'student_ids' => json_encode($assignedStudentIds),
                'date' => $date,
                'start_at' => $startAt,
                'end_at' => $endAt,
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
    }

    return $beats;
}


public function fillBeats(Request $request)
{
    $date = $request->input('date', Carbon::today()->toDateString());

    // Check if beats have already been generated for the date
    $existingBeats = Beat::where('date', $date)->exists();

    if ($existingBeats) {
        return response()->json(['message' => 'Beats already generated for ' . $date], 200);
    }

    // Step 1: Fetch Active Students
    $activeStudents = Student::where('beat_status', 1)->orderBy('beat_round')->get();

    // Step 2: Organize Students by Company and Platoon
    $studentsByCompany = $activeStudents->groupBy('company_id');
    $studentsByPlatoon = $activeStudents->groupBy('platoon');

    // Step 3: Fetch Guard and Patrol Areas
    $guardAreas = GuardArea::all();
    $patrolAreas = PatrolArea::all();

    // Step 4: Filter Areas by Time Exceptions
    $filteredGuardAreas = $this->filterAreasByTimeExceptions($guardAreas);
    $filteredPatrolAreas = $this->filterAreasByTimeExceptions($patrolAreas);

    // Step 5: Assign Students to Beats
    $beats = $this->generateBeats($filteredGuardAreas, $studentsByCompany, $studentsByPlatoon, 'guards', $date);
    $beats = array_merge($beats, $this->generateBeats($filteredPatrolAreas, $studentsByCompany, $studentsByPlatoon, 'patrols', $date));

    // Step 6: Insert Records into Beats Table and save student-beat relationship in pivot table
    DB::transaction(function () use ($beats) {
        foreach ($beats as $beatData) {
            $beat = Beat::create($beatData);
            $beat->students()->attach(json_decode($beatData['student_ids']));
        }
    });


    DB::transaction(function () use ($companyId, $date) {
        //Assign Leaders on Duty
        assignLeadersOnDuty($companyId, $date);

        //Assign Reserves
        assignReserves($companyId, $date);
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
function assignLeadersOnDuty($companyId, $date)
{
    // Get active students (beat_status = 1), sorted by id & beat_round
    $students = Student::where('company_id', $companyId)
        ->where('beat_status', 1)
        ->orderBy('id', 'asc')
        ->orderBy('beat_round', 'asc')
        ->orderBy('beat_leader_round', 'asc')
        ->get();

    // Select first male (RC) and first female (WRC)
    $male = $students->where('gender', 'Male')->first();
    $female = $students->where('gender', 'Female')->first();

    if (!$male || !$female) {
        return; // Not enough candidates
    }

    // Prevent duplicate leaders
    if (!BeatLeaderOnDuty::where('company_id', $companyId)->where('beat_date', $date)->exists()) {
        BeatLeaderOnDuty::insert([
            ['student_id' => $male->id, 'company_id' => $companyId, 'beat_date' => $date, 'created_at' => now(), 'updated_at' => now()],
            ['student_id' => $female->id, 'company_id' => $companyId, 'beat_date' => $date, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

/**
 * Function to Assign Reserves (6 Males, 4 Females, One Per Platoon)
 */
function assignReserves($companyId, $date)
{
    $students = Student::where('company_id', $companyId)
        ->where('beat_status', 1)
        ->orderBy('id', 'asc')
        ->orderBy('beat_round', 'asc')
        ->get();

    $maleReserves = $students->where('gender', 'Male')->unique('platoon')->take(6);
    $femaleReserves = $students->where('gender', 'Female')->unique('platoon')->take(4);

    $reserves = $maleReserves->merge($femaleReserves);

    foreach ($reserves as $student) {
        BeatReserve::create([
            'student_id' => $student->id,
            'company_id' => $companyId,
            'beat_date' => $date,
        ]);
    }
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
