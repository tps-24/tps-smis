<?php

namespace App\Http\Controllers;

use App\Models\TeacherOnDuty;
use App\Models\Company;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class TeacherOnDutyController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:teacher_on_duty-view', ['only' => ['index']]);
        $this->middleware('permission:teacher_on_duty-assign', ['only' => ['store','unassign']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $companies = [];

        $user = Auth::user();
        if($user->hasRole(['OC Coy'])){
            $companies = [$user->staff->company];
            $teachers = TeacherOnDuty::whereNull('end_date')->where('company_id', $user->staff->company_id)->get();
            $staffs = $companies[0]->staffs()->where('rank', 'CPL')->orWhere('rank', 'PC')->where('company_id',$user->staff->company_id)->paginate(10);
        }
        else{
            $companies = Company::all();
            $teachers = TeacherOnDuty::whereNull('end_date')->get();
            $staffs = $companies[0]->staffs()->where('rank', 'CPL')->orWhere('rank', 'PC')->paginate(10);
        }  
        
       // return $companies[0];
        //$staffs = $companies[0]->staffs()->where('rank', 'CPL')->orWhere('rank', 'PC')->where('company_id',$companies[0]->id)->paginate(10);
        return view('teacher_on_duty.index',compact('staffs','companies','teachers')) ->with('i', ($request->input('page', 1) - 1) * 10);
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
    public function store(Request $request,$staffId)
    {
        $staff = Staff::find($staffId);
        $unassigned = TeacherOnDuty::where('company_id',$staff->company_id)->whereNull('end_date')->get();
        if($unassigned->isNotEmpty()){
            return redirect()->back()->with('info', 'Unassign Teacher on duty in that company first.');
        }
        TeacherOnDuty::create([
            'user_id' => $staff->user_id,
            'company_id' => $staff->company_id,
            'start_date' => $request->start_date
        ]);
        return redirect()->back()->with('success','Teacher on duty Assigned successfully.');
    }


        public function unassign(Request $request,$teacherOnDutyId)
    {
        $teacherOnDuty = TeacherOnDuty::find($teacherOnDutyId);
        if(!$teacherOnDuty){
          return redirect()->back()->with('error', 'Not found.');  
        }
        $teacherOnDuty->end_date = $request->end_date;
        $teacherOnDuty->save();
        return redirect()->back()->with('success','Teacher on duty Unassigned successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(TeacherOnDuty $teacherOnDuty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeacherOnDuty $teacherOnDuty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeacherOnDuty $teacherOnDuty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeacherOnDuty $teacherOnDuty)
    {
        //
    }

public function search(Request $request)
{
    $staffs = Staff::where('company_id', $request->company_id)
        ->orderBy('firstName');

    if ($request->name) {
        $staffs = $staffs->whereIn('rank', ['CPL', 'PC'])
            ->where(function ($query) use ($request) {
                $query->where('firstName', 'like', '%' . $request->name . '%')
                      ->orWhere('lastName', 'like', '%' . $request->name . '%')
                      ->orWhere('middleName', 'like', '%' . $request->name . '%');
            });
    }

    $companies = Company::all();
    $teachers = TeacherOnDuty::whereNull('end_date')->get();

    // 👇 Append search parameters to pagination
    $staffs = $staffs->latest()->paginate(10)->appends($request->all());

    return view('teacher_on_duty.index', compact('staffs', 'companies', 'teachers'))
        ->with('i', ($request->input('page', 1) - 1) * 10);
}


}