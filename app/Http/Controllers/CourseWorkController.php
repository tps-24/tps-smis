<?php

namespace App\Http\Controllers;

use App\Models\CourseWork;
use App\Models\Semester;
use App\Models\AssessmentType;
use Illuminate\Http\Request;

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
    public function index()
    {
        $courseWorks = CourseWork::all();
        return view('course_works.index', compact('courseWorks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assessmentTypes = AssessmentType::get();
        return view('course_works.create', compact('assessmentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'programme_id' => 'required|exists:programmes,id',
            'course_id' => 'required|exists:courses,id',
            'semester_id' => 'required|exists:semesters,id',
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'coursework_title' => 'required|string',
            'max_score' => 'required|integer',
            'due_date' => 'nullable|date',
            'session_programme_id' => 'required|exists:session_programmes,id',
        ]);

        CourseWork::create($request->all());

        return redirect()->route('course_works.index')->with('success', 'Coursework created successfully.');
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
    public function getCourseworks($semesterId){
        $semester = Semester::findOrFail($semesterId);
        return response()->json($semester->courseWorks);
    }
}
