<?php

namespace App\Http\Controllers;

use App\Models\SemesterExamResult;
use Illuminate\Http\Request;
use App\Models\CourseworkResult;
use App\Models\Course;
use App\Models\Student;
use App\Models\CourseWork;
use App\Models\Semester;
use App\Models\Programme;
use App\Imports\CourseworkResultImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\DB;

class SemesterExamResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  
     public function index(Request $request)
     {
         $programme = Programme::findOrFail(1);
 
         $semesters = Semester::with('courses')->get();
         $selectedSemesterId = $request->get('semester_id');
         $selectedSemester = $selectedSemesterId ? Semester::with('courses')->find($selectedSemesterId) : null;
 
         // dd($courseworkResults);
         return view('semester_exams.index', compact('programme','semesters','selectedSemester'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SemesterExamResult $semesterExamResult)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SemesterExamResult $semesterExamResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SemesterExamResult $semesterExamResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SemesterExamResult $semesterExamResult)
    {
        //
    }
}
