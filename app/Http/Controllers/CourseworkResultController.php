<?php
namespace App\Http\Controllers;

use App\Imports\CourseworkResultImport;
use App\Models\Course;
use App\Models\CourseWork;
use App\Models\CourseworkResult;
use App\Models\Programme;
use App\Models\Semester;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class CourseworkResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $programme = Programme::findOrFail(1);
        $userId             = $request->user()->id;
        $user = $request->user();
        if(
            $user->hasRole('Super Administrator') ||
            $user->hasRole('Academic Coordinator') ||
             $user->hasRole('Chief Instructor') ||
             $user->hasRole('Head of Department')){
        $semesters          = Semester::with('courses')->get();

        }
        else if($request->user()->hasRole('Instructor')){
        $semesters = Semester::with(['courses' => function ($query) use ($userId) {
            $query->whereHas('courseInstructors', function ($subQuery) use ($userId) {
                $subQuery->where('user_id', $userId);
            });
        }])->get();
        }else{
            $semesters = [];
        }
        $selectedSemesterId = $request->get('semester_id');
        $selectedSemester   = $selectedSemesterId ? Semester::with('courses')->find($selectedSemesterId) : null;


        return view('course_works_results.index', compact('programme', 'semesters', 'selectedSemester'));
    }

    public function getResultsByCourse_old($courseId)
    {
        try {
            // Fetch all coursework configurations for the given course
            $courseworks = DB::table('courseworks')
                ->where('course_id', $courseId)
                ->select('id', 'coursework_title')
                ->get();

            // Fetch coursework results with pagination
            $results = DB::table('coursework_results')
                ->join('students', 'coursework_results.student_id', '=', 'students.id')
                ->where('coursework_results.course_id', $courseId)
                ->select(
                    'coursework_results.student_id',
                    'coursework_results.coursework_id',
                    'coursework_results.score',
                    'students.force_number',
                    'students.first_name',
                    'students.middle_name',
                    'students.last_name'
                )
                ->paginate(10); // Limit results to 10 per page

            // Group results by student ID
            $groupedResults = collect($results->items())->groupBy('student_id')->map(function ($studentResults) use ($courseworks) {
                $studentData = $studentResults->first();
                $scores      = collect($studentResults)->pluck('score', 'coursework_id');
                $totalCW     = $scores->sum();

                return [
                    'student'  => [
                        'force_number' => $studentData->force_number,
                        'first_name'   => $studentData->first_name,
                        'middle_name'  => $studentData->middle_name,
                        'last_name'    => $studentData->last_name,
                    ],
                    'scores'   => $scores,
                    'total_cw' => $totalCW,
                ];
            });

            // Sort results by total_cw in descending order
            $sortedResults = $groupedResults->sortByDesc('total_cw');

            // If no results exist, handle empty data
            if ($sortedResults->isEmpty()) {
                return response()->json([
                    'courseworks' => $courseworks ?? [],
                    'results'     => [
                        'data'  => [],
                        'links' => [],
                    ],
                    'message'     => 'No results found for this course.',
                ]);
            }

            // Return JSON response with sorted results and pagination links
            return response()->json([
                'courseworks' => $courseworks ?? [],
                'results'     => [
                    'data'  => $sortedResults,
                    'links' => $results->toArray()['links'], // Provide pagination links
                ],
            ]);
        } catch (\Exception $e) {
            // Log error and return a server error response
            \Log::error('Error fetching coursework results:', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function getResultsByCourse($courseId)
    {
        try {
            \Log::info("Fetching results for course ID: {$courseId}");
            // Fetch all coursework configurations for the given course
            $courseworks = DB::table('courseworks')
                ->where('course_id', $courseId)
                ->select('id', 'coursework_title')
                ->get();

            // Fetch coursework results by linking through the coursework table
            $results = DB::table('coursework_results')
                ->join('students', 'coursework_results.student_id', '=', 'students.id')
                ->join('courseworks', 'coursework_results.coursework_id', '=', 'courseworks.id') // Establish the relationship
                ->where('courseworks.course_id', $courseId)                                      // Filter based on course_id in the coursework table
                ->select(
                    'coursework_results.student_id',
                    'students.force_number',
                    'students.first_name',
                    'students.middle_name',
                    'students.last_name',
                    DB::raw('SUM(coursework_results.score) as total_cw') // Calculate total CW
                )
                ->groupBy(
                    'coursework_results.student_id',
                    'students.force_number',
                    'students.first_name',
                    'students.middle_name',
                    'students.last_name'
                )
                ->orderByDesc('total_cw') // Sort by total CW
                ->paginate(10);           // Paginate results

            // Format results for the frontend
            $groupedResults = collect($results->items())->map(function ($studentResult) use ($courseworks) {
                $scores = DB::table('coursework_results')
                    ->where('student_id', $studentResult->student_id)
                    ->whereIn('coursework_id', $courseworks->pluck('id'))
                    ->pluck('score', 'coursework_id'); // Map scores by coursework ID

                return [
                    'student'  => [
                        'force_number' => $studentResult->force_number,
                        'first_name'   => $studentResult->first_name,
                        'middle_name'  => $studentResult->middle_name,
                        'last_name'    => $studentResult->last_name,
                    ],
                    'scores'   => $scores,
                    'total_cw' => $studentResult->total_cw,
                ];
            });

            // Handle empty results
            if ($groupedResults->isEmpty()) {
                return response()->json([
                    'courseworks' => $courseworks ?? [],
                    'results'     => [
                        'data'  => [],
                        'links' => [],
                    ],
                    'message'     => 'No results found for this course.',
                ]);
            }

            // Return formatted JSON response
            return response()->json([
                'courseworks' => $courseworks ?? [],
                'results'     => [
                    'data'  => $groupedResults,
                    'links' => $results->toArray()['links'], // Include pagination links
                ],
            ]);
        } catch (\Exception $e) {
            // Log and return error
            \Log::error('Error fetching coursework results:', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function coursework()
    {
        $user      = auth()->user()->id;
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
        $students    = Student::where('programme_id', 1)->where('session_programme_id', 4)->orderBy('first_name', 'ASC')->get();
        $courses     = Course::all();
        $courseWorks = CourseWork::all();
        $semesters   = Semester::all();

        return view('course_works_results.create', compact('students', 'courses', 'courseWorks', 'semesters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id'    => 'required|exists:students,id',
            'coursework_id' => 'required|exists:courseworks,id',
            'score'         => 'required|integer',
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
        $course = Course::findOrFail($courseId);
        return view('course_works_results.upload_explanation', compact('course'));
    }

    public function import(Request $request, $courseId)
    {
        // dd($request);
        // dd($request->file('import_file'));
        // dd(file_exists($request->file('import_file')->getRealPath()));

        // Validate the request input
        $request->validate([
            'courseworkId' => 'required|exists:courseworks,id',
            'import_file'  => 'required|file|mimes:csv,xls,xlsx|max:4048',
        ]);

        // Assigning variables correctly from the request
        $semesterId   = $request->semesterId;
        $courseworkId = $request->courseworkId;

        // dd($courseworkId);
        // Validate the import file format
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
            Excel::import(new CourseworkResultImport($courseworkId), $request->file('import_file'));

            // Redirect with success message after successful import
            return redirect()->route('coursework_results.index')->with('success', 'Coursework results uploaded successfully.');
        } catch (Exception $e) {
            // Catch any errors during the import process and return an error response
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function downloadSample()
    {
        $path = storage_path('app/public/sample/coursework_result.xlsx');
        if (file_exists($path)) {
            return response()->download($path);
        }
        abort(404);
    }
    public function getAssignedCourses()
    {
        $user = Auth::user();
        // $user = User::findOrFail($userId);

        // Retrieve the user instance

        // Retrieve all course instructors for this user and eagerly load related semester and course
        $courseInstructors = $user->courseInstructors()
            ->with('programmeCourseSemester.semester', 'course') // Eager loading for programmeCourseSemester and semester
            ->get();

        // Group the course instructors by semester name
        $groupedCourses = $courseInstructors->groupBy(function ($course_instructor) {
            // Check if the semester data is available, if not group under 'Unassigned'
            if ($course_instructor->programmeCourseSemester && $courseInstructor->programmeCourseSemester->semester) {
                return $course_instructor->programmeCourseSemester->semester->name;
            }
            // Return 'Unassigned' if semester data is missing
            return 'Unassigned';
        });

        // Add labels to the groups
        $groupedWithLabels = $groupedCourses->map(function ($group, $semesterName) {
            return [
                'label'   => "Courses in $semesterName", // Label for the group
                'courses' => $group->map(function ($courseInstructor) {
                    return $courseInstructor->course; // Only return the Course model
                }),
            ];
        });

        // Filter out the empty keys or groups
        $groupedWithLabels = $groupedWithLabels->filter(function ($group) {
            return $group['courses']->isNotEmpty(); // Only keep groups with courses
        });

        // Return the grouped courses with labels
        return $groupedWithLabels;
    }

}
