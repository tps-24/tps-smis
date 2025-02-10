<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\User;
use App\Models\Company;
use App\Models\Programme;
use App\Models\CourseworkResult;
use App\Imports\BulkImportStudents;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Http\RedirectResponse;
use DB;
use Hash;

class StudentController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:student-list|student-create|student-edit|student-delete', ['only' => ['index', 'view','search']]);
        $this->middleware('permission:student-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:student-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:student-delete', ['only' => ['destroy']]);
        $this->middleware('permission:student-list|student-create|student-edit|student-delete', ['only' => ['import']]);
    }

    // public function dashboard(){
    //     $user = Auth::user();
    //     return view('students/dashboard', compact('user'));
    // }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedSessionId = session('selected_session');
        if (!$selectedSessionId)
            $selectedSessionId = 1;
        $students = Student::where('session_programme_id', $selectedSessionId)->latest()->paginate(20);
        $page_name = "Student Management";
        return view('students.index', compact('students', 'page_name'))
            ->with('i', ($request->input('page', 1) - 1) * 20);
        
    }

    public function search(Request $request)
    {
        $selectedSessionId = session('selected_session');
        if (!$selectedSessionId)
            $selectedSessionId = 1;
        $students = Student::where('session_programme_id', $selectedSessionId)
                    ->where('company', $request->company)
                    ->where('platoon',$request->platoon)
                    ->orderBy('first_name')
                    ->latest()->paginate(90);
        return view('students.index', compact('students'))
            ->with('i', ($request->input('page', 1) - 1) * 90);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $page_name = "Create new Student";
        return view('students.create', compact('page_name'));
    }

    public function createPage()
    {
        $programmes = Programme::get();
        return view('students.self.register', compact('programmes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'rank' => 'required',
            'education_level' => 'required',
            'first_name' => 'required|max:30|alpha|regex:/^[A-Z]/',
            'last_name' => 'required|max:30|alpha|regex:/^[A-Z]/',
            'middle_name' => 'required|max:30|alpha|regex:/^[A-Z]/',
            'phone' => 'nullable|numeric|digits:10|unique:students',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'home_region' => 'required|string|min:4',
            'nin' => 'required|numeric|digits:20|',
            'dob' => 'required|string',
            'gender' => 'required|max:1|alpha|regex:/^[M,F]/',
            'company' => 'required|max:2|alpha',
            'platoon' => 'required|max:1',
            'blood_group' => 'required|max:2'
        ]);
        if ($validator->errors()->any()) {
            return redirect()->back()->withErrors($validator->errors());//->with('success',$validator->errors());
        }

        $student = Student::create([
            //questions(user created first or)
            'education_level' => $request->education_level,
            'rank' => $request->rank,
            'force_number' => $request->force_number,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'nin' => $request->nin,
            'home_region' => $request->home_region,
            'phone' => $request->phone,
            'height' => $request->height,
            'weight' => $request->weight,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'company' => $request->company,
            'platoon' => $request->platoon,
            'blood_group' => $request->blood_group,
        ]);
        return redirect()->route('students.index')->with('success', "Student created successfully.");
    }

    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'force_number' => 'required|regex:/^[A-Z]{1,2}\.\d+$/|unique:students,force_number',
            'first_name' => 'required|max:50|alpha|regex:/^[A-Z]/',
            'middle_name' => 'required|max:50|alpha|regex:/^[A-Z]/',
            'last_name' => 'required|max:50|alpha|regex:/^[A-Z]/',
            'nin' => 'required|numeric|unique:students,nin',
            'dob' => 'required|date',
            'programme_id' => 'required|string|max:255',
            'email' => 'required|string|email|max:100|unique:users',
            'gender' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        
        $input = $request->all();
        $password = Hash::make($input['password']);
        $fullName = $request->first_name. ' ' . $request->middle_name. ' ' . $request->last_name;
    

        //Create User First
        $user = User::create([
            'name' => $fullName,
            'email' => $request->email,
            'password' => $password,
        ]);

        // Assign the 'student' role to the user
        $user->assignRole('student');

        $input['user_id'] = $user->id;
        
        // dd($input);
        //Now Create Student
        $student = new Student([
            'force_number' => $request->input('force_number'),
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'nin' => $request->input('nin'),
            'dob' => $request->input('dob'),
            'programme_id' => $request->input('programme_id'),
            'email' => $request->input('email'),
            'gender' => $request->input('gender'),
            'user_id' =>  $user->id,
            'password' => Hash::make($request->input('password')),
        ]);

        $student->save();

        return redirect()->back()->with('success', 'Your successfully created an account!');

        // return redirect()->route('students.dashboard')->with('success', "Your successfully created an account.");
    
    }


    public function myCourses()
    {
        $user = auth()->user()->id;
        $studentId = Student::where('user_id', $user)->pluck('id');
        $student = Student::find($studentId[0]);
        $courses = $student->courses();
    
        // $role = auth()->user()->role;
        // dd($courses);
        
        return view('students.mycourse', compact('courses'));
    }

    public function dashboard()
    {
        $user = auth()->user()->id;
        $student = Student::where('user_id', $user)->get();

        return view('dashboard.student_dashboard', compact('student'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $student = Student::find($id);
        $page_name = "More Student Details";
        return view('students.show', compact('student', 'page_name'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $student = Student::find($id);
        $request->session()->put('student', $student);
        $page_name = "Edit Student Details";
        //return redirect("/students/create/");
        return view('students/wizards/stepOne', compact('student'));
        // return view('students.edit',compact('student', 'page_name'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $student = Student::find($id);

        $validator = Validator::make($request->all(), [
            'rank' => 'required',
            'education_level' => 'required',
            'first_name' => 'required|max:30|alpha|regex:/^[A-Z]/',
            'last_name' => 'required|max:30|alpha|regex:/^[A-Z]/',
            'middle_name' => 'required|max:30|alpha|regex:/^[A-Z]/',
            'phone' => 'nullable|numeric|unique:students,phone,' . $student->id . ',id',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'home_region' => 'required|string|min:4',
            'nin' => 'required|numeric|unique:students,nin,' . $student->id . ',id',
            'dob' => 'required|string',
            'gender' => 'required|max:1|alpha|regex:/^[M,F]/',
            'company' => 'required|max:2|alpha',
            'platoon' => 'required|max:1',
            'blood_group' => 'required|max:2'
        ]);
        if ($validator->errors()->any()) {
            return redirect()->back()->withErrors($validator->errors());//->with('success',$validator->errors());
        }
        $student->update([
            'force_number' => $request->force_number,
            'education_level' => $request->education_level,
            'rank' => $request->rank,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'nin' => $request->nin,
            'home_region' => $request->home_region,
            'phone' => $request->phone,
            'height' => $request->height,
            'weight' => $request->weight,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'company' => $request->company,
            'platoon' => $request->platoon,
            'blood_group' => $request->blood_group,
        ]);
        //dd($request->all());
        return redirect('students')->with('success', "Student updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $student = Student::find($id);
        $student->delete();
        return redirect('students')->with('success', "Student deleted successfully.");

    }

    public function import(Request $request)
    {
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
        Excel::import(new BulkImportStudents, filePath: $request->file('import_file'));
        return back()->with('success', 'Students Uploaded  successfully.');
    }

    public function postStepOne(Request $request, $type)
    {
        $student_validate_rule = "";
        if ($type == "edit") {
            $student = Student::findOrFail($request->id);
            $student_validate_rule = '|unique:students,force_number,' . $student->id . ',id';
        } else {
            $student_validate_rule = '|unique:students,force_number';
        }

        $validatedData = $request->validate([
            'force_number' => 'nullable|regex:/^[A-Z]{1,2}\.\d+$/' . $student_validate_rule,
            'rank' => 'required',
            'education_level' => 'required',
            'first_name' => 'required|max:30|alpha|regex:/^[A-Z]/',
            'last_name' => 'required|max:30|alpha|regex:/^[A-Z]/',
            'middle_name' => 'required|max:30|alpha|regex:/^[A-Z]/',
            'home_region' => 'required|string|min:4',
        ]);
        $companies = Company::all();
        if (empty($request->session()->get('student'))) {
            $student = new Student();
            $student->fill($validatedData);
            $request->session()->put('student', $student);
        } else {

            $student = $request->session()->get('student');

            $student['force_number'] = $validatedData['force_number'];
            $student['rank'] = $validatedData['rank'];
            $student['education_level'] = $validatedData['education_level'];
            $student['first_name'] = $validatedData['first_name'];
            $student['middle_name'] = $validatedData['middle_name'];
            $student['last_name'] = $validatedData['last_name'];
            $student['home_region'] = $validatedData['home_region'];

            $request->session()->put('student', $student);
        }
        if ($type == "edit") {
            return view('students.wizards.stepTwo', compact('student'));

        }
        return view('students.wizards.stepTwo', compact('companies', 'type'));

    }

    public function createStepTwo(Request $request)
    {
        $student = $request->session()->get('student');
        return view('students.wizards.stepTwo', compact('student'));
    }

    public function postStepTwo(Request $request, $type)
    {
        $student = $request->session()->get('student');
        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|numeric|digits:10|unique:students,phone,' . $student->id . ',id',
            'nin' => 'required|digits:20|numeric|unique:students,nin,' . $student->id . ',id',
            'dob' => 'required|string',
            'gender' => 'required|max:1|alpha|regex:/^[M,F]/',
            'company' => 'required|max:2|alpha',
            'platoon' => 'required|max:2',
            'weight' => 'required|numeric',
            'height' => 'required|numeric'
        ]);
        if ($validator->errors()->any()) {
            return view('students/wizards/stepTwo', compact('student'))->withErrors($validator->errors());
        }
        $student['phone'] = $request->phone;
        $student['nin'] = $request->nin;
        $student['dob'] = $request->dob;
        $student['gender'] = $request->gender;
        $student['company'] = $request->company;
        $student['platoon'] = $request->platoon;
        $student['weight'] = $request->weight;
        $student['height'] = $request->height;
        $request->session()->put('student', $student);
        if ($type == "create") {
            return redirect('students/create/step-three/create');
        }
        return view('students.wizards.stepThree', compact('student'));

    }

    public function createStepThree(Request $request)
    {
        $student = $request->session()->get('student');
        return view('students.wizards.stepThree', compact('student'));
    }

    public function postStepThree(Request $request, $type)
    {
        $validatedData = $request->validate([
            'next_kin_phone' => 'nullable|numeric|digits:10',
            'next_kin_names' => 'required|max:30',
            'next_kin_address' => 'required|string|min:4',
            'next_kin_relationship' => 'required|string|min:4',
        ]);

        $student = $request->session()->get('student');
        $student['next_kin_phone'] = $validatedData['next_kin_phone'];
        $student['next_kin_names'] = $validatedData['next_kin_names'];
        $student['next_kin_address'] = $validatedData['next_kin_address'];
        $student['next_kin_relationship'] = $validatedData['next_kin_relationship'];
        if ($type == 'create') {
            $selectedSessionId = session('selected_session');
            if (!$selectedSessionId)
                $selectedSessionId = 1;
            $student['session_programme_id'] = $selectedSessionId;
        }
        //$student->fill($validatedData);
        $student->save();
        //$request->session()->put('student', $student);
        $student = $request->session()->get('student');
        $request->session()->forget('student');
        //return $student;
        if ($type == "edit") {
            $message = "Student updated successfully.";
        } else {
            $message = "";
        }
        return redirect()->route('students.index')->with('success', $message);
        ;
    }
}
