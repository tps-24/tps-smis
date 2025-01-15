<?php

namespace App\Http\Controllers;

use App\Models\Attendence;
use App\Models\Platoon;
use App\Models\Company;
use App\Models\AttendenceType;
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
        return $this->attendence(1);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $attendenceTypes = AttendenceType::all();
        $platoon = Platoon::where([
            ['name', $request->platoon],
            ['company_id', $request->company]
        ])->get()[0]->id;
        return view('attendences/create', compact('platoon','attendenceTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $platoon_id)
    {
        $todayRecords = Attendence::where('platoon_id', $platoon_id)->whereDate('created_at', Carbon::today())->get();
        if (!$todayRecords->isEmpty()) {
            return redirect()->route('attendences.index')->with('success', "Attendences for this platoon already recorded.");
        }
        $validator = Validator::make($request->all(), [
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
        if ($validator->errors()->any()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        $page = AttendenceType::find($request->type);
        $total = 0;
        $total = $total + $request->present + $request->absent + $request->sentry  + $request->adm;
        $total = $total + $request->safari + $request->off + $request->mess ;
        Attendence::create([
            'attendenceType_id' => $request->type,
            'platoon_id' => $platoon_id,
            'present' => $request->present,
            'sentry' => $request->sentry,
            'absent' => $request->absent,
            'adm' => $request->adm,
            'safari' => $request->safari,
            'off' => $request->off,
            'mess' => $request->mess,
            'female' => $request->female,
            'male' => $request->male,
            'total' => $total
        ]);
        return $this->attendence($request->type);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendence $attendence)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($attendence_id)
    {
        $attendence = Attendence::find($attendence_id);
        $attendenceTypes = AttendenceType::all();
        $platoon = $attendence->platoon->id;
        return view('attendences/create', compact('platoon', 'attendence','attendenceTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $attendence_id)
    {

        $total = 0;
        $total = $total + $request->present + $request->absent  + $request->sentry + $request->adm;
        $total = $total + $request->safari + $request->off + $request->mess ;

        $attendence = Attendence::find($attendence_id);
        $attendence->update(
            [
                'present' => $request->present,
                'sentry' => $request->sentry,
                'absent' => $request->absent,
                'adm' => $request->adm,
                'safari' => $request->safari,
                'off' => $request->off,
                'mess' => $request->mess,
                'female' => $request->female,
                'male' => $request->male,
                'total' => $total
            ]
        );

        return redirect()->route('attendences.index')->with('success', "Attendences updated successfully.");


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
    public function today($company_id, $type)
    {
        $attendences = [];
        $company = Company::find($company_id);
        $page = AttendenceType::find($type);
        foreach($company->platoons as $platoon){
            if($platoon->attendences != "null"){
                $attendences = $platoon->attendences()->where('attendenceType_id',$type)->whereDate('created_at', Carbon::today())->get();
                return view('attendences.attended', compact('attendences','page'));
            }
        }
        return view('attendences.attended', compact('attendences','page'));
    }
    protected function statistics($attendence){
        $present = 0;
        $absent = 0;
        $total = 0;
        $sick = 0;
        $leave = 0;
        $off = 0;
        $mps = 0;
        foreach($attendence as $att){
            $present = $present + $att['present'];
            $absent = $absent + $att['absent'];
            $total = $total + $att['total'];
            $off = $off + $att['off'];
        }
        return [
            'present' => $present,
            'absent' => $absent,
            'total' => $total,
            'sick' => $sick,
            'leave' => $off,
            'mps' => $mps
        ];
    }

    protected function setZero(){
        return [
            'present' => 0,
            'absent' => 0,
            'total' => 0,
            'sick' => 0,
            'leave' => 0,
            'mps' => 0,
        ];
    }

    public function attendence($type){
        $attendenceType = AttendenceType::find($type);
        $page = $attendenceType;
        $hqAttendence = [];
        $AAttendence = [];
        $BAttendence = [];
        $CAttendence = [];
        $companies = Company::all();
        $attendence = Attendence::where('attendenceType_id', $type)->whereDate('created_at', Carbon::today())->get();
        foreach($attendence as $att) {
            if($att->platoon->company != "null" && $att->platoon->company->name == "HQ"){
                array_push( $hqAttendence, $att);
            }
            if($att->platoon->company != "null" && $att->platoon->company->name == "A"){
                array_push( $AAttendence, $att);
            }
            if($att->platoon->company != "null" && $att->platoon->company->name == "B"){
                array_push( $BAttendence, $att);
            }
            if($att->platoon->company != "null" && $att->platoon->company->name == "C"){
                array_push( $CAttendence, $att);
            }
        }

        $statistics = [
            'hq' => count($hqAttendence)> 0 ?  $this->statistics($hqAttendence): $this->setZero(),
            'a' => count($AAttendence)> 0 ?  $this->statistics($AAttendence): $this->setZero(),
            'b' => count($BAttendence)> 0 ?  $this->statistics($BAttendence): $this->setZero(),
            'c' => count($CAttendence)> 0 ?  $this->statistics($CAttendence): $this->setZero()
        ]; 
        return view('attendences/index', compact('statistics', 'companies','page'));
    }

}
