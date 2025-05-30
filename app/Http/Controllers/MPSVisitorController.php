<?php

namespace App\Http\Controllers;

use App\Models\MPSVisitor;
use App\Models\Student;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MPSVisitorController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:mps-list|mps-create|mps-edit|mps-delete', ['only' => ['index']]);
        $this->middleware('permission:mps-create', ['only' => ['create', 'store',]]);
        $this->middleware('permission:mps-edit', ['only' => ['edit', 'update','searchStudent']]);
        $this->middleware('permission:mps-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mpsVisitors = MPSVisitor::all();
        return view('mps.visitors.index', compact('mpsVisitors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::all();
        return view('mps.visitors.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $student_id)
    {
       
        $validator = Validator::make($request->all(), [
            'visited_at' => 'required',
            'visitor_name' => 'required',
            'visitor_phone'=>'required',
            'visitor_relation' => 'required|string'
        ]);
        if ($validator->errors()->any()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        $MpsVisitor = MPSVisitor::create([
            'student_id' => $student_id,
            'visited_at'  => $request->visited_at,
            'names' => $request->visitor_name,
            'phone' => $request->visitor_phone,
            'welcomed_by' => $request->user()->id,
            'relationship'  => $request->visitor_relation
        ]);

        return redirect()->route('visitors.index')->with('success', 'Visitor is recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($studentId)
    {
        $mpsVisitors = MPSVisitor::where('student_id', $studentId)->get();

        return view('mps.visitors.show', compact('mpsVisitors'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MPSVisitor $mPSVisitor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $mPSVisitor= MPSVisitor::find($id);
        $validator = Validator::make($request->all(), [
            'visited_at' => 'required',
            'visitor_name' => 'required',
            'visitor_phone'=>'required',
            'visitor_relation' => 'required|string'
        ]);
        if ($validator->errors()->any()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $mPSVisitor->visited_at =$request->visited_at;
        $mPSVisitor->names=$request->visitor_name;
        $mPSVisitor->phone =$request->visitor_phone;
        $mPSVisitor->relationship = $request->visitor_relation;
        $mPSVisitor->save();
        return redirect()->route('visitors.index')->with('success', 'Visitor is updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MPSVisitor $mPSVisitor, $id)
    { 
        $mPSVisitor= MPSVisitor::find($id);
        $mPSVisitor->delete();
        return redirect()->route('visitors.index')->with('success', 'Visitor details deleted successfully.');
    }
    public function searchStudent(Request $request){
        $companies = Company::all();
        $students = Student::where('platoon', $request->platoon)->where('company_id', $request->company_id);//orWhere('last_name', 'like', '%' . $request->last_name . '%')->get();
        if ($request->name) {
            $students = $students->where(function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->name . '%')
                    ->orWhere('last_name', 'like', '%' . $request->name . '%')
                    ->orWhere('middle_name', 'like', '%' . $request->name . '%');
            });
        }
        $students = $students->get();
        return view('mps.visitors.create', compact('companies','students'));
    }
}
