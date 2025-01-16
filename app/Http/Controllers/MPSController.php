<?php

namespace App\Http\Controllers;

use App\Models\MPS;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MPSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mpsStudents = MPS::all();
        return view('mps.index', compact('mpsStudents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mps.search');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $student_id)
    {
        $student = Student::find($student_id);
        if(!$student){
            abort(404);
        }
       $mpsStudentData = $student->mps;
       if($mpsStudentData){
        foreach($mpsStudentData as $data){
            if(!$data->released_at){
                return  redirect()->route('mps.create')->with('success','Student  not released yet.');
            }           
        }
       }
        $validator =  Validator::make($request->all(),[
            'description' => 'required',
            'days'=> 'required|numeric'
        ]);
        
        if ($validator->errors()->any()){
            return redirect()->back()->withErrors($validator->errors());
        }
        $student = MPS::create([
            'added_by'=> Auth::user()->id,
            'student_id' => $student->id,
            'description' => $request->description,
            'days' => $request->days,
            'arrested_at' => $request->arrested_at
        ]);
        
        return redirect()->route('mps.index')->with('success','Student recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MPS $mPS)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MPS $mPS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $mPS)
    {
        $mPSstudent = MPS::find($mPS);
        if(!$mPSstudent){
            abort(404);
        }
        $validator =  Validator::make($request->all(),[
            'description' => 'required|alpha',
            'days' => 'required|numeric'
        ]);
        if ($validator->errors()->any()){
            return redirect()->back()->withErrors($validator->errors());
        }
        MPS::create([
            'added_by' => Auth::user()->id,
            'student_id' => $mPSstudent->id,
            'days' => $request->days,
            'arrested_at' => $request->arrested_at
        ]);

        return redirect()->back()->with('success','MPS student record updated succesfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $mPS)
    {
        $mPSstudent = MPS::find($mPS);
        if(!$mPSstudent){
            abort(404);
        }
        $mPSstudent->delete();
        return redirect()->back()->with('success','MPS student record deleted succesfully.');

    }

    public function search(Request $request){
        $students = Student::where('last_name','like','%'.$request->last_name.'%')->where('platoon',$request->platoon)->where('company',$request->company)->get();
        return view('mps.search', compact('students'));
        //return redirect()->back()->with('student',$student);
    }

    public function release(Request $request, $mPSstudent){
        $mPSstudent = MPS::find($mPSstudent);
        if(!$mPSstudent){
            abort(404);
        }
        $mPSstudent->released_at = Carbon::now();
        $mPSstudent->save();
        return redirect()->back()->with('success','Student released successfuly.');
    }
}
