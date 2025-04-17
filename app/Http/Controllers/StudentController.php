<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\User;
use App\Models\Company;
use App\Models\SafariType;
use App\Models\Programme;
use App\Models\CourseworkResult;
use App\Imports\BulkImportStudents;
use App\Imports\UpdateStudentDetails;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Http\RedirectResponse;
use DB;
use Hash;
use Exception;
use Illuminate\Support\Facades\Log; // Namespace for the Log facade

use Barryvdh\DomPDF\Facade as PDF;

// use Barryvdh\DomPDF\Facade\Pdf;


class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:student-list|student-create|student-edit|student-delete', ['only' => ['index', 'view', 'search']]);
        $this->middleware('permission:student-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:student-create', ['only' => ['create', 'store', 'createStepOne', 'postStepOne', 'createStepTwo', 'postStepTwo', 'createStepThree', 'postStepThree', 'import']]);
        $this->middleware('permission:student-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:student-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedSessionId = session('selected_session');
        if (!$selectedSessionId)
            $selectedSessionId = 1;
        $students = Student::where('session_programme_id', $selectedSessionId)->orderBy('company_id')->orderBy('platoon')->paginate(20);
        $companies = Company::whereHas('students', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId); // Filter students by session
        })->get();
        return view('students.index', compact('students', 'companies'))
            ->with('i', ($request->input('page', 1) - 1) * 20);
    }

    public function search(Request $request)
    {
        $selectedSessionId = session('selected_session');
        if (!$selectedSessionId)
            $selectedSessionId = 1;
        $students = Student::where('session_programme_id', $selectedSessionId)
                    ->where('company_id', $request->company_id)
                    ->where('platoon',$request->platoon)
                    ->orderBy('first_name');
                    //->latest()->paginate(90);
        //return view('students.index', compact('students'))
            //->with('i', ($request->input('page', 1) - 1) * 90);
            // ->where('company_id', $request->company_id)
            // ->where('platoon', $request->platoon);

        if ($request->name) {
            $students = $students->where(function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->name . '%')
                    ->orWhere('last_name', 'like', '%' . $request->name . '%')
                    ->orWhere('middle_name', 'like', '%' . $request->name . '%');
            });
        }
        $companies = Company::all();
        $students = $students->latest()->paginate(90);
        return view('students.index', compact('students','companies'))
            ->with('i', ($request->input('page', 1) - 1) * 90);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {        
        return view('students.wizards.stepOne');
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
            'first_name' => 'required|max:50|regex:/^[A-Z][a-zA-Z\s\-\'\.\,]*$/',
            'middle_name' => 'required|max:50|regex:/^[A-Z][a-zA-Z\s\-\'\.\,]*$/',
            'last_name' => 'required|max:50|regex:/^[A-Z][a-zA-Z\s\-\'\.\,]*$/',
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
            'rank' => $request->input('rank'),
            'dob' => $request->input('dob'),
            'programme_id' => $request->input('programme_id'),
            'session_programme_id' => $request->input('session_programme_id'),
            'email' => $request->input('email'),
            'gender' => $request->input('gender'),
            'user_id' =>  $user->id,
            'password' => Hash::make($request->input('password')),
        ]);

        $student->save();

        // return redirect()->back()->with('success', 'Your successfully created an account!');

        return redirect()->route('login')->with('success', "Your successfully created an account.");
    
    }


    public function myCourses()
    {
        $user = auth()->user()->id;
        $studentId = Student::where('user_id', $user)->pluck('id');
        $student = Student::find($studentId[0]);
        $courses = $student->courses();
    
        // $role = auth()->user()->role;
        // dd($courses);
        

        // $role = auth()->user()->role;
        // dd($courses);

        return view('students.mycourse', compact('courses'));
    }

    //Haitumiki for now
    public function dashboard()
    {
        $student = auth()->user()->id;
        $pending_message = session('message');
        // $pending_message = "Your account is pending for approval.";

        // dd($pending_message);

        return view('dashboard.student_dashboard', compact('pending_message'));
    }

     /**
     * Displaying user profile..
     */
    public function profile($id):View
    {
        $user = User::find($id);
        return view('students.profile',compact('user'));
    }

    public function approveStudent($id)
    {
        $student = Student::findOrFail($id);
        $student->approve();

        return redirect()->route('students.index')->with('success', 'Student approved successfully.');
    }
    
    /**
     * Display the specified resource.
     */

    public function show($id)
    {
        $student = Student::find($id);
        $safari_types = SafariType::all();
        return view('students.show', compact('student', 'safari_types'));
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
    public function updatex(Request $request, $id)
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
     * Update the specified student in storage.
     */
    public function completeProfile($id)
    {        
        $student = Student::where('id', $id)->first();
        // dd($student);
        return view('students.complete_profile', compact('student'));
    }

    public function profileComplete(Request $request, $id)
    {
        // dd($request->file('photo'));
        $student = Student::findOrFail($id);
        $request->validate([
            'education_level' => 'required',
            'home_region' => 'required|string|min:4',
            'phone' => 'nullable|numeric|unique:students,phone,' . $student->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'next_of_kin' => 'nullable|array',
            'next_of_kin.*.name' => 'nullable|string',
            'next_of_kin.*.phone' => 'nullable|string',
            'next_of_kin.*.relationship' => 'nullable|string',
            'next_of_kin.*.address' => 'nullable|string',
            'company' => 'required',
            'platoon' => 'required',
        ]);

        // if ($request->hasFile('photo')) {
        //     $photoFile = $request->file('photo');
            
        //     // Check if the file was uploaded correctly
        //     if ($photoFile->isValid()) {
        //         // Store the file and get the path
        //         $photoPath = $photoFile->store('photos', 'public');
                
        //         // Check the stored path
        //         if ($photoPath) {
        //             $student->photo = $photoPath;
        //             \Log::info('Photo stored at: ' . $photoPath);
        //         } else {
        //             \Log::error('Failed to store photo.');
        //             return back()->with('error', 'Failed to store photo.');
        //         }
        //     } else {
        //         \Log::error('Uploaded file is not valid.');
        //         return back()->with('error', 'Uploaded file is not valid.');
        //     }
        // }
        

        // Handle the file upload and resize using GD library
        if ($request->hasFile('photo')) {
            $photoFile = $request->file('photo');
            Log::info('Photo file received.', ['file' => $photoFile->getClientOriginalName()]);

            $photoPath = 'photos/' . uniqid() . '.' . $photoFile->getClientOriginalExtension();
            Log::info('Generated photo path.', ['path' => $photoPath]);

            // Get the original dimensions and create the GD resource
            list($width, $height) = getimagesize($photoFile->getPathname());
            Log::info('Original dimensions.', ['width' => $width, 'height' => $height]);

            $src = null;
            switch ($photoFile->getClientOriginalExtension()) {
                case 'jpeg':
                case 'jpg':
                    $src = imagecreatefromjpeg($photoFile->getPathname());
                    break;
                case 'png':
                    $src = imagecreatefrompng($photoFile->getPathname());
                    break;
                case 'gif':
                    $src = imagecreatefromgif($photoFile->getPathname());
                    break;
                case 'svg':
                    // SVG handling would require additional libraries
                    break;
            }

            if ($src) {
                // Create a new true color image with the desired dimensions
                $dst = imagecreatetruecolor(150, 170);
                Log::info('Created new true color image.');

                // Resize and copy the original image to the new image
                imagecopyresampled($dst, $src, 0, 0, 0, 0, 150, 170, $width, $height);
                Log::info('Resized and copied the original image to the new image.');

                // Save the new image
                $saveSuccess = false;
                switch (strtolower($photoFile->getClientOriginalExtension())) {
                    case 'jpeg':
                    case 'jpg':
                        $saveSuccess = imagejpeg($dst, storage_path('app/public/' . $photoPath));
                        break;
                    case 'png':
                        $saveSuccess = imagepng($dst, storage_path('app/public/' . $photoPath));
                        break;
                    case 'gif':
                        $saveSuccess = imagegif($dst, storage_path('app/public/' . $photoPath));
                        break;
                }

                if ($saveSuccess) {
                    Log::info('Saved the new image.', ['path' => $photoPath]);
                    $student->photo = $photoPath;
                } else {
                    Log::error('Failed to save the new image.');
                    return back()->with('error', 'Failed to save the photo.');
                }

                // Free up memory
                imagedestroy($src);
                imagedestroy($dst);
                Log::info('Freed up memory.');
            } else {
                Log::error('Unsupported image format.');
                return back()->with('error', 'Unsupported image format.');
            }
        }


        // dd($student->photo = $photoPath);
        // Update other student attributes...
        // $student->update($request->all());
         // Update or Create NextOfKin
         Student::updateOrCreate(
            ['id' => $student->id], // This is the condition to find the existing record
            [
                'education_level' => $request->education_level,
                'home_region' => $request->home_region,
                'phone' => $request->phone,
                'photo' => $student->photo = $photoPath,
                'company' => $request->company,
                'platoon' => $request->platoon,
            ]
        );
        
        Log::info('Student saved successfully.', ['student' => $student]);


        // Update other student attributes...
        // $student->update($request->except('photo'));

        // Handle next-of-kin data
        $student->next_of_kin = $request->next_of_kin;
        $student->save();

        return redirect()->route('profile',$student->user_id)->with('success', 'Student updated successfully.');
        // return back()->with('success', 'Students updated  successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $student = Student::find($id);
        
        // Check if the student has a related user
        if ($student->user) {
            $student->user->delete();
        }
        
        // Delete the student
        $student->delete();
    
        return redirect('students')->with('success', "Student and related user deleted successfully.");
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
        try{
        Excel::import(new BulkImportStudents, filePath: $request->file('import_file'));
        }catch (Exception $e) {
            // If an error occurs during import, catch the exception and return the error message
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
        return redirect()->route('students.index')->with('success', 'Students Uploaded  successfully.');
    }


    public function updateStudents(Request $request)
    {
        // Check if a session is selected
        $selectedSessionId = session('selected_session');
        if (!$selectedSessionId) {
            return redirect()->back()->withErrors('Please select a session before updating students.');
        }

        $import = new UpdateStudentDetails();

        try {
            Excel::import($import, $request->file('students_file'));

            // Return with success, warnings, and errors
            return redirect()->back()->with([
                'success' => 'Student details updated successfully!',
                'warnings' => $import->warnings,
                'errors' => $import->errors,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }



    // public function createStepOne()
    // {
    //     return view('students.wizards.stepOne');
    // }
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
            //'rank' => 'nullable',
            //'education_level' => 'required',
            'first_name' => 'required|max:30|alpha|regex:/^[A-Z]/',
            'last_name' => 'required|max:30|alpha|regex:/^[A-Z]/',
            'middle_name' => 'required|max:30|alpha|regex:/^[A-Z]/',
            'home_region' => 'nullable|string|min:4',
            'rank' => 'required',
            'education_level' => 'required',
        ]);
        $companies = Company::all();
        if (empty($request->session()->get('student'))) {
            $student = new Student();
            $student->fill($validatedData);
            $request->session()->put('student', $student);
        } else {

            $student = $request->session()->get('student');

            $student['force_number'] = $validatedData['force_number'];
            //$student['rank'] = $validatedData['rank'];
            //$student['education_level'] = $validatedData['education_level'];
            $student['rank'] = $validatedData['rank'];
            $student['education_level'] = $validatedData['education_level'];
            $student['first_name'] = $validatedData['first_name'];
            $student['middle_name'] = $validatedData['middle_name'];
            $student['last_name'] = $validatedData['last_name'];
            $student['home_region'] = $validatedData['home_region'];

            $request->session()->put('student', $student);
        }
        if ($type == "edit") {
            return view('students.wizards.stepTwo', compact('student', 'companies'));

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
        $companies = Company::all();
        $student = $request->session()->get('student');
        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|numeric|digits:10|unique:students,phone,' . $student->id . ',id',
            'nin' => 'required|digits:20|numeric|unique:students,nin,' . $student->id . ',id',
            'dob' => 'required|string',
            'gender' => 'required|max:1|alpha|regex:/^[M,F]/',
            'company_id' => 'required|max:2|numeric',
            'platoon' => 'required|max:2',
            'weight' => 'required|numeric',
            'height' => 'required|numeric'
        ]);
        if ($validator->errors()->any()) {
            return view('students/wizards/stepTwo', compact('student', 'companies'))->withErrors($validator->errors());
        }
        $student['phone'] = $request->phone;
        $student['nin'] = $request->nin;
        $student['dob'] = $request->dob;
        $student['gender'] = $request->gender;
        $student['company_id'] = $request->company_id;
        $student['platoon'] = $request->platoon;
        $student['weight'] = $request->weight;
        $student['height'] = $request->height;
        $request->session()->put('student', $student);
        if ($type == "create") {
            return redirect('students/create/step-three/create');
        }
        return view('students.wizards.stepThree', compact('student','companies'));

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

    public function activate_beat_status($studentId){
        $student  = Student::find($studentId);

        $student -> beat_status = 1;
        $student->save();
        return redirect()->back()->with('success','Beat activated successfully.');
    }

    public function deactivate_beat_status($studentId){
        $student  = Student::find($studentId);

        $student -> beat_status = 0;
        $student->save();
         return redirect()->back()->with('success','Beat deactivated successfully.');
    }

    public function downloadSample () {
        $path = storage_path('app/public/sample/basic recruit course students.csv');
        if (file_exists($path)) {
            return response()->download($path);
        }
        abort(404);
    }

    public function updateFastStatus(Request $request, $studentId, $fastStatus){
        $student = Student::findOrFail($studentId);
        if(!$student){
            return redirect()->back()->with('success', 'Student with the '.$studentId.' Id is not found.');
        }

        if($fastStatus == 1){
            $student->fast_status = 1;
        }
        else if($fastStatus == 0){
            $student->fast_status = 0;
        }else{
            return redirect()->route('students.index')->with('success','Please specify fasting status.');
        }

        $student->save();


        return redirect()->route('students.index')->with('success','Fasting status updated successfully.');
    }

    public function toSafari($studentId){
        $student = Student::findOrFail($studentId);
        if(!$student){
            return redirect()->back()->with('error', 'Student with the '.$studentId.' Id is not found.');
        }

            $student->beat_status = 4;
        $student->save();   
        return redirect()->route('students.index')->with('success','Beat status to Safari updated successfully.');
    }

    public function BackFromsafari($studentId){
        $student = Student::findOrFail($studentId);
        if(!$student){
            return redirect()->back()->with('error', 'Student is not found.');
        }

            $student->beat_status = 1;
        $student->save();   
        return redirect()->route('students.index')->with('success','Beat status back from Safari updated successfully.');
    }
}