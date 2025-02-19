<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beat;
use App\Models\Student;
use App\Models\GuardArea;
use App\Models\PatrolArea;
use App\Models\BeatType;
use Carbon\Carbon;

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

    public function generateBeats($date = null)
    {
        $date = $date ?? now()->toDateString(); // Default to today if no date is provided

        // Define shift times for guards and patrols
        $guardShifts = [
            ['start' => '06:00', 'end' => '12:00'],
            ['start' => '12:00', 'end' => '18:00'],
            ['start' => '18:00', 'end' => '00:00'],
            ['start' => '00:00', 'end' => '06:00']
        ];

        $patrolShifts = [
            ['start' => '18:00', 'end' => '00:00'],
            ['start' => '00:00', 'end' => '06:00']
        ];

        $activeStudents = Student::where('beat_status', 1)
            ->where('session_programme_id', 1)
            ->orderBy('beat_round', 'asc')
            ->get()
            ->groupBy('company_id');


        // Fetch all guard and patrol areas
        $guardAreas = GuardArea::all();
        $patrolAreas = PatrolArea::all();

        foreach ($guardAreas as $area) {
            foreach ($guardShifts as $shift) {
                if (!$this->shouldGenerateForArea($area, $shift['start'])) {
                    continue; // Skip if area shouldn't have a beat for this time
                }

                $students = $this->assignStudentsToArea($area, $shift, $activeStudents);
                if ($students->isNotEmpty()) {
                    Beat::create([
                        'beatType_id' => BeatType::where('name', 'Guards')->first()->id,
                        'guardArea_id' => $area->id,
                        'patrolArea_id' => null,
                        'student_ids' => json_encode($students->pluck('id')->toArray()),
                        'date' => $date,
                        'start_at' => $shift['start'],
                        'end_at' => $shift['end'],
                        'status' => 1,
                    ]);
                    $this->updateStudentRounds($students);
                }
            }
        }

        foreach ($patrolAreas as $area) {
            foreach ($patrolShifts as $shift) {
                if (!$this->shouldGenerateForArea($area, $shift['start'])) {
                    continue;
                }

                $students = $this->assignStudentsToArea($area, $shift, $activeStudents);
                if ($students->isNotEmpty()) {
                    Beat::create([
                        'beatType_id' => BeatType::where('name', 'Patrols')->first()->id,
                        'guardArea_id' => null,
                        'patrolArea_id' => $area->id,
                        'student_ids' => json_encode($students->pluck('id')->toArray()),
                        'date' => $date,
                        'start_at' => $shift['start'],
                        'end_at' => $shift['end'],
                        'status' => 1,
                    ]);
                    $this->updateStudentRounds($students);
                }
            }
        }

        return "Beats generated successfully for $date.";
    }

    private function shouldGenerateForArea($area, $startTime)
    {
        $beatTimeExceptions = json_decode($area->beat_time_exception_ids, true) ?? [];
        
        if (empty($beatTimeExceptions)) {
            return true; // If no time restriction, all shifts are valid
        }

        $timeExceptions = [
            '06:00' => 1,
            '12:00' => 2,
            '18:00' => 3,
            '00:00' => 4,
        ];

        return in_array($timeExceptions[$startTime] ?? null, $beatTimeExceptions);
    }

    // private function assignStudentsToArea($area, $shift, $activeStudents)
    // {
    //     $companyStudents = $activeStudents[$area->company_id] ?? collect();
        
        
    //     $filteredStudents = $companyStudents->reject(function ($student) use ($area, $shift) {
    //         return $this->studentViolatesRestrictions($student, $area, $shift);
    //     });


    //     return $this->distributeStudentsByPlatoon($filteredStudents, $area->number_of_guards ?? 2);
    // }

    private function assignStudentsToArea($area, $activeStudents, $requiredGuards)
    {
        $filteredStudents = collect();
        foreach($activeStudents as $platoonStudents){
           $filteredStudents = $filteredStudents->merge($platoonStudents->filter(function ($student) use ($area){
                return $student->company_id == $area->company_id;
            }));
        }
        // $filteredStudents = $activeStudents->filter(function ($students) use ($area) {
        //     foreach($students as $student)
        //         return $student->company_id == $area->company_id;
        //     //if ($student->company_id !== $area->company_id) return false;

        //     // $genderRestrictions = json_decode($area->beat_exception_ids, true) ?? [];
        //     // if (in_array('Male only', $genderRestrictions) && $student->gender !== 'M') return false;
        //     // if (in_array('Female only', $genderRestrictions) && $student->gender !== 'F') return false;

        //     //return true;
        // });
        $platoonGroups = $filteredStudents->groupBy('platoon');
        $platoonCount = $platoonGroups->count();
        if ($platoonCount == 0) {
            return collect(); // No students match criteria
        }

        $assignedStudents = collect();
        $basePerPlatoon = intdiv($platoonCount,$requiredGuards); // Minimum students per platoon
        $extraSlots = $requiredGuards % $platoonCount; // Leftover slots to distribute
        foreach ($platoonGroups as $platoon => $group) {
            $takeCount = $basePerPlatoon + ($extraSlots > 0 ? 1 : 0);
            $extraSlots--;
            
            $assignedStudents = $assignedStudents->merge($group->take($takeCount));
        }
        
        return $assignedStudents->take($requiredGuards);
    }



    private function studentViolatesRestrictions($student, $area, $shift)
    {
        $beatExceptions = json_decode($area->beat_exception_ids, true) ?? [];

        // Gender restrictions
        if (in_array(1, $beatExceptions) && $student->gender !== 'F') return true;
        if (in_array(2, $beatExceptions) && $student->gender !== 'M') return true;

        // Prioritize females in day shifts and males in night shifts
        if (empty($beatExceptions)) {
            if (($shift['start'] == '06:00' || $shift['start'] == '12:00') && $student->gender !== 'F') {
                return true;
            }
            if (($shift['start'] == '18:00' || $shift['start'] == '00:00') && $student->gender !== 'M') {
                return true;
            }
        }

        return false;
    }


    private function distributeStudentsByPlatoon($students, $requiredGuards)
    {
        $requiredGuards = 400;
        $platoonGroups = $students->groupBy('platoon');
        $assignedStudents = collect();

        
        foreach ($platoonGroups as $platoon => $group) {
            $assignedStudents = $assignedStudents->merge($group->take(intval($requiredGuards / $platoonGroups->count())));
        }

        
        // dd($assignedStudents);
        return $assignedStudents->take($requiredGuards);
    }

    
    private function updateStudentRounds($students)
    {
        foreach ($students as $student) {
            // $student->increment('beat_round');
        }
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
