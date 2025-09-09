<?php

namespace App\Http\Controllers;

use App\Models\SemesterExam;
use Illuminate\Http\Request;

class SemesterExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
                Rule::unique('course_works')->where(function ($query) use ($request, $courseId) {
                    return $query->where('course_id', $courseId) // Check within the same course
                                ->where('assessment_type_id', $request->assessment_type_id); // Check within the same assessment type
                }),
            ],
            'max_score' => 'required|integer|min:1',
            'due_date' => 'nullable|date',
        ]);

        SemesterExam::create([
            'programme_id' => $coursePivot->programme_id,
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
    public function show(SemesterExam $semesterExam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SemesterExam $semesterExam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SemesterExam $semesterExam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SemesterExam $semesterExam)
    {
        //
    }

    public function semExams($semesterId, $courseId)
    {
        Log::info("Fetching semester exam for semester ID: {$semesterId} and course Id: {$courseId} ");
        
        if (!$courseId) {
            return response()->json(['error' => 'Course ID not found in session'], 400);
        }
    
        // Find the semester
        $semester = Semester::findOrFail($semesterId);
    
        // Retrieve semester exam filtered by both semester_id and course_id
        $courseworks = SemesterExam::where('semester_id', $semesterId)
            ->where('course_id', $courseId)
            ->get(['id', 'coursework_title']);
    
        return response()->json($courseworks);
    }

}
