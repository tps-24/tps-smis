<?php
namespace App\Http\Controllers;

use App\Imports\CourseExamResultImport;
use App\Models\Course;
use App\Models\Programme;
use App\Models\Semester;
use App\Models\SemesterExam;
use App\Models\FinalResult;
use App\Models\SemesterExamResult;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class SemesterExamResultController extends Controller
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
    public function show(SemesterExamResult $semesterExamResult)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SemesterExamResult $semesterExamResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SemesterExamResult $semesterExamResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SemesterExamResult $semesterExamResult)
    {
        //
    }

   
    public function getExamResultsByCourse($courseId, $semesterId)
{
    $selectedSessionId = session('selected_session',4); // Default to 4 if not set

    // Get course info
    $course = DB::table('courses')
        ->where('id', $courseId)
        ->first();

    // Fetch paginated semester exam results
    $results =  SemesterExamResult::whereHas('semesterExam', function ($query) use ($courseId, $semesterId, $selectedSessionId) {
            $query->where('course_id', $courseId)
                ->where('semester_id', $semesterId)
                ->where('session_programme_id', $selectedSessionId);
        })
        ->with(['student', 'semesterExam'])
        ->paginate(10);

    // Check if final results exist
    $hasFinalResults = FinalResult::with('student')
        ->where('course_id', $courseId)
        ->where('semester_id', $semesterId)
        ->whereHas('student', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId);
        })
        ->exists();

    // Return JSON with paginator included
    return response()->json([
        'course'          => $course ?? [],
        'hasFinalResults' => $hasFinalResults,
        'results'         => $results, // send paginator directly
    ]);
}

}
