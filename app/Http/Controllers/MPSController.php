<?php

namespace App\Http\Controllers;

use App\Models\MPS;
use App\Models\Company;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MPSController extends Controller
{

    public function __construct()
    {

        $this->middleware('permission:mps-list|mps-create|mps-edit|mps-delete', ['only' => ['index']]);
        $this->middleware('permission:attendance-create', ['only' => ['create', 'store',]]);
        $this->middleware('permission:attendance-edit', ['only' => ['edit', 'update','release']]);
        $this->middleware('permission:attendance-delete', ['only' => ['destroy']]);
    }
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
        if (!$student) {
            abort(404);
        }
        $mpsStudentData = $student->mps;
        if ($mpsStudentData) {
            foreach ($mpsStudentData as $data) {
                if (!$data->released_at) {
                    return redirect()->route('mps.create')->with('error', 'Student  not released yet.');
                }
            }
        }
        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'days' => 'required|numeric'
        ]);

        if ($validator->errors()->any()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        $student = MPS::create([
            'added_by' => Auth::user()->id,
            'student_id' => $student->id,
            'description' => $request->description,
            'days' => $request->days,
            'arrested_at' => $request->arrested_at
        ]);

        return redirect()->route('mps.index')->with('success', 'Student recorded successfully.');
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
        if (!$mPSstudent) {
            abort(404);
        }
        $validator = Validator::make($request->all(), [
            'description' => 'required|alpha',
            'days' => 'required|numeric'
        ]);
        if ($validator->errors()->any()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        MPS::create([
            'added_by' => Auth::user()->id,
            'student_id' => $mPSstudent->id,
            'days' => $request->days,
            'arrested_at' => $request->arrested_at
        ]);

        return redirect()->back()->with('success', 'MPS student record updated succesfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($mPS)
    {
        $mPSstudent = MPS::find($mPS);
        if (!$mPSstudent) {
            abort(404);
        }
        $mPSstudent->delete();
        return redirect()->back()->with('success', 'MPS student record deleted succesfully.');

    }

    public function search(Request $request)
    {
        $students = Student::where('platoon', $request->platoon)->where('company', $request->company);//orWhere('last_name', 'like', '%' . $request->last_name . '%')->get();
        if ($request->name) {
            $students = $students->where(function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->name . '%')
                    ->orWhere('last_name', 'like', '%' . $request->name . '%')
                    ->orWhere('middle_name', 'like', '%' . $request->name . '%');
            });
        }
        $students = $students->get();
        return view('mps.search', compact('students'));
        //return redirect()->back()->with('student',$student);
    }

    public function release(Request $request, $mPSstudent)
    {
        $mPSstudent = MPS::find($mPSstudent);
        if (!$mPSstudent) {
            abort(404);
        }
        $mPSstudent->released_at = Carbon::now();
        $mPSstudent->save();
        return redirect()->back()->with('success', 'Student released successfuly.');
    }

    public function company($companyName)
    {
        $mpsStudents = MPS::join('students', 'm_p_s.student_id', 'students.id')->join('companies', 'students.company', 'companies.name')
                        ->where('students.company', $companyName)->get();
                        $scrumbName = $companyName;
        return view('mps.index', compact('mpsStudents', 'scrumbName'));

    }
}
