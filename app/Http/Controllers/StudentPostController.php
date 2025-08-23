<?php

namespace App\Http\Controllers;

use App\Models\StudentPost;
use App\Models\Company;
use App\Models\Student;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Imports\StudentPostImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Exception;

class StudentPostController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:post-view|post-create|post-edit|post-delete', ['only' => ['index', 'store', 'import']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedSessionId = session('selected_session');
        $companies = Company::whereHas('students', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId); // Filter students by session
        })->get();
        $posts = StudentPost::paginate(20);
        return view('students.posts.index', compact('posts','companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentPost $studentPost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentPost $studentPost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentPost $studentPost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentPost $studentPost)
    {
        //
    }

        public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'import_file' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (! in_array($value->getClientOriginalExtension(), ['csv', 'xls', 'xlsx'])) {
                        $fail('Incorrect :attribute type choose.');
                    }
                },
            ],
        ]);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }
        try {
            Excel::import(new StudentPostImport, filePath: $request->file('import_file'));
        } catch (Exception $e) {
            // If an error occurs during import, catch the exception and return the error message
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
        return redirect()->route('students-post.index')->with('success', 'Students post Uploaded/Updated  successfully.');
    }

    public function search(Request $request)
    {
        // Check if a session ID has been submitted
        if (request()->has('session_id')) {
            // Store the selected session ID in the session
            session(['selected_session' => request()->session_id]);
        }
        
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }

        // Build the student query
        $students = Student::where('session_programme_id', $selectedSessionId);

        if ($request->company_id) {
            $students->where('company_id', $request->company_id);

            if ($request->platoon) {
                $students->where('platoon', $request->platoon);
            }
        }

        if ($request->name) {
            $students->where(function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->name . '%')
                    ->orWhere('last_name', 'like', '%' . $request->name . '%')
                    ->orWhere('force_number', 'like', '%' . $request->name . '%')
                    ->orWhere('middle_name', 'like', '%' . $request->name . '%');
            });
        }

        // Get the IDs of the matching students
        $studentIds = $students->pluck('id');

        // Now fetch posts for these students
        $posts = Post::whereIn('student_id', $studentIds)->get();


        // Clone the query before pagination to get approved count
        

        $companies = Company::whereHas('students', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId);
        })->get();


        return view('students-post.index', compact('posts', 'companies'))
            ->with('i', ($request->input('page', 1) - 1) * 90);

    }
}
