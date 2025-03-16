<?php

namespace App\Http\Controllers;

use App\Models\CourseworkResult;
use App\Models\Course;
use App\Models\Student;
use App\Models\CourseWork;
use App\Models\Semester;
use App\Models\Programme;
use Illuminate\Http\Request;
use App\Imports\CourseworkResultImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Exception;

class CourseworkResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courseworkResults = CourseworkResult::all();
        $courses = Course::all();
        $programme = Programme::findOrFail(1);
        // dd($courseworkResults);
        return view('course_works_results.index', compact('courseworkResults', 'courses', 'programme'));
    }


    //     public function getResultsByCourse($courseId)
    // {
    //     try {
    //         \Log::info('getResultsByCourse method called with courseId: ' . $courseId); // Log for debugging

    //         $results = CourseworkResult::where('course_id', $courseId)->with(['student', 'course', 'coursework', 'semester'])->get();

    //         if ($results->isEmpty()) {
    //             return response()->json([], 200); // Return an empty array with a 200 OK status
    //         }

    //         return response()->json($results);
    //     } catch (\Exception $e) {
    //         \Log::error('Error fetching coursework results: ' . $e->getMessage()); // Log the error
    //         return response()->json(['message' => 'Internal Server Error'], 500);
    //     }
    // }

    public function getResultsByCourse($courseId)
    {
        try {
            \Log::info('getResultsByCourse method called with courseId: ' . $courseId); // Log for debugging

            $results = CourseworkResult::where('course_id', $courseId)
                ->with(['student', 'course', 'coursework', 'semester'])
                ->paginate(10); // Paginate the results, 10 per page

            return response()->json($results);
        } catch (\Exception $e) {
            \Log::error('Error fetching coursework results: ' . $e->getMessage()); // Log the error
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }


    public function coursework()
    {
        $user = auth()->user()->id;
        $studentId = Student::where('user_id', $user)->pluck('id');
        // $student = Student::find($studentId[0]);
        // $coursework = $student->coursework();

        $results = CourseworkResult::where('student_id', $studentId[0])
            ->with(['student', 'course', 'coursework', 'semester', 'programmeCourseSemester'])->get();

        $groupedBySemester = $results->groupBy('semester_id');

        // dd($groupedBySemester);

        return view('students.coursework.coursework', compact('groupedBySemester'));
    }


    public function summary($id)
    {
        $result = CourseworkResult::with(['student', 'course', 'coursework', 'semester', 'programmeCourseSemester'])
            ->findOrFail($id);

        return view('students.coursework.summary', compact('result'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Retrieve necessary data for the form (students, courses, course works, semesters)
        $students = Student::where('programme_id', 1)->where('session_programme_id', 4)->orderBy('first_name', 'ASC')->get();
        $courses = Course::all();
        $courseWorks = CourseWork::all();
        $semesters = Semester::all();

        return view('course_works_results.create', compact('students', 'courses', 'courseWorks', 'semesters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'coursework_id' => 'required|exists:course_works,id',
            'score' => 'required|integer',
            'semester_id' => 'required|exists:semesters,id',
        ]);

        CourseworkResult::create($request->all());

        return redirect()->route('coursework_results.index')->with('success', 'Coursework Result created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseworkResult $courseworkResult)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseworkResult $courseworkResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseworkResult $courseworkResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseworkResult $courseworkResult)
    {
        //
    }

    public function create_import($courseId)
    {
        $semesters = Semester::all();
        $courseworks = CourseWork::all();
        return view('course_works_results.upload_explanation', compact('semesters', 'courseworks', 'courseId'));
    }
    public function import(Request $request, $courseId)
    {
        $request->validate([
            'semesterId' => 'required|exists:semesters,id', // Corrected 'exist' to 'exists' and fixed the table name
            'courseworkId' => 'required|exists:course_works,id'
        ]);

        $semesterId = $request->semesterId;
        $courseworkId = $request->semesterId;
        ;
        $validator = Validator::make($request->all(), [
            'import_file' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!in_array($value->getClientOriginalExtension(), ['csv', 'xls', 'xlsx'])) {
                        $fail('Incorrect :attribute type choose.');
                    }
                }
            ],
        ]);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }
        try {
            // Perform the import
            Excel::import(new CourseworkResultImport($semesterId, $courseId, $courseworkId), $request->file('import_file'));

            // Return success message after import is successful
            return redirect()->route('coursework_results.index')->with('success', 'Coursework results Uploaded  successfully.');
        } catch (Exception $e) {
            // If an error occurs during import, catch the exception and return the error message
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
        //Excel::import(new CourseworkResultImport($semesterId, $courseId, $courseworkId), filePath: $request->file('import_file'));
        //return redirect()->route('coursework_results.index')->with('success', 'Coursework results Uploaded  successfully.');
    }

    public function downloadSample()
    {
        $path = storage_path('app/public/sample/coursework_result.xlsx');
        if (file_exists($path)) {
            return response()->download($path);
        }
        abort(404);
    }
}
