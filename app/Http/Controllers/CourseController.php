<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use DB;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $courses = Course::orderBy('id','DESC')->paginate(5);
        return view('courses.index',compact('courses'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::get();
        return view('courses.create',compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'courseName' => 'required|unique:courses,courseName',
            'courseCode' => 'required',
        ]);
    
        Course::create($request->all());
    
        return redirect()->route('courses.index')
                        ->with('success','Course added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $departmentName = Department::WHERE('id' , $course->department_id)->pluck('departmentName');
        return view('courses.show',compact('departmentName','course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $departments = Department::get();
        return view('courses.edit',compact('course', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        request()->validate([
            'courseName' => 'required|unique:courses,courseName',
            'courseCode' => 'required',
       ]);
   
       $course->update($request->all());
   
       return redirect()->route('courses.index')
                       ->with('success','Course updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();
    
        return redirect()->route('courses.index')
                        ->with('success','Course deleted successfully');
    }
}
