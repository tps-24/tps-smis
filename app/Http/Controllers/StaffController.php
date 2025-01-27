<?php 

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Department;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Http\RedirectResponse;
use DB;
use Hash;

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
        // dd($request);
        $this->validate($request, [
            'forceNumber' => 'required|unique:staff',
            'rank' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'gender' => 'required',
            'DoB' => 'required|date',
            'maritalStatus' => 'required',
            'phoneNumber' => 'required',
            'email' => 'required|email|unique:users,email',
            'department_id' => 'required|integer',
            'roles' => 'required',
            'educationLevel' => 'required',
            'contractType' => 'required',
            'joiningDate' => 'required|date',
            'location' => 'required',
            'created_by' => 'required|exists:users,id'
        ]);

        $input = $request->all();
        $password = Hash::make($input['lastName']);
        $fullName = $request->firstName. ' ' . $request->middleName. ' ' . $request->lastName;
    

        //Create User First
        $user = User::create([
            'name' => $fullName,
            'email' => $request->email,
            'password' => $password,
        ]);
        $user->assignRole($request->input('roles'));

        
        $input['user_id'] = $user->id;
        
        // dd($input);
        //Now Create Staff
        $staff = Staff::create($input);

        return redirect()->route('staffs.index')->with('success', 'Staff created successfully.');
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

        return view('staffs.edit', compact('staff', 'departments','roles', 'userRole'));
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
            'DoB' => 'required|date',
            'maritalStatus' => 'required',
            'phoneNumber' => 'required',
            'email' => 'required|unique:staff,email,' . $staff->id,
            'department_id' => 'required|integer',
            'roles' => 'required',
            'educationLevel' => 'required',
            'contractType' => 'required',
            'joiningDate' => 'required|date',
            'location' => 'required',
            'updated_by' => 'required|exists:users,id'
        ]);

        $id = Staff::where('id', $staff->id)->pluck('user_id');
        $fullName = $request->firstName. ' ' . $request->middleName. ' ' . $request->lastName;
        $input['name'] = $fullName;
        $input = $request->only(['name', 'email']);
        $input = Arr::except($input,array('password'));    
 
    
        $user = User::find($id);
        $user->update($input);


        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));

        $staff->update($request->all());

        return redirect()->route('staffs.index')->with('success', 'Staff updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        $staff->delete();

        return redirect()->route('staffs.index')->with('success', 'Staff deleted successfully.');
    }
}
