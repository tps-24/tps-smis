<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use DB;

class SemesterController extends Controller
{
    public function index(Request $request): View
    {
        $semesters = Semester::orderBy('id','Asc')->paginate(5);
        return view('semesters.index',compact('semesters'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('semesters.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'semester_name' => 'required|string|max:255',
        ]);

        Semester::create($request->all());

        return redirect()->route('semesters.index')
                         ->with('success', 'Semester created successfully.');
    }

    public function show(Semester $semester)
    {
        return view('semesters.show', compact('semester'));
    }

    public function edit(Semester $semester)
    {
        return view('semesters.edit', compact('semester'));
    }

    public function update(Request $request, Semester $semester)
    {
        $request->validate([
            'semester_name' => 'required|string|max:255',
        ]);

        $semester->update($request->all());

        return redirect()->route('semesters.index')
                         ->with('success', 'Semester updated successfully.');
    }

    public function destroy(Semester $semester)
    {
        $semester->delete();

        return redirect()->route('semesters.index')
                         ->with('success', 'Semester deleted successfully.');
    }
}