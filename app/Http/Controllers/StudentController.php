<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use App\Imports\BulkImportStudents;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $students = Student::latest()->paginate(5);
        $page_name = "Student Management";
        return view('students.index',compact('students', 'page_name'))
         ->with('i', ($request->input('page', 1) - 1) * 5);;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $page_name = "Create new Student";
        return view('students.create',compact('page_name'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'rank' => 'required',
            'education_level' => 'required',
            'first_name' => 'required|max:30|alpha|regex:/^[A-Z]/',
            'last_name'=> 'required|max:30|alpha|regex:/^[A-Z]/',
            'middle_name'=> 'required|max:30|alpha|regex:/^[A-Z]/',
            'phone' => 'nullable|numeric|digits:10|unique:students',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'home_region' => 'required|string|min:4',
            'nin'=> 'required|numeric|digits:20|',
            'dob' => 'required|string',
            'gender' => 'required|max:1|alpha|regex:/^[M,F]/',
            'company' => 'required|max:2|alpha',
            'platoon' => 'required|max:1',
            'blood_group' => 'required|max:2'  
        ]);
        if ($validator->errors()->any()){
            return redirect()->back()->withErrors($validator->errors());//->with('success',$validator->errors());
        }
        
        $student = Student::create([
            //questions(user created first or)
            'education_level'=> $request->education_level,
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

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $student = Student::find($id);
        $page_name = "More Student Details";
        return view('students.show',compact('student','page_name'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $student = Student::find($id);
        $page_name = "Edit Student Details";
        return view('students.edit',compact('student', 'page_name'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        
        $student = Student::find($id);
        
        $validator = Validator::make($request->all(),[
            'rank' => 'required',
            'education_level' => 'required',
            'first_name' => 'required|max:30|alpha|regex:/^[A-Z]/',
            'last_name'=> 'required|max:30|alpha|regex:/^[A-Z]/',
            'middle_name'=> 'required|max:30|alpha|regex:/^[A-Z]/',
            'phone' => 'nullable|numeric|unique:students,phone,'.$student->id.',id',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'home_region' => 'required|string|min:4',
            'nin'=> 'required|numeric|unique:students,nin,'.$student->id.',id',
            'dob' => 'required|string',
            'gender' => 'required|max:1|alpha|regex:/^[M,F]/',
            'company' => 'required|max:2|alpha',
            'platoon' => 'required|max:1',
            'blood_group' => 'required|max:2'  
        ]);
        if ($validator->errors()->any()){
            return redirect()->back()->withErrors($validator->errors());//->with('success',$validator->errors());
        }
//dd($request->all());
        $student->update([
            'force_number' => $request->force_number,
            'education_level'=> $request->education_level,
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
      $validator=   Validator::make($request->all(),[
            'import_file' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!in_array($value->getClientOriginalExtension(), ['csv', 'xls', 'xlsx'])) {
                        $fail('Incorrect :attribute type choose.');
                    }
                }
            ],
        ]);
        if($validator->fails()){
            return back()->with('success',$validator->errors()->first());
        }
        Excel::import(new BulkImportStudents, filePath: $request->file('import_file'));
        return back()->with('success', 'Students Uploaded  successfully.');
    }

    public function postStepOne(Request $request)
    {
        $validatedData = $request->validate([
            'education_level' => 'required',
            'first_name' => 'required|max:30|alpha|regex:/^[A-Z]/',
            'last_name'=> 'required|max:30|alpha|regex:/^[A-Z]/',
            'middle_name'=> 'required|max:30|alpha|regex:/^[A-Z]/',
            'home_region' => 'required|string|min:4',
        ]);
  
        if(empty($request->session()->get('student'))){
            $student = new Student();
            $student->fill($validatedData);
            $request->session()->put('student', $student);
        }else{
            $student = $request->session()->get('student');
            $student->fill($validatedData);
            $request->session()->put('student', $student);
        }
        
        return redirect('students/create/step-two');
    }

    public function createStepTwo(Request $request)
    {
        $student = $request->session()->get('student');
  
        return view('students.wizards.stepTwo',compact('student'));
    }

    public function postStepTwo(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => 'nullable|numeric|digits:10|unique:students',
            'nin'=> 'required|numeric|unique:students',
            'dob' => 'required|string',
            'gender' => 'required|max:1|alpha|regex:/^[M,F]/',
            'company' => 'required|max:2|alpha',
            'platoon' => 'required|max:1',
        ]);
        $student = $request->session()->get('student');
        $student->fill($validatedData);
       $request->session()->put('student', $student);
        return redirect('students/create/step-three');
        
    }

    public function createStepThree(Request $request)
    {
        $student = $request->session()->get('student');
  
        return view('students.create-step-three',compact('student'));
    }
 
    public function postStepThree(Request $request)
    {
        $validatedData = $request->validate([
            'next_kin_phone' => 'nullable|numeric|digits:10',
             'next_kin_names'=> 'required|max:30',
             'next_kin_address' => 'required|string|min:4',
             'next_kin_relationship' => 'required|string|min:4',
        ]);
  
        $student = $request->session()->get('student');
        $student->fill($validatedData);
        $request->session()->put('student', $student);
        $student = $request->session()->get('student');
        $student->save();
  
        $request->session()->forget('student');
        return redirect()->route('students.index')->with('success','Student created successfully.');
    }
}
