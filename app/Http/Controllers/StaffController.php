<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Company;
use App\Models\User;
use App\Imports\BulkImportStaff;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Models\Department;
use App\Models\NextOfKin;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\StaffController;
use App\Models\EducationLevel;
use App\Models\School;
use App\Models\WorkExperience;

use DB;
use Hash;
use Auth;

class StaffController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:staff-list|staff-create|staff-edit|staff-delete', ['only' => ['index']]);
         $this->middleware('permission:staff-create', ['only' => ['create','store','import']]);
         $this->middleware('permission:staff-edit', ['only' => ['edit','update','updateProfile']]);
         $this->middleware('permission:staff-delete', ['only' => ['destroy']]);
         $this->middleware('permission:staff-view|profile-list', ['only' => ['profile','view']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $companies = Company::all();
        $staffs = Staff::orderBy('id','DESC')->paginate(10);
        return view('staffs.index',compact('staffs', 'companies'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::get();
        $roles = Role::pluck('name','name')->all();
        return view('staffs.create', compact('departments', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $this->validate($request, [
            'forceNumber' => 'required|unique:staff',
            'rank' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'gender' => 'required',
            'email' => 'required|email|unique:users,email',
            'roles' => 'required',
            'created_by' => 'required|exists:users,id',
        ]);

        // Process input
        $input = $request->all();
        $password = Hash::make(strtoupper($input['lastName']));
        $fullName = $request->firstName . ' ' . $request->middleName . ' ' . $request->lastName;

        // Create User
        $user = User::create([
            'name' => $fullName,
            'email' => $request->email,
            'password' => $password,
        ]);
        $user->assignRole($request->input('roles'));

        // Set user_id for staff
        $input['user_id'] = $user->id;

        // Create Staff
        $staff = Staff::create($input);

        // Check if Next of Kin full name is provided
        if (!empty($request->input('nextofkinFullname'))) {
            // Validate NextOfKin fields
            $this->validate($request, [
                'nextofkinFullname' => 'required',
                'nextofkinRelationship' => 'required',
                'nextofkinPhysicalAddress' => 'required',
            ]);

            // Create NextOfKin
            NextOfKin::Create([
                'nextofkinFullname' => $input['nextofkinFullname'],
                'nextofkinRelationship' => $input['nextofkinRelationship'],
                'nextofkinPhoneNumber' => $input['nextofkinPhoneNumber'],
                'nextofkinPhysicalAddress' => $input['nextofkinPhysicalAddress'],
                'staff_id' => $staff->id,
            ]);
        }

        return redirect()->route('staffs.index')->with('success', 'Staff created successfully.');
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
        Excel::import(new BulkImportStaff, filePath: $request->file('import_file'));
        return back()->with('success', 'Staff Uploaded  successfully.');
    }

    /**
     * Displaying user profile..
     */
    public function profile($id):View
    {
        $user = User::find($id);
        return view('staffs.profile',compact('user'));
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        return view('staffs.show', compact('staff'));
    }

    /**
     * Display the CV for a specific staff member.
     */
    // public function resume1()
    // {
    //     $id=1;
    //         $staff = Staff::with('department')->find(1);
        
    //         if (!$staff) {
    //             return abort(404, 'Staff member not found.');
    //         }
        
    //         return view('staffs.resume', compact('staff'));        
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {        
        // Ensure the roles relationship is loaded
        $staff->load('roles');
        
        $user = User::find($staff->user_id);

        $departments = Department::get();
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        $staffNextofkin = NextOfKin::where('staff_id', $staff->id)->first();

        return view('staffs.edit', compact('staff', 'departments','roles', 'userRole', 'staffNextofkin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        $this->validate($request, [
            'forceNumber' => 'required|unique:staff,forceNumber,' . $staff->id,
            'rank' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'gender' => 'required',
            // 'DoB' => 'required|date',
            // 'maritalStatus' => 'required',
            // 'phoneNumber' => 'required',
            'email' => 'required|unique:staff,email,' . $staff->id,
            'department_id' => 'required|integer',
            // 'roles' => 'required',
            // 'educationLevel' => 'required',
            // 'contractType' => 'required',
            // 'joiningDate' => 'required|date',
            // 'location' => 'required',
            'updated_by' => 'required|exists:users,id'
        ]);

        $id = Staff::where('id', $staff->id)->pluck('user_id');
        $fullName = $request->firstName . ' ' . $request->middleName . ' ' . $request->lastName;
        $input = $request->all();
        // $input = $request->only(['email', 'password']);
        // $input = Arr::except($input,array('password'));  

        $user = User::where('id', $id)->first();

        if ($user) {
            $user->update([
                'name' => $fullName,
                'email' => $input['email']
            ]);
        }

        DB::table('model_has_roles')->where('model_id', $id)->delete();
        
        $user->assignRole($request->input('roles'));

        $staff->update($request->all());

        // Check if Next of Kin full name is provided
        if (!empty($request->input('nextofkinFullname'))) {
            // Validate NextOfKin fields
            $this->validate($request, [
                'nextofkinFullname' => 'required',
                'nextofkinRelationship' => 'required',
                'nextofkinPhoneNumber' => 'required',
                'nextofkinPhysicalAddress' => 'required',
            ]);

        // Update or Create NextOfKin
        NextOfKin::updateOrCreate(
            ['staff_id' => $staff->id], // This is the condition to find the existing record
            [
                'nextofkinFullname' => $input['nextofkinFullname'],
                'nextofkinRelationship' => $input['nextofkinRelationship'],
                'nextofkinPhoneNumber' => $input['nextofkinPhoneNumber'],
                'nextofkinPhysicalAddress' => $input['nextofkinPhysicalAddress'],
                'staff_id' => $staff->id,
            ]
        );

        }

        return redirect()->route('staffs.index')->with('success', 'Staff updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        // First, delete the corresponding user
        $user = $staff->user; // Assuming there's a relationship defined between Staff and User
        if ($user) {
            $user->delete();
        }
    
        // Then, delete the staff member
        $staff->delete();
    
        return redirect()->route('staffs.index')->with('success', 'Staff and corresponding user deleted successfully.');
    }
    public function downloadSample () {
        $path = storage_path('app/public/sample/staff sample.xlsx');
        if (file_exists($path)) {
            return response()->download($path);
        }
        abort(404);
    }
    public function search(Request $request)
    {

        $staffs = Staff::where('company_id', $request->company_id)
                    ->orderBy('firstName');

        if ($request->name) {
            $staffs = $staffs->where(function ($query) use ($request) {
                $query->where('firstName', 'like', '%' . $request->name . '%')
                    ->orWhere('lastName', 'like', '%' . $request->name . '%')
                    ->orWhere('middleName', 'like', '%' . $request->name . '%');
            });
        }
        $companies = Company::all();
        $staffs = $staffs->latest()->paginate(10);
        return view('staffs.index',compact('staffs', 'companies'))
        ->with('i', ($request->input('page', 1) - 1) * 10);

    }

    public function generateResume($staffId)
    {
        $staff = Staff::findOrFail($staffId);
        return view('staffs.resume', compact('staff'));
    }
    
    public function generateResumexx($id)
    {
        // Fetch the staff record and its associated user
        $staff = Staff::with('user')->findOrFail($id);

        // Fetch related data using the user relation
        $user = $staff->user;

        $workExperiences = $user->workExperiences; // Use user_id to fetch work experiences
        $computerLiteracies = $user->computerLiteracies; // Fetch computer literacy details
        $languageProficiencies = $user->languageProficiencies; // Fetch language proficiencies
        $trainingsAndWorkshops = $user->trainingsAndWorkshops; // Fetch trainings and workshops
        $referees = $user->referees; // Fetch referees

        // Pass all data to the view
        return view('staffs.resume', compact(
            'staff', 
            'workExperiences', 
            'computerLiteracies', 
            'languageProficiencies', 
            'trainingsAndWorkshops', 
            'referees'
        ));
    }

    public function create_cv($staff_id){
        $staff = Staff::find($staff_id);
        $education_levels = EducationLevel::all();
        return view('staffs.create_cv', compact('staff', 'education_levels'));
    }

    public function generateResumeePdf($staff_id){
        $staff = Staff::find($staff_id);
        $education_levels = EducationLevel::all();
        $pdf = PDF::loadView('staffs.download_resumeePdf',compact('staff', 'education_levels'));
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isPhpEnabled', true);

        return $pdf->stream("resumee.pdf");
        return view('staffs.download_resumeePdf', compact('staff', 'education_levels'));
    }
    public function update_cv(Request $request, $staff_id){
        $staff = Staff::find($staff_id);
        $education_levels = EducationLevel::all();
        // $request->validate([
        //     'father_names' => 'null|string',
        //     'father_ward_of_birth' => 'required_if:father_names,!null|string',
        //     'father_district_of_birth' => 'required_if:father_names,!null|string',
        //     'father_region_of_birth' => 'required_if:father_names,!null|string',
        // ]);
        $staff-> fatherParticulars = [
             $request-> father_names,
             $request-> father_village_of_birth,
            $request-> father_ward_of_birth,
             $request-> father_district_of_birth,
             $request-> father_region_of_birth,
        ];

        $staff-> motherParticulars = [
              $request-> mother_names,
             $request-> mother_village_of_birth,
             $request-> mother_ward_of_birth,
             $request-> mother_district_of_birth,
             $request-> mother_region_of_birth,
        ];
        $staff-> parentsAddress = [
             $request-> parentsVillage,
             $request-> parentsWard,
              $request-> parentsDistrict,
            $request-> parentsRegion,
        ];
        $staff->save();
        return $staff;
        return view('staffs.create_cv', compact('staff', 'education_levels'));
    }

    public function update_school_cv(Request $request, $staff_id){
        //return $request->all();
        $staff = Staff::find($staff_id);
        if($request->primary_school_name){
            $school = School::create([
                'staff_id' => $staff_id,
                'name' =>$request->primary_school_name,
                'education_level_id' =>1,
                'admission_year' =>$request->primary_school_YoA,
                'graduation_year' =>$request->primary_school_YoG,
                'award' =>$request->primary_school_ward,
                'village' =>$request->primary_school_village,
                'district' =>$request->primary_school_district,
                'region' =>$request->primary_school_region
            ] );            
        }

        if($request->secondary_school_name){
            $school = School::create([
                'staff_id' => $staff_id,
                'name' =>$request->secondary_school_name,
                'education_level_id' =>2,
                'admission_year' =>$request->secondary_school_YoA,
                'graduation_year' =>$request->secondary_school_YoG,
                'award' =>$request->secondary_school_ward,
                'village' =>$request->secondary_school_village,
                'district' =>$request->secondary_school_district,
                'region' =>$request->secondary_school_region
            ] );            
        }

        if($request->colleges_name){
            $school = School::create([
                'staff_id' => $staff_id,
                'name' =>$request->colleges_name,
                'education_level_id' =>4,
                'admission_year' =>$request->colleges_YoA,
                'graduation_year' =>$request->colleges_YoG,
                'duration' =>$request->duration,
                'country' =>$request->colleges_name_region,
                'award' =>$request->colleges_award,
                'region' =>$request->colleges_name_region,
            ] );            
        }

        if($request->venue){
            $school = School::create([
                'staff_id' => $staff_id,
                'name' =>$request->college,
                'education_level_id' =>5,
                'duration' =>$request->duration,
                'country' =>$request->colleges_name_region,
                'award' =>$request->award,
                'venue' =>$request->venue,
            ] );            
        }
        return "Ok";
    }
    public function update_work_experience(Request $request, $staff_id){
        $staff = Staff::find($staff_id);
        WorkExperience::create([
            'user_id' =>$staff->id,
            'institution'=> $request->institution,
            'address'=> $request->address,
            'job_title'=> $request->job_title,
            'position' => $request->position, 
            'start_date' =>  $request->start_date,
            'end_date' => $request->end_date,
            'address' => $request->address,
            'duties' => json_encode($request->duties),
        ]);
        return $request->all();
    }
}