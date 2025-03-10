<?php

namespace App\Http\Controllers;

use App\Models\GuardArea;
use App\Models\Company;
use App\Models\Campus;
use App\Models\BeatException;
use App\Models\BeatTimeException;
use Illuminate\Http\Request;

class GuardAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // app/Http/Controllers/GuardAreaController.php
    public function index()
    {
        $guardAreas = GuardArea::all();
        return view('guardArea.index', compact('guardAreas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $beatExceptions = BeatException::all();
        $beatTimeExceptions = BeatTimeException::all();
        $campuses = Campus::all();
        $companies = Company::all();
        return view('guardArea.create', compact('beatExceptions', 'beatTimeExceptions', 'campuses', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'guard_area_name' => 'required',
            'company_id' => 'required|exists:companies,id',
            'campus_id' => 'required|exists:campuses,id',
            'beat_exception_ids' => 'required|array',
            'beat_exception_ids.*' => 'required|numeric|exists:beat_exceptions,id',
            'beat_time_exception_ids' => 'required|array',
            'beat_time_exception_ids.*' => 'required|numeric|exists:beat_time_exceptions,id',
            'number_of_guards' => 'required|numeric|min:1'
        ]);

        GuardArea::create(
            [
                'name' => $request->guard_area_name,
                'company_id' => $request->company_id,
                'campus_id' => $request->campus_id,
                'added_by' => $request->user()->id,
                'beat_exception_ids' => $request->beat_exception_ids,
                'beat_time_exception_ids' => $request->beat_time_exception_ids,
                'number_of_guards' => $request->number_of_guards
            ]);
        return redirect()->route('guardArea.index')->with('success', "New guard area created successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GuardArea $guardArea)
    {
        $beatExceptions = BeatException::all();
        $beatTimeExceptions = BeatTimeException::all();
        return view('guardArea.edit', compact('guardArea', 'beatExceptions', 'beatTimeExceptions'));
    }





    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GuardArea $guardArea)
    {
        $data = $request->validate([
            'beat_exception_ids' => 'nullable|array',
            'beat_exception_ids.*' => 'integer',
            'beat_time_exception_ids' => 'nullable|array',
            'beat_time_exception_ids.*' => 'integer|nullable',
        ]);

        $data['beat_exception_ids'] = json_encode($data['beat_exception_ids']);
        if (!empty($data['beat_time_exception_ids'])) {
            $data['beat_time_exception_ids'] = json_encode($data['beat_time_exception_ids']);
        }

        $guardArea->update($data);

        return redirect()->route('guard-areas.index');
    }

    // public function update(Request $request, GuardArea $guardArea)
    // {
    //     $data = $request->validate([
    //         'beat_exception_ids' => 'nullable|array',
    //         'beat_exception_ids.*' => 'integer',
    //         'beat_time_exception_ids' => 'nullable|array',
    //         'beat_time_exception_ids.*' => 'integer|nullable',
    //     ]);

    //     // Directly assign the array to the model attributes

    //     $data['beat_exception_ids'] =($data['beat_exception_ids']);
    //     if(!empty($data['beat_time_exception_ids'])){
    //         $data['beat_time_exception_ids'] = ($data['beat_time_exception_ids']);
    //     }

    //     $guardArea->update($data);

    //     return redirect()->route('guard-areas.index');
    // }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
