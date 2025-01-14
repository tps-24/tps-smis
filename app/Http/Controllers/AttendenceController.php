<?php

namespace App\Http\Controllers;

use App\Models\Attendence;
use App\Models\Platoon;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;


class AttendenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendence = Attendence::whereDate('created_at', Carbon::today())->get();
        $statistics = [
        'present' => $attendence->sum('present'),
        'absent' => $attendence->sum('absent'),
        'total' => $attendence->sum('total'),
        'sick' => $attendence->sum('sick'),
        'leave' => $attendence->sum('off'),
        'mps' => $attendence->sum('off'),
        ];
        //dd($statistics);
        return view('attendences/index', compact('statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {              
        $platoon = Platoon::where([
            ['name', $request->platoon],
            ['company_id', $request->company]
        ])->get()[0]->id;
        return view('attendences/create', compact('platoon'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$platoon_id)
    {
        $todayRecords = Attendence::where('platoon_id',$platoon_id)->whereDate('created_at', Carbon::today())->get();
        if(!$todayRecords->isEmpty()){
            return redirect()->route('attendences.index')->with('success', "Attendences for this platoon already recorded.");
        }
        $validator = Validator::make($request->all(),[
            'present' => 'required|numeric',
            'absent' => 'nullable|numeric',
            'sick' => 'nullable|numeric',
            'sentry' => 'nullable|numeric',
            'excuse_duty' => 'nullable|numeric',
            'kazini' => 'nullable|numeric',
            'adm' => 'nullable|numeric',
            'safari' => 'nullable|numeric',
            'off' => 'nullable|numeric',
            'mess' => 'nullable|numeric',
            'female' => 'nullable|numeric',
            'male' => 'nullable|numeric',
        ]);
        if ($validator->errors()->any()){
            return redirect()->back()->withErrors($validator->errors());
        }
        $total = 0;
        $total = $total + $request->present + $request->absent + $request->sick + $request->sentry + $request->excuse_duty + $request->kazini + $request->adm ;
        $total = $total + $request->safari + $request->off + $request->mess + $request->sick;  
        Attendence::create([
            'platoon_id' => $platoon_id,
            'present' => $request->present,
            'sentry' => $request->sentry,
            'absent' => $request->absent,
            'excuse_duty'=>$request->excuse_duty,
            'kazini' => $request->kazini,
            'adm' => $request->adm,
            'safari' => $request->safari,
            'off' => $request->off,
            'mess' => $request->mess,
            'sick'=> $request->sick,
            'female' => $request->female,
            'male' => $request->male,
            'total' => $total
        ]);

        return redirect()->route('attendences.index')->with('success', "Attendences record successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendence $attendence)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendence $attendence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendence $attendence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendence $attendence)
    {
        //
    }

    /**
     * List all attendence for all companies
     */
    public function today(){
        $attendences = Attendence::whereDate('created_at', Carbon::today())->get();
        return view('attendences.attended',compact('attendences'));
    }
}
