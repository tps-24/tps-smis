<?php

namespace App\Http\Controllers;

use App\Models\PatrolArea;
use App\Models\BeatException;
use App\Models\BeatTimeException;
use Illuminate\Http\Request;

class PatrolAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patrolAreas = PatrolArea::all();
        return view('patrolArea.index', compact('patrolAreas'));
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

    public function edit(PatrolArea $patrolArea)
    {
        $beatExceptions = BeatException::all();
        $beatTimeExceptions = BeatTimeException::all();
        return view('patrolArea.edit', compact('patrolArea', 'beatExceptions', 'beatTimeExceptions'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PatrolArea $patrolArea)
    {

        $data = $request->validate([
            'beat_exception_ids' => 'nullable|array',
            'beat_exception_ids.*' => 'integer',
            'beat_time_exception_ids' => 'nullable|array',
            'beat_time_exception_ids.*' => 'integer',
        ]);

        $data['beat_exception_ids'] = json_encode($data['beat_exception_ids']);
        $data['beat_time_exception_ids'] = json_encode($data['beat_time_exception_ids']);

        $patrolArea->update($data);

        return redirect()->route('patrol-areas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
