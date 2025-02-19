<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\User;
use App\Imports\BulkImportStaff;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Models\Department;
use App\Models\NextOfKin;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Http\RedirectResponse;
use DB;
use Hash;
use Auth;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $staffs = Staff::orderBy('id','DESC')->paginate(5);
        return view('staffs.index',compact('staffs'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
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
    
}
