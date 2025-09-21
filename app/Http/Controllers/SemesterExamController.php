<?php

namespace App\Http\Controllers;

use App\Models\SemesterExam;
use Illuminate\Http\Request;
use App\Models\Programme;
use App\Models\Semester;

use App\Imports\CourseExamResultImport;
use App\Models\Course;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class SemesterExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $programme = Programme::findOrFail(1);
        $userId    = $request->user()->id;
        $user      = $request->user();
        if (
            $user->hasRole('Super Administrator') ||
            $user->hasRole('Academic Coordinator') ||
            $user->hasRole('Chief Instructor') ||
            $user->hasRole('Head of Department')) {
            $semesters = Semester::with('courses')->get();

        } else if ($request->user()->hasRole('Instructor')) {
            $semesters = Semester::with(['courses' => function ($query) use ($userId) {
                $query->whereHas('courseInstructors', function ($subQuery) use ($userId) {
                    $subQuery->where('user_id', $userId);
                });
            }])->get();
        } else {
            $semesters = [];
        }
        $selectedSemesterId = $request->get('semester_id');
        $selectedSemester   = $selectedSemesterId ? Semester::with('courses')->find($selectedSemesterId) : null;

        return view('semester_exams.index', compact('programme', 'semesters', 'selectedSemester'));
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
    // public function store(Request $request, $courseId)
    // {
    //     $course = Course::findOrFail($courseId);
    //     $coursePivot =  $course->semesters[0]->pivot;
    //     $request->validate([
    //         'assessment_type_id' => 'required|exists:assessment_types,id',
    //         'coursework_title' => [
    //             'required',
    //             'string',
    //             'max:255',
    //             Rule::unique('course_works')->where(function ($query) use ($request, $courseId) {
    //                 return $query->where('course_id', $courseId) // Check within the same course
    //                             ->where('assessment_type_id', $request->assessment_type_id); // Check within the same assessment type
    //             }),
    //         ],
    //         'max_score' => 'required|integer|min:1',
    //         'due_date' => 'nullable|date',
    //     ]);

    //     SemesterExam::create([
    //         'programme_id' => $coursePivot->programme_id,
    //         'course_id' => $course->id,
    //         'semester_id' => $coursePivot->semester_id,
    //         'assessment_type_id' => $request->assessment_type_id,
    //         'coursework_title' => $request->coursework_title,
    //         'max_score' => $request->max_score,
    //         'due_date' => $request->due_date?? NULL,
    //         'session_programme_id' =>$coursePivot->session_programme_id,
    //         'created_by' => $request->user()->id
    //     ]);

    //     return redirect()->back()->with('success', 'Assessment type added successfully.');
    // }

    public function store(Request $request, $courseId)
    {
        //return $request->all();
        $course      = Course::findOrFail($courseId);
        $coursePivot = $course->semesters[0]->pivot;
        $request->validate([
            'max_score' => 'required|integer|min:1',
            'exam_date' => 'required|date',
            Rule::unique('courses')->where(function ($query) use ($request, $courseId) {
                return $query->where('id', $courseId)                                // Check within the same course
                    ->where('session_programme_id', $coursePivot->session_programme_id); // Check within the same assessment type
            }),
        ]);

        SemesterExam::create([
            'course_id'            => $course->id,
            'semester_id'          => $coursePivot->semester_id,
            'assessment_type_id'   => $request->assessment_type_id,
            'max_score'            => $request->max_score,
            'exam_date'            => $request->exam_date,
            'session_programme_id' => $coursePivot->session_programme_id,
            'created_by'           => $request->user()->id,
        ]);

        return redirect()->back()->with('success', 'Course Exam configured successfully.');
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
        return $semesterExam;
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

     public function getUploadExplanation($courseId, $semesterId)
    {
        $course = Course::find($courseId);
        return view('semester_exams.upload_explanation', compact('course', 'semesterId'));
    }

    public function uploadResults(Request $request, $courseId)
    {
        $course    = Course::find($courseId);
        $validator = Validator::make($request->all(), [
            'import_file' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (! in_array($value->getClientOriginalExtension(), ['csv', 'xls', 'xlsx'])) {
                        $fail('Incorrect :attribute type. Please upload a CSV, XLS, or XLSX file.');
                    }
                },
            ],
        ]);

        // dd('Excel import executed successfully');
        if ($validator->fails()) {
            // Return an error response if validation fails
            return back()->with('error', $validator->errors()->first());
        }

        try {
            // Perform the import using the provided Excel file
            Excel::import(new CourseExamResultImport($courseId, $request->semesterId), $request->file('import_file'));

            // Redirect with success message after successful import
            return redirect()->route('semester_exams.index')->with('success', 'Course exam results uploaded successfully.');
        } catch (Exception $e) {
            // Catch any errors during the import process and return an error response
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
        return $course;
    }

    public function configure($courseId)
    {
        $course = Course::find($courseId);
        return view('semester_exams.configurations.index', compact('course'));
    }

}
