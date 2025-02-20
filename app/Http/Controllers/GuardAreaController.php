<?php

namespace App\Http\Controllers;

use App\Models\GuardArea;
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
        if(!empty($data['beat_time_exception_ids'])){
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
