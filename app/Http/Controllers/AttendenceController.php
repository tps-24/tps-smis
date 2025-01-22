<?php

namespace App\Http\Controllers;

use App\Models\Attendence;
use App\Models\Platoon;
use App\Models\Company;
use App\Models\AttendenceType;
use App\Models\MPS;
use App\Models\Student;
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
    public function create(Request $request, $attendenceType_id)
    {
        $attendenceType = AttendenceType::find($attendenceType_id);
        $platoon = Platoon::where([
            ['name', $request->platoon],
            ['company_id', $request->company]
        ])->get()[0];
        $platoon_id = $platoon->id;
        return view(
            'attendences/create',
            compact('platoon', 'attendenceType')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$type, $platoon_id)
    {
        $present = count($request->input('student_ids'));
        $platoon = Platoon::find($platoon_id);
        $students = $platoon->students;
        $total = count(value: $students);
        $absent = $total - $present;
        $todayRecords = Attendence::join('platoons', 'attendences.platoon_id', 'platoons.id')
            ->where('attendences.platoon_id', $platoon_id)->whereDate('attendences.created_at', Carbon::today())->get();
        if (!$todayRecords->isEmpty()) {
            return redirect()->route('attendences.index')->with('error', "Attendences for this platoon already recorded.");
        }
        // $validator = Validator::make($request->all(), [
        //     'present' => 'required|numeric',
        //     'absent' => 'nullable|numeric',
        //     'sick' => 'nullable|numeric',
        //     'sentry' => 'nullable|numeric',
        //     'excuse_duty' => 'nullable|numeric',
        //     'kazini' => 'nullable|numeric',
        //     'adm' => 'nullable|numeric',
        //     'safari' => 'nullable|numeric',
        //     'off' => 'nullable|numeric',
        //     'mess' => 'nullable|numeric',
        //     'female' => 'nullable|numeric',
        //     'male' => 'nullable|numeric',
        // ]);
        // if ($validator->errors()->any()) {
        //     return redirect()->back()->withErrors($validator->errors());
        // }
        $page = AttendenceType::find($type);

        Attendence::create([
            'attendenceType_id' => $type,
            'platoon_id' => $platoon_id,
            'present' => $present,
            'sentry' => 0,
            'absent' => $absent,
            'adm' => 0,
            'safari' => 0,
            'off' => 0,
            'mess' => 0,
            'female' => 0,
            'male' => 0,
            'total' => $total
        ]);
        return $this->attendence($request->type)->with('page',$page);
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
        return view('attendences/create', compact('platoon', 'attendence', 'attendenceTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $attendence_id)
    {

        $total = 0;
        $total = $total + $request->present + $request->absent + $request->sentry + $request->adm;
        $total = $total + $request->safari + $request->off + $request->mess;

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
        $attendences = new \Illuminate\Database\Eloquent\Collection;
        $company = Company::find($company_id);
        $page = AttendenceType::find($type);
        foreach ($company->platoons as $platoon) {
            if ($platoon->attendences->isNotEmpty()) {
                $platoon_attendences = $platoon->attendences()->where('attendenceType_id', $type)->whereDate('created_at', Carbon::today())->get();
                $absent_students = new \Illuminate\Database\Eloquent\Collection;
                $safari_students = new \Illuminate\Database\Eloquent\Collection;
                foreach ($platoon_attendences as $att) {
                    $absent_students_ids = explode(',', $att->absent_student_ids);
                    $safari_students_ids = explode(',', $att->safari_student_ids);
                    
                    if (count($absent_students_ids) > 0) {
                        for ($i = 0; $i < count($absent_students_ids); ++$i) {
                            $student = Student::find($absent_students_ids[$i]);
                            $absent_students->push($student);
                        }
                        $att->absent_students = $absent_students;
                        //$attendences->push($att);
                    }

                    //Safari students
                    if (count($safari_students_ids) > 0) {
                        for ($i = 0; $i < count($safari_students_ids); ++$i) {
                            $student = Student::find($safari_students_ids[$i]);
                            $safari_students->push($student);
                        }
                        $att->safari_students = $safari_students;
                        $attendences->push($att);
                    }

                }

                //return view('attendences.attended', compact('attendences', 'page','absent_students','company'));
            }


        }
        return view('attendences.attended', compact('attendences', 'page', 'company'));
    }
    protected function statistics($attendence, $company)
    {
        //return $attendence;
        $data = new \Illuminate\Database\Eloquent\Collection();
        $present = 0;
        $absent = 0;
        $total = 0;
        $sick = 0;
        $safari = 0;
        $off = 0;
        $mps = 0;
        for ($i = 0; $i < count($attendence); ++$i) {
            if (count($attendence[$i + 1]) > 0) {
                $present += $attendence[$i + 1][0]['present'];
                $absent += $attendence[$i + 1][0]['absent'];
                $safari += $attendence[$i + 1][0]['safari'];
                $total += $attendence[$i + 1][0]['total'];
                // $data->put($i +1, $attendence[$i+ 1][0]['present']);
            } else {

            }
        }

        $data->put('present', $present);
        $data->put('absent', $absent);
        $data->put('total', $total);
        $data->put('sick', $sick);
        $data->put('safari', $safari);
        $data->put('mps', $this->getMPSdata($company));
        return $data;
    }

    protected function setZero($company)
    {
        return [
            'present' => 0,
            'absent' => 0,
            'total' => 0,
            'sick' => 0,
            'leave' => 0,
            'mps' => $this->getMPSdata($company),
        ];
    }

    public function attendence($type)
    {
        $attendenceType = AttendenceType::find($type);
        $page = $attendenceType;
        $attendences = new \Illuminate\Database\Eloquent\Collection();
        $data = [];
        $statistics = new \Illuminate\Database\Eloquent\Collection();
        $companies = Company::all();
        foreach ($companies as $company) {
            $hasAttendence = false;
            $platoon_attendence = new \Illuminate\Database\Eloquent\Collection();
            foreach ($company->platoons as $platoon) {
                if (!empty($platoon->attendences)) {

                    $att = $platoon->attendences()
                        ->where('attendenceType_id', $type)->whereDate('created_at', Carbon::today())->get();
                    if ($att) {
                        $platoon_attendence->put($platoon->name, $att);
                        $hasAttendence = true;
                    }
                }
            }
            if ($hasAttendence) {
                $attendences->put($company->name, $platoon_attendence);
                $hasAttendence = false;
            }
        }
        foreach ($companies as $company) {
            $statistics->put($company->name, $this->statistics($attendences[$company->name], $company->name));
        }

        return view('attendences/index', compact('statistics', 'companies', 'page'));
    }

    public function testAttendence($type)
    {
        $attendenceType = AttendenceType::find($type);
        $page = $attendenceType;
        $hqAttendence = [];
        $AAttendence = [];
        $BAttendence = [];
        $CAttendence = [];
        $companies = Company::all();
        $attendence = Attendence::where('attendenceType_id', $type)->whereDate('created_at', Carbon::today())->get();
        foreach ($attendence as $att) {
            if ($att->platoon->company != "null" && $att->platoon->company->name == "HQ") {
                array_push($hqAttendence, $att);
            }
            if ($att->platoon->company != "null" && $att->platoon->company->name == "A") {
                array_push($AAttendence, $att);
            }
            if ($att->platoon->company != "null" && $att->platoon->company->name == "B") {
                array_push($BAttendence, $att);
            }
            if ($att->platoon->company != "null" && $att->platoon->company->name == "C") {
                array_push($CAttendence, $att);
            }
        }

        $statistics = [
            'hq' => count($hqAttendence) > 0 ? $this->statistics($hqAttendence, 'HQ') : $this->setZero('HQ'),
            'a' => count($AAttendence) > 0 ? $this->statistics($AAttendence, 'A') : $this->setZero('A'),
            'b' => count($BAttendence) > 0 ? $this->statistics($BAttendence, 'B') : $this->setZero('B'),
            'c' => count($CAttendence) > 0 ? $this->statistics($CAttendence, 'C') : $this->setZero('C')
        ];

        return view('attendences/index', compact('statistics', 'companies', 'page'));
    }

    public function getMPSdata($company)
    {
        $mpsStudents = MPS::whereDate('created_at', Carbon::today())->orWhereNotNull('released_at')->get();
        $count = 0;
        return 0;
        foreach ($mpsStudents as $mpsStudent) {
            if ($mpsStudent->student->company == $company) {
                ++$count;
            }
        }
        return $count;
    }

    

    public function list($list_type, $attendence_id)
    {
        $attendence = Attendence::find($attendence_id);
        $students = $attendence->platoon->students;
        
        // $students = Company::join('students', 'companies.name', 'students.company')->join('platoons', 'platoons.id', 'students.platoon')
        //     ->where('students.company', 'HQ')->where('students.platoon', '1')->get('students.*');
        return view('attendences.select_absent', compact('students', 'attendence_id','list_type'));
    }

    public function list_safari($list_type, $attendence_id)
    {
        $attendence = Attendence::find($attendence_id);
        $students = $attendence->platoon->students;
                return view('attendences.select_safari', compact('students', 'attendence_id','list_type'));
    }
    public function storeAbsent(Request $request, $attendence_id)
    {
        $attendence = Attendence::find($attendence_id);
        if (!$attendence) {
            abort(404);
        }
        $ids = $request->input('student_ids');
        $absent_student_ids = implode(',', $ids);
        $attendence->absent_student_ids = $absent_student_ids;
        $attendence->absent = count(value: $ids);
        $attendence->save();
        $page = $attendence->attendenceType;
        return $this->today($attendence->platoon->company_id, $attendence->attendenceType_id)->with('page',$attendence->attendenceType);
    }

    public function storeSafari(Request $request, $attendence_id)
    {
        $attendence = Attendence::find($attendence_id);
        if (!$attendence) {
            abort(404);
        }
        $ids = $request->input('student_ids');
        $safari_student_ids = implode(',', $ids);
        $attendence->safari_student_ids = $safari_student_ids;
        $attendence->safari = count(value: $ids);
        $attendence->save();
        $page = $attendence->attendenceType;
        return $this->today($attendence->platoon->company_id, $attendence->attendenceType_id, )->with('page', $page);
    }

}
