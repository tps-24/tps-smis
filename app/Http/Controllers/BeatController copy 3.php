<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beat;
use App\Models\Student;
use App\Models\BeatType;
use App\Models\GuardArea;
use App\Models\PatrolArea;
use Illuminate\Support\Facades\Log;
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

    /**
     * Show details of a specific beat.
     */
    public function show($id)
    {
        $beat = Beat::with(['guardArea', 'patrolArea'])->findOrFail($id);
        $studentIds = json_decode($beat->student_ids, true);
        $students = Student::whereIn('id', $studentIds)->get();
        return view('beats.show', compact('beat', 'students'));
    }

    /**
     * Generate beats for guards and patrols.
     */
    public function generateBeats(Request $request)
    {
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

        $date = $request->input('date', today()->toDateString());

        // Fetch active students and group by platoon, sorted by beat_round (lowest first)
        $activeStudents = Student::where('beat_status', 1)
            ->orderBy('beat_round', 'asc') // Prioritize students with the lowest beat_round
            ->where('session_programme_id', 1)
            ->get()
            ->groupBy('platoon');

        // Fetch guard and patrol areas
        $guardAreas = GuardArea::all();
        $patrolArea = PatrolArea::all();

            
            foreach ($guardAreas as $guardArea) {
                foreach ($guardShifts as $shift) {
                    // Track assigned students to prevent duplication on the same day
                    $alreadyAssigned = Beat::where('date', $date)
                    ->get()
                    ->flatMap(fn ($beat) => json_decode($beat->student_ids, true))
                    ->unique();
                    
                    $eligibleStudents = $activeStudents->values()->flatten()->filter(function ($student) use ($guardAreas, $alreadyAssigned) {
                        foreach ($guardAreas as $guardArea) {
                            if ($student->company_id === $guardArea->company_id 
                                && !in_array($student->id, $alreadyAssigned->toArray())) {
                                return true;
                            }
                        }
                        return false;
                    })->groupBy('platoon');

                    $assignedStudents = $this->assignStudentsToArea($guardArea, $eligibleStudents->values(), $guardArea->number_of_guards);
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
                    foreach($assignedStudents as $student){
                        ++$student->beat_round;
                        //$student->save();                        
                    }
                    // $eligibleStudents = $eligibleStudents->reject(function ($eligibleStudent) use($assignedStudents) {
                    //     foreach($assignedStudents as $student){
                    //         dd($student);
                    //         return $eligibleStudent->id == $student->id;
                    //     }
                    //    // return $item->id === 1; // Replace with your condition
                    // });
                    
                }
                
                $alreadyAssigned = $alreadyAssigned->merge($assignedStudents);
                //Student::whereIn('id', $assignedStudents)->increment('beat_round');
            }



        // Process Patrol Beats
        // foreach ($patrolAreas as $patrolArea) {
        //     $requiredPatrols = $patrolArea->number_of_guards;

        //     $eligibleStudents = $activeStudents->flatten()->filter(function ($student) use ($patrolArea, $alreadyAssigned) {
        //         return $student->company === $patrolArea->company_id
        //             && !in_array($student->id, $alreadyAssigned->toArray())
        //             && $this->passesGenderRestrictions($student, $patrolArea);
        //     })->groupBy('platoon');

        //     $assignedStudents = collect();
        //     foreach ($eligibleStudents as $platoon => $group) {
        //         $assignedStudents = $assignedStudents->merge($group->take(intval($requiredPatrols / max(1, $eligibleStudents->count()))));
        //     }

        //     $assignedStudents = $assignedStudents->take($requiredPatrols)->pluck('id');

        //     if ($assignedStudents->count() === $requiredPatrols) {
        //         Beat::create([
        //             'beatType_id' => BeatType::where('name', 'Patrols')->first()->id,
        //             'patrolArea_id' => $patrolArea->id,
        //             'student_ids' => json_encode($assignedStudents->toArray()),
        //             'date' => $date,
        //             'start_at' => '18:00',
        //             'end_at' => '00:00',
        //             'status' => 1
        //         ]);

        //         $alreadyAssigned = $alreadyAssigned->merge($assignedStudents);
        //         Student::whereIn('id', $assignedStudents)->increment('beat_round');
        //     }
        // }

        return redirect()->route('beats.index')->with('success', "Beats generated successfully for $date!");
    }

    /**
     * Helper function to check gender restrictions.
     */
    private function passesGenderRestrictions($student, $area)
    {
        if (!$area->beat_exception_ids) return true; // No restrictions

        $exceptions = json_decode($area->beat_exception_ids, true);
        // if (in_array('Female only', $exceptions) && $student->gender !== 'F') return false;
        // if (in_array('Male only', $exceptions) && $student->gender !== 'M') return false;
        // if (in_array('Both but not in Pair', $exceptions)) {
        //     // Ensure an equal number of male & female students are not assigned together
        //     return true; // Implement more logic if necessary
        // }
        return true;
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
    private function assignStudentsToArea($area, $eligibleStudents, $requiredGuards)
    {
        $filteredStudents = collect();
        foreach($eligibleStudents as $platoonStudents){
           $filteredStudents = $filteredStudents->merge($platoonStudents->filter(function ($student) use ($area){
                return $student->company_id == $area->company_id;
            }));
        }
        // $filteredStudents = $eligibleStudents->filter(function ($students) use ($area) {
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
}
