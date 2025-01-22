<?php

namespace App\Http\Controllers;

use App\Models\Programme;
use App\Models\Course;
use App\Models\Semester;
use App\Models\SessionProgramme;
use App\Models\ProgrammeCourseSemester;
use Illuminate\Http\Request;

class ProgrammeCourseSemesterController extends Controller
{
    public function index()
    {
        $semesterId = 1;
        $sessionProgrammeId = 4;
        $programmeId = 1;
        $programme = Programme::findOrFail(1);

        $programme = Programme::with(['courses' => function($query) use ($semesterId, $sessionProgrammeId) {
            $query->wherePivot('semester_id', $semesterId)
                  ->wherePivot('session_programme_id', $sessionProgrammeId);
        }])->findOrFail($programmeId);

        $semester = Semester::findOrFail($semesterId);
        $sessionProgramme = SessionProgramme::findOrFail($sessionProgrammeId);
        $courses = $programme->courses;


        $vcourses = $programme->courses()->wherePivot('semester_id', 1)
                            ->wherePivot('session_programme_id', 4)
                            ->get();
                            
        $courseses = ProgrammeCourseSemester::all();
        // dd($courses);

        return view('course_assignments.index', compact('programme', 'semester', 'sessionProgramme', 'courses'));
    }

    public function create()
    {
        $programme = Programme::findOrFail(1);
        $semester = Semester::findOrFail(1);
        $sessionProgramme = SessionProgramme::findOrFail(4);
        $courses = Course::all();

        return view('course_assignments.create', compact('programme', 'semester', 'sessionProgramme', 'courses'));
    }

    public function store(Request $request)
    {
        $programme = Programme::findOrFail(1);

        $courseIds = $request->input('course_ids');
        $courseType = $request->input('course_type'); 
        $creditWeight = $request->input('credit_weight'); 
        $sessionProgrammeId = $request->input('session_programme_id_'); 
        $programmeId = $request->input('programme_id_ '); 
        $semesterId = $request->input('semester_id_'); 

        foreach ($courseIds as $courseId) {
            $programme->courses()->attach($courseId, [
                'semester_id' => $semesterId,
                'course_type' => $courseType,
                'credit_weight' => $creditWeight,
                'session_programme_id' => $sessionProgrammeId
            ]);
        }

        return redirect()->route('assign-courses.index', [$programmeId, $semesterId, $sessionProgrammeId])
                         ->with('success', 'Courses assigned successfully');
    }

    public function edit($programmeId, $semesterId, $sessionProgrammeId, $courseId)
    {
        $programme = Programme::findOrFail($programmeId);
        $semester = Semester::findOrFail($semesterId);
        $sessionProgramme = SessionProgramme::findOrFail($sessionProgrammeId);
        $course = Course::findOrFail($courseId);

        return view('course_assignments.edit', compact('programme', 'semester', 'sessionProgramme', 'course'));
    }

    public function update(Request $request, $programmeId, $semesterId, $sessionProgrammeId, $courseId)
    {
        $programme = Programme::findOrFail($programmeId);

        $programme->courses()->updateExistingPivot($courseId, [
            'semester_id' => $semesterId,
            'course_type' => $request->input('course_type'),
            'credit_weight' => $request->input('credit_weight'),
            'session_programme_id' => $sessionProgrammeId
        ]);

        return redirect()->route('assign-courses.index', [$programmeId, $semesterId, $sessionProgrammeId])
                         ->with('success', 'Course updated successfully');
    }

    public function destroy($programmeId, $semesterId, $sessionProgrammeId, $courseId)
    {
        $programme = Programme::findOrFail($programmeId);

        $programme->courses()->detach($courseId, [
            'semester_id' => $semesterId,
            'session_programme_id' => $sessionProgrammeId
        ]);

        return redirect()->route('assign-courses.index', [$programmeId, $semesterId, $sessionProgrammeId])
                         ->with('success', 'Course removed successfully');
    }
}
