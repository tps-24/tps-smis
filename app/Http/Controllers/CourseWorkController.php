<?php

namespace App\Http\Controllers;

use App\Models\CourseWork;
use Illuminate\Http\Request;

class CourseWorkController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:coursework-create')->only(['create', 'store']);
        $this->middleware('permission:coursework-list')->only(['index', 'show']);
        $this->middleware('permission:coursework-update')->only(['edit', 'update']);
        $this->middleware('permission:coursework-delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(CourseWork $courseWork)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseWork $courseWork)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseWork $courseWork)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseWork $courseWork)
    {
        //
    }
}
