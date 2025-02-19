<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beat;
use App\Models\Student;
use App\Models\GuardArea;
use App\Models\PatrolArea;
use App\Models\BeatType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BeatController extends Controller
{
    /**
     * Display a list of beats.
     */
    public function index()
    {
        $beats = Beat::with(['guardArea', 'patrolArea'])->orderBy('date', 'desc')->get();
        return view('beats.index', compact('beats'));
    }

    /**
     * Generate beats for a given date.
     */
    public function generate(Request $request)
    {
        $date = $request->input('date', today()->toDateString());

        DB::transaction(function () use ($date) {
            $guardShifts = [
                ['start_at' => '06:00', 'end_at' => '12:00'],
                ['start_at' => '12:00', 'end_at' => '18:00'],
                ['start_at' => '18:00', 'end_at' => '00:00'],
                ['start_at' => '00:00', 'end_at' => '06:00']
            ];

            $patrolShifts = [
                ['start_at' => '18:00', 'end_at' => '00:00'],
                ['start_at' => '00:00', 'end_at' => '06:00']
            ];

            $students = Student::where('beat_status', 1)->get();
            Log::info('Active Students:', $students->toArray());
            $guardAreas = GuardArea::all();
            $patrolAreas = PatrolArea::all();
            
            foreach ($guardAreas as $guardArea) {
                foreach ($guardShifts as $shift) {
                    $assignedStudents = $this->assignStudentsToArea($guardArea, $students, $guardArea->number_of_guards);
                    
            // dd($assignedStudents);
                    if ($assignedStudents->isEmpty()) continue;
                    

                    Beat::create([
                        'beatType_id' => BeatType::where('name', 'Guards')->first()->id,
                        'guardArea_id' => $guardArea->id,
                        'patrolArea_id' => null,
                        'student_ids' => json_encode($assignedStudents->pluck('id')->toArray()),
                        'date' => $date,
                        'start_at' => $shift['start_at'],
                        'end_at' => $shift['end_at'],
                        'status' => 1
                    ]);
                }
            }

            foreach ($patrolAreas as $patrolArea) {
                foreach ($patrolShifts as $shift) {
                    $assignedStudents = $this->assignStudentsToArea($patrolArea, $students, $patrolArea->number_of_guards);
                    if ($assignedStudents->isEmpty()) continue;

                    Beat::create([
                        'beatType_id' => BeatType::where('name', 'Patrol')->first()->id,
                        'guardArea_id' => null,
                        'patrolArea_id' => $patrolArea->id,
                        'student_ids' => json_encode($assignedStudents->pluck('id')->toArray()),
                        'date' => $date,
                        'start_at' => $shift['start_at'],
                        'end_at' => $shift['end_at'],
                        'status' => 1
                    ]);
                }
            }
        });

        return redirect()->route('beats.index')->with('success', 'Beats generated successfully.');
    }

    /**
     * Helper function to assign students to an area.
     */
    private function assignStudentsToArea($area, $students, $requiredGuards)
    {
        $filteredStudents = $students->filter(function ($student) use ($area) {
            if ($student->company_id !== $area->company_id) return false;

            // $genderRestrictions = json_decode($area->beat_exception_ids, true) ?? [];
            // if (in_array('Male only', $genderRestrictions) && $student->gender !== 'M') return false;
            // if (in_array('Female only', $genderRestrictions) && $student->gender !== 'F') return false;

            return true;
        });

        $platoonGroups = $filteredStudents->groupBy('platoon');
        $platoonCount = $platoonGroups->count();

        if ($platoonCount == 0) {
            return collect(); // No students match criteria
        }

        $assignedStudents = collect();
        $basePerPlatoon = intdiv($requiredGuards, $platoonCount); // Minimum students per platoon
        $extraSlots = $requiredGuards % $platoonCount; // Leftover slots to distribute

        foreach ($platoonGroups as $platoon => $group) {
            $takeCount = $basePerPlatoon + ($extraSlots > 0 ? 1 : 0);
            $extraSlots--;

            $assignedStudents = $assignedStudents->merge($group->take($takeCount));
        }

        return $assignedStudents->take($requiredGuards);
    }


    /**
     * Delete a beat.
     */
    public function destroy($id)
    {
        $beat = Beat::findOrFail($id);
        $beat->delete();
        return redirect()->route('beats.index')->with('success', 'Beat deleted successfully.');
    }
}
