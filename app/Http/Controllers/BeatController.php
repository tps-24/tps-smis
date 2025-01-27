<?php

namespace App\Http\Controllers;

use App\Models\Beat;
use App\Models\Company;
use App\Models\Area;
use App\Models\Student;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BeatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $beats = Beat::all();
        return view('beats.index', compact('beats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($area_id)
    {
        $area = Area::find($area_id);
        if (!$area) {

        }
        $companies = Company::all();

        return view('beats.search_students', compact('area', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $area_id)
    {
        $area = Area::find($area_id);
        $students_ids = $request->input('student_ids');
        for ($i = 0; $i < count($students_ids); ++$i) {
            Beat::create([
                'area_id' => $area->id,
                'student_id' => $students_ids[$i],
                'assigned_by' => Auth::user()->id,
                'start_at' => $request->start_at,
                'end_at' => $request->end_at
            ]);

        }
        $area->is_assigned = true;
        $area->save();
        return $students_ids[0];
    }

    /**
     * Display the specified resource.
     */
    public function show(Beat $beat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Beat $beat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Beat $beat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Beat $beat)
    {
        //
    }

    public function search(Request $request, $area_id)
    {
        $area = Area::find($area_id);
        $validatedData = $request->validate([
            'company' => 'required',
            'platoon' => 'required',
        ]);
        $companies = Company::all();
        $students = Student::where('company', $request->company)->where('platoon', $request->platoon)->get();
        //if($students && $request->name){
        // $students = $students->where('last_name','LIKE', "%{$request->name}%")
        // ->orWhere('first_name','LIKE', "%{$request->name}%")->get();
        // }
        return view('beats.search_students', compact('area', 'companies', 'students'));
    }

    public function assign_beats()
    {
        $area_id = 1;
        $beatType_id = 1;
        $company_id = 2;
        $company = Company::find($company_id);
        $students = $company->students()->whereNull('vitengo_id')->orderBy('platoon', 'asc')->get();

        $students = DB::table('students')->whereNull('vitengo_id')
            ->where('company', $company->name)
            ->orderBy('platoon')
            ->leftJoin('beats', 'students.id', '=', 'beats.student_id')
            ->select('students.id', DB::raw('COUNT(beats.student_id) as count'))
            ->groupBy('students.id')
            ->get();
        foreach ($students as $student) {
            // Beat::create([
            //     'beatType_id' => $beatType_id,
            //     'area_id' => $area_id,
            //     'student_id' => $student->id,
            //     'start_at' => now()->timestamp,
            //     'end_at' => now()->timestamp
            // ]);
        }
        return $students;
    }
}
