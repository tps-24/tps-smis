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

class BeatController extends Controller
{
      /**
     * Display a listing of beats.
     */
    public function index()
    {
        $beats = Beat::with(['guardArea', 'patrolArea'])->orderBy('date', 'desc')->get();
        return view('beats.index', compact('beats'));
    }

    public function fillBeats(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

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

        // Step 6: Insert Records into Beats Table
        DB::table('beats')->insert($beats);

        return response()->json(['message' => 'Beats filled successfully'], 200);
    }

    private function filterAreasByTimeExceptions($areas)
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

    private function generateBeats($areas, $studentsByCompany, $studentsByPlatoon, $beatType, $date)
    {
        $beats = [];
        $assignedStudents = []; // Track assigned students to avoid duplication

        foreach ($areas as $areaData) {
            $area = $areaData['area'];
            $startAt = $areaData['start_at'];
            $endAt = $areaData['end_at'];
            $company_id = $area->company_id;
            $requiredStudents = $area->number_of_guards;
            $assignedStudentIds = [];

            // Fetch students for the company
            $companyStudents = $studentsByCompany[$company_id] ?? collect();

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
                    $companyStudents = $companyStudents->where('gender', 'F')->merge($companyStudents->where('gender', 'M'));
                } else {
                    $companyStudents = $companyStudents->where('gender', 'M')->merge($companyStudents->where('gender', 'F'));
                }
            }

            // Apply Platoon Ratios
            $platoonRatios = $companyStudents->groupBy('platoon')->map(function ($students) {
                return $students->count();
            });

            foreach ($platoonRatios as $platoon => $count) {
                $platoonStudents = $companyStudents->where('platoon', $platoon);
                foreach ($platoonStudents as $student) {
                    if (count($assignedStudentIds) < $requiredStudents && !in_array($student->id, $assignedStudents)) {
                        $assignedStudentIds[] = $student->id;
                        $assignedStudents[] = $student->id;
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

    public function showBeats()
    {
        $companies = Company::with(['guardAreas.beats', 'patrolAreas.beats'])->get();

        return view('beats.index', compact('companies'));
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
