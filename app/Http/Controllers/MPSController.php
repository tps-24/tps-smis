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
    private  $selectedSessionId;
    public function __construct()
    {

        $this->middleware('permission:mps-list|mps-create|mps-edit|mps-delete', ['only' => ['index']]);
        $this->middleware('permission:mps-create', ['only' => ['create', 'store',]]);
        $this->middleware('permission:mps-edit', ['only' => ['edit', 'update','release']]);
        $this->middleware('permission:mps-delete', ['only' => ['destroy']]);
        $this->selectedSessionId = session('selected_session');
        if (!$this->selectedSessionId)
            $this->selectedSessionId = 1;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mpsStudents = MPS::whereNull('released_at')->get();
        return view('mps.index', compact('mpsStudents'));
    }

    public function all()
    {
        $mpsStudents = MPS::orderBy('created_at', 'desc')->get();
        $scrumbName = "All";
        return view('mps.index', compact('mpsStudents', 'scrumbName'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::all();
        return view('mps.search', compact('companies'));
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
            'arrested_at' => 'required||date'
            //'days' => 'required|numeric'
        ]);

        if ($validator->errors()->any()) {
            return redirect()->back()->withErrors($validator->errors());
        }
         MPS::create([
            'added_by' => Auth::user()->id,
            'student_id' => $student->id,
            'description' => $request->description,
            'previous_beat_status' => $student->beat_status,
            'arrested_at' => $request->arrested_at
        ]);

        $student->beat_status = 6;
        $student->save();
        return redirect()->route('mps.index')->with('success', 'Student recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($studentId)
    {
        $mpsStudents = MPS::where('student_id', $studentId)->get();
        return view('mps.show', compact('mpsStudents'));
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
    public function update(Request $request, $mPSStudentId)
    {
        $mPSstudent = MPS::find($mPSStudentId);
        if (!$mPSstudent) {
            abort(404);
        }
        $validator = Validator::make($request->all(), [
            'description' => 'required|alpha',
            'arrested_at' => 'required||date'
        ]);
        if ($validator->errors()->any()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        $mPSstudent->arrested_at = $request->arrested_at;
        $mPSstudent->description = $request->description;
        $mPSstudent->save();

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
        $companies = Company::all();
        $students = Student::where('platoon', $request->platoon)->where('company_id', $request->company_id)->where('session_programme_id', $this->selectedSessionId);//orWhere('last_name', 'like', '%' . $request->last_name . '%')->get();
        if ($request->name) {
            $students = $students->where(function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->name . '%')
                    ->orWhere('last_name', 'like', '%' . $request->name . '%')
                    ->orWhere('middle_name', 'like', '%' . $request->name . '%');
            });
        }
        $students = $students->get();
        return view('mps.search', compact('students', 'companies'));
        //return redirect()->back()->with('student',$student);
    }

    private function searchStudent($company_id, $platoon, $name = null){
        $students = Student::where('platoon', $platoon)->where('company_id', $company_id)->where('session_programme_id', $this->selectedSessionId);//orWhere('last_name', 'like', '%' . $request->last_name . '%')->get();
        if ($name) {
            $students = $students->where(function ($query) use ($name) {
                $query->where('first_name', 'like', '%' . $name . '%')
                    ->orWhere('last_name', 'like', '%' . $name . '%')
                    ->orWhere('middle_name', 'like', '%' . $name . '%');
            });
        }
        $students = $students->get();
        return $students;
    }

    

    public function release(Request $request, $mPSstudent)
    {
        $request->validate([
            'reason' => 'required'
        ]);

        $mPSstudent = MPS::find($mPSstudent);
        if (!$mPSstudent) {
            abort(404);
        }
        $mPSstudent->released_at = Carbon::now();
        $mPSstudent->days = Carbon::parse($mPSstudent->arrested_at)->diffInDays(Carbon::now());
        $student = $mPSstudent->student;
        $mPSstudent->release_reason = $request->reason;
        $student->beat_status = $mPSstudent->previous_beat_status;
        $student->save();
        $mPSstudent->save();
        return redirect()->back()->with('success', 'Student released successfuly.');
    }

    public function company($companyId)
    {
        $company = Company::find($companyId);
        $mpsStudents = $company->lockUp->whereNull('released_at');
                        $scrumbName = $company->description;
        return view('mps.index', compact('mpsStudents', 'scrumbName'));

    }
}
