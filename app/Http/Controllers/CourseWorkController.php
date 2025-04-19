<?php

namespace App\Http\Controllers;

use App\Models\CourseWork;
use App\Models\Course;
use App\Models\Semester;
use App\Models\AssessmentType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class CourseWorkController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:coursework-create')->only(['create', 'store']);
        $this->middleware('permission:coursework-list')->only(['index', 'show']);
        $this->middleware('permission:coursework-update')->only(['edit', 'update']);
        $this->middleware('permission:coursework-delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function getCourse($courseId){
        $course = Course::findOrFail($courseId);
        $assessmentTypes = AssessmentType::all();
        return view('course_works.index', compact('course','assessmentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($courseId)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $coursePivot =  $course->semesters[0]->pivot;
        $request->validate([
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'coursework_title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('courseworks')->where(function ($query) use ($request, $courseId) {
                    return $query->where('course_id', $courseId) // Check within the same course
                                ->where('assessment_type_id', $request->assessment_type_id); // Check within the same assessment type
                }),
            ],
            'max_score' => 'required|integer|min:1',
            'due_date' => 'nullable|date',
        ]);

        CourseWork::create([
            'course_id' => $course->id,
            'semester_id' => $coursePivot->semester_id,
            'assessment_type_id' => $request->assessment_type_id,
            'coursework_title' => $request->coursework_title,
            'max_score' => $request->max_score,
            'due_date' => $request->due_date?? NULL,
            'session_programme_id' =>$coursePivot->session_programme_id,
            'created_by' => $request->user()->id
        ]);

        return redirect()->back()->with('success', 'Assessment type added successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(CourseWork $courseWork)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseWork $courseWork)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseWork $courseWork)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseWork $courseWork)
    {
        //
    }

    public function getCourseworksxx($semesterId){
        
    Log::info("Fetching courseworks for semester ID: {$semesterId}");
        $semester = Semester::findOrFail($semesterId);
        return response()->json($semester->courseWorks);
    }

    public function getCourseworks($semesterId, $courseId)
    {
        Log::info("Fetching courseworks for semester ID: {$semesterId} and course Id: {$courseId} ");
        
        if (!$courseId) {
            return response()->json(['error' => 'Course ID not found in session'], 400);
        }
    
        // Find the semester
        $semester = Semester::findOrFail($semesterId);
    
        // Retrieve courseworks filtered by both semester_id and course_id
        $courseworks = Coursework::where('semester_id', $semesterId)
            ->where('course_id', $courseId)
            ->get(['id', 'coursework_title']);
    
        return response()->json($courseworks);
    }
     

}
