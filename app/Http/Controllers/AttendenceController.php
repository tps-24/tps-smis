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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;


class AttendenceController extends Controller
{
    private $user;
    private $companies;
    private $selectedSessionId;
    public function __construct()
    {
        $this->selectedSessionId = session('selected_session');
        if (!$this->selectedSessionId)
            $this->selectedSessionId = 1;

        $this->middleware('permission:attendance-list|attendance-create|attendance-edit|attendance-delete', ['only' => ['index', 'today', 'attendence']]);
        $this->middleware('permission:attendance-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:attendance-delete', ['only' => ['destroy']]);


    }
    /**
     */
    public function index()
    {
        return redirect()->to('attendences/type/1');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $attendenceType_id)
    {
        $attendenceType = AttendenceType::find($attendenceType_id);
        $platoon = Platoon::find($request->platoon);

        $students = Student::where('company_id', $platoon->company_id)
                    ->where('session_programme_id',$this->selectedSessionId)
                    ->where('platoon', $platoon->name)
                    ->whereNotIn('id',$this->getKaziniStudentsIds($platoon))
                    ->get();
                   
        return view(
            'attendences/create',
            compact('students', 'attendenceType', 'platoon')
        );
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
    public function today($company_id, $type, $date)
    {
        $attendences = new \Illuminate\Database\Eloquent\Collection;
        $company = Company::find($company_id);
        $page = AttendenceType::find($type);
        $date = Carbon::parse($date)->format('Y-m-d');
        foreach ($company->platoons as $platoon) {
            if ($platoon->attendences->isNotEmpty()) {
                $platoon_attendences = $platoon->attendences()->where('attendenceType_id', $type)->whereDate('created_at', $date)->get();
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

        // dd($attendences);
        return view('attendences.attended', compact('attendences', 'page', 'company', 'date'));
    }
    protected function statistics($attendence, $company_id)
    {
        //return count($attendence[0]);
        $data = new \Illuminate\Database\Eloquent\Collection();
        $present = 0;
        $absent = 0;
        $total = 0;
        $sick = 0;
        $safari = 0;
        $off = 0;
        $mps = 0;
        for ($i = 0; $i < count($attendence); ++$i) {
            if (count($attendence[$i]) > 0) {
                $present += $attendence[$i][0]['present'];
                $absent += $attendence[$i][0]['absent'];
                $safari += $attendence[$i][0]['safari'];
                $total += $attendence[$i][0]['total'];
                // $present += $attendence[$i + 1][0]['present'];
                // $absent += $attendence[$i + 1][0]['absent'];
                // $safari += $attendence[$i + 1][0]['safari'];
                // $total += $attendence[$i + 1][0]['total'];
                // $data->put($i +1, $attendence[$i+ 1][0]['present']);
            } else {

            }
        }

        $mps = $this->getMPSdata($company_id);
        $data->put('present', $present);
        $data->put('absent', $absent);
        $data->put('total', $total - $mps);
        $data->put('sick', $sick);
        $data->put('safari', $safari);
        $data->put('mps', $mps);
        return $data;
    }

    protected function setZero($company_id)
    {
        return [
            'present' => 0,
            'absent' => 0,
            'total' => 0,
            'sick' => 0,
            'safari' => 0,
            'mps' => $this->getMPSdata($company_id),
        ];
    }

    public function testAttendence($type)
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
            $statistics->put($company->name, $this->statistics($attendences[$company->id], $company->name));
        }
        return view('attendences/index', compact('statistics', 'companies', 'page'));
    }

    public function attendence(Request $request, $type)
    {

        if (!$request->date) {
            $date = Carbon::today();
        } else {
            $date = $request->date;
        }
        $date = Carbon::parse($date)->format('Y-m-d');
        $attendenceType = AttendenceType::find($type);
        $page = $attendenceType;
        $roles = Auth::user()->roles;
        foreach ($roles as $role) {
            if (
                $role->name == 'Admin' ||
                $role->name == 'Academic Coordinator' ||
                $role->name == 'Super Administrator' ||
                $role->name == 'Chief Instructor' ||
                $role->name == 'Staff Officer'
            ) {
                $this->companies = Company::all();
            } else if ($role->name == 'Teacher' || $role->name == 'Sir Major') {
                //return Auth::user()->staff;
                $this->companies = [Auth::user()->staff->company];
                if (count($this->companies) != 0)
                    if ($this->companies[0] == null) {
                        return view('attendences/index', compact('page', 'date'));
                    }
            } else {
                abort(403);
            }
        }
        $statistics = [];
        foreach ($this->companies as $company) {
            $company_stats = [];
            foreach ($company->platoons as $platoon) {
                if (count($platoon->attendences()->whereDate('created_at', $date)->where('attendenceType_id', $type)->get()) > 0) {
                    array_push($company_stats, $platoon->attendences()->whereDate('created_at', $date)->where('attendenceType_id', $type)->get());
                }
            }
            array_push($statistics, [
                'company_name' => $company->name,
                'statistics' => count($company_stats) > 0 ? $this->statistics($company_stats, $company->id) : $this->setZero($company->id)
            ]);
        }
        $companies = $this->companies;
        return view('attendences/index', compact('statistics', 'companies', 'page', 'date'));

    }

    public function getMPSdata($company_id)
    {
        $mpsStudents = MPS::whereDate('created_at', Carbon::today())->orWhereNotNull('released_at')->get();
        $count = 0;
        foreach ($mpsStudents as $mpsStudent) {
            if ($mpsStudent->student->company_id == $company_id) {
                ++$count;
            }
        }
        return $count;
    }



    public function list($list_type, $attendence_id, $date)
    {
        $attendence = Attendence::find($attendence_id);
        $students = $attendence->platoon->students()->where('session_programme_id',$this->selectedSessionId)->orderBy('first_name')->get();
        $absent_student_ids = explode(",", $attendence->absent_student_ids);
        // $students = Company::join('students', 'companies.name', 'students.company')->join('platoons', 'platoons.id', 'students.platoon')
        //     ->where('students.company', 'HQ')->where('students.platoon', '1')->get('students.*');
        return view('attendences.select_absent', compact('students', 'attendence_id', 'list_type', 'absent_student_ids', 'date'));
    }

    public function list_safari($list_type, $attendence_id)
    {
        $attendence = Attendence::find($attendence_id);
        $students = $attendence->platoon->students;
        return view('attendences.select_safari', compact('students', 'attendence_id', 'list_type'));
    }
    public function storeAbsent(Request $request, $attendence_id, $date)
    {
        $attendence = Attendence::find($attendence_id);
        if (!$attendence) {
            abort(404);
        }
        $ids = $request->input('student_ids');
        $absent_student_ids = implode(',', $ids);
        $attendence->absent_student_ids = $absent_student_ids;
        $attendence->absent = count(value: $ids);
        $attendence->present = $attendence->total - $attendence->mps - $attendence->absent;
        $attendence->save();
        $page = $attendence->attendenceType;
        return $this->today($attendence->platoon->company_id, $attendence->attendenceType_id, $date)->with('page', $attendence->attendenceType);
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
        return $this->today($attendence->platoon->company_id, $attendence->attendenceType_id, Carbon::today())->with('page', $page);
    }

    public function getPlatoons($company_id)
    {
        $company = Company::find($company_id);
        return response()->json($company->platoons);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $type, $platoon_id)
    {
        $ids = $request->input('student_ids');
        if ($ids == NULL) {
            return redirect()->route('attendences.index')->with('success', "Attendences for this platoon already recorded.");
        }
        // $female = $students->where('gender', 'F')->count();
        // $male = $students->where('gender', 'M')->count();
        $present = count($ids);
        $platoon = Platoon::find($platoon_id);
        $platoonStudents = Student::where('company_id', $platoon->company_id)
        ->where('session_programme_id', $this->selectedSessionId)
        ->where('platoon', $platoon->name);
        $students = $platoonStudents->pluck('id')->toArray();
        $female = Student::where('company_id', $platoon->company_id)
                            ->where('session_programme_id', $this->selectedSessionId)
                            ->where('platoon', $platoon->name)  
                            ->where('gender', 'F')->count();
        $male = Student::where('company_id', $platoon->company_id)
                        ->where('session_programme_id', $this->selectedSessionId)
                        ->where('platoon', $platoon->name)  
                        ->where('gender', 'M')->count();
        
        $absent_ids = array_values(array_diff($students, $ids, $this->getKaziniStudentsIds($platoon)));
        $total = count($students);
        
        
        $todayRecords = Attendence::leftJoin('platoons', 'attendences.platoon_id', 'platoons.id')
            ->where('attendences.platoon_id', $platoon_id)
            ->whereDate('attendences.created_at', Carbon::today())->get();
        if (!$todayRecords->isEmpty()) {
            return redirect()->route('attendences.index')->with('success', "Attendences for this platoon already recorded.");
        }
        for($i = 0; $i<count($absent_ids); $i++){
            $student = Student::find($absent_ids[$i]);
                if($student->gender == 'M'){
                    $male-=1;
                }else if($student->gender = 'F'){
                    $female -=1;
                }
        }
        $page = AttendenceType::find($type);
        $lockUp = $this->getLockUpStudentsIds($platoon);
        $kazini = $this->kaziniPlatoonStudents($platoon);
        Attendence::create([
            'attendenceType_id' => $type,
            'platoon_id' => $platoon_id,
            'present' => $present - count($lockUp),
            'sentry' => 0,
            'absent' => count($absent_ids),
            'adm' => 0,
            'safari' => 0,
            'off' => 0,
            'mess' => 0,
            'female' => $female,
            'male' => $male,
            'lockUp' => count($lockUp),
            'kazini' => $kazini,
            'lockUp_students_ids' =>count($lockUp) > 0?  json_encode($lockUp): NULL,
            'absent_student_ids' =>count($absent_ids) > 0? implode(',', $absent_ids): NULL,
            'total' => $total
        ]);
        
        return redirect()->route('attendences.index')->with('success','Attendances saved successfully.');

    }

    public function day_report($companyId, $date)
    {
        $summary = [];
        $total_present = 0;
        $total_absent = 0;
        $total = 0;
        $company = Company::find($companyId);
        foreach ($company->platoons as $platoon) {
            $attendances = $platoon->attendences()->whereDate('created_at', $date)->get();
            if ($attendances->isNotEmpty()) {
                foreach ($attendances as $attendance) {
                    $summary[$platoon->id] = ['present' => $attendance->present, 'absent' => $attendance->absent, 'total' => $attendance->total];
                    $total_present += $attendance->present;
                    $total_absent += $attendance->absent;
                    $total += $attendance->total;
                }
            } else {
                $summary[$platoon->id] = ['present' => 0, 'absent' => 0, 'total' => 0];
            }
        }
        return [
            'summary' => $summary,
            'total_present' => $total_present,
            'total_absent' => $total_absent,
            'total' => $total,
            'absent_percentage' => $total != 0 ? round(($total_absent / $total) * 100, 4) : 0
        ];        
    }
    public function current_week_report($companyId)
    {
        $company = Company::find($companyId);
        $summary = [];
        $total_present = $total_absent = $total = 0;

        // Determine the start and end dates of the current week
        $startDate = Carbon::now()->startOfWeek()->toDateString();
        $endDate = Carbon::now()->endOfWeek()->toDateString();

        foreach ($company->platoons as $platoon) {
            $platoon_summary = ['present' => 0, 'absent' => 0, 'total' => 0];

            $attendances = $platoon->attendences()->whereBetween('created_at', [$startDate, $endDate])->get();

            foreach ($attendances as $attendance) {
                $platoon_summary['present'] += $attendance->present;
                $platoon_summary['absent'] += $attendance->absent;
                $platoon_summary['total'] += $attendance->total;
                $total_present += $attendance->present;
                $total_absent += $attendance->absent;
                $total += $attendance->total;
            }

            $summary[$platoon->id] = $platoon_summary;
        }

        $absent_percentage = $total > 0 ? round(($total_absent / $total) * 100, 4) : 0;

        return [
            'summary' => $summary,
            'total_present' => $total_present,
            'total_absent' => $total_absent,
            'total' => $total,
            'absent_percentage' => $absent_percentage
        ];
    }

    public function report_for_date_range($companyId, $startDate, $endDate)
    {
        $company = Company::find($companyId);
        $summary = [];
        $total_present = $total_absent = $total = 0;

        foreach ($company->platoons as $platoon) {
            $platoon_summary = ['present' => 0, 'absent' => 0, 'total' => 0];

            $attendances = $platoon->attendences()->whereBetween('created_at', [$startDate, $endDate])->get();

            foreach ($attendances as $attendance) {
                $platoon_summary['present'] += $attendance->present;
                $platoon_summary['absent'] += $attendance->absent;
                $platoon_summary['total'] += $attendance->total;
                $total_present += $attendance->present;
                $total_absent += $attendance->absent;
                $total += $attendance->total;
            }

            $summary[$platoon->id] = $platoon_summary;
        }

        $absent_percentage = $total > 0 ? round(($total_absent / $total) * 100, 4) : 0;

        return [
            'summary' => $summary,
            'total_present' => $total_present,
            'total_absent' => $total_absent,
            'total' => $total,
            'absent_percentage' => $absent_percentage
        ];
    }

    public function generatePdf($companyId, $date)
    {
        $company = Company::find($companyId);
        //return view('attendences.daily_report', compact('company', 'date'));
        $pdf = Pdf::loadView('attendences.daily_report', compact('company', 'date'));
        return $pdf->download($date . "-" . $company->name . "-attendance.pdf");
    }

    public function changanua($attendenceId){
        $attendence = Attendence::find($attendenceId);
        $platoon = $attendence->platoon;
        $kaziniStudentsId = $this->getKaziniStudentsIds($platoon);
    //     return Student::whereIn('id', $kaziniStudentsId)->get();
    //    return count($kaziniStudentsId);
        $absent = $attendence->absent_student_ids !=null? explode(",",$attendence->absent_student_ids): [];
        $sentry = $attendence->sentry_student_ids !=null? explode(",",$attendence->sentry_student_ids): [];
        $mess = $attendence->mess_student_ids !=null? explode(",",$attendence->mess_student_ids): [];
        $safari = $attendence->safari_student_ids !=null? explode(",",$attendence->safari_student_ids): [];
        $off = $attendence->off_student_ids !=null? explode(",",$attendence->off_student_ids): [];
      
        $notEligibleStudent_ids = array_merge($absent,$sentry,$mess, $safari,$off,$kaziniStudentsId);
        $students = $platoon->students->where('company_id', $platoon->company_id)->where('session_programme_id',$this->selectedSessionId)->whereNotIn('id',$notEligibleStudent_ids)->values();
        $sentry_students =  $platoon->students->whereIn('id',$sentry)->values();
        $mess_students =  $platoon->students->whereIn('id',$mess)->values();
        $off_students =  $platoon->students->whereIn('id',$off)->values();
        $safari_students =  $platoon->students->whereIn('id',$safari)->values();
        return view(
            'attendences.changanua', 
            compact(
                'students', 
                'attendence',
                'sentry_students',
                'mess_students',
                'off_students',
                'safari_students'
            ));
    }

    public function storeMchanganuo(Request $request,$attendenceId){
        $attendence = Attendence::find($attendenceId);
        $sentry_ids = $request->input('sentry_student_ids');
        $off_ids = $request->input('off_student_ids');
        $mess_ids = $request->input('mess_student_ids');
        $safari_ids = $request->input('safari_student_ids');
        //return $sentry_ids;
        if($sentry_ids){
            $attendence->sentry_student_ids = implode(",",$sentry_ids);
            $attendence->sentry = count($sentry_ids);
            $attendence->present -= count($sentry_ids);
        }
        if($off_ids){
            $attendence->off_student_ids = implode(",",$off_ids);
            $attendence->off = count($off_ids);
            $attendence->present -= count($off_ids);
        }
        //}else if($request->type == "adm"){
            // $attendence->adm_student_ids = implode(",",$ids);
            // $attendence->adm = count($ids);
            // $attendence->present -= count($ids);
            if($mess_ids){
            $attendence->mess_student_ids = implode(",",$mess_ids);
            $attendence->mess = count($mess_ids);
            $attendence->present -= count($mess_ids);
            }
            if($safari_ids){
            $attendence->safari_student_ids = implode(",",$safari_ids);
            $attendence->safari = count($safari_ids);
            $attendence->present -= count($safari_ids);
        }
        
        $attendence->save();

        return redirect()->to('attendences/type/'.$attendence->attendenceType_id)->with('success', "Attendance updated successfully.");
    }

    private function getLockUpStudentsIds($platoon){
        $lockUpStudentsIds = $platoon->lockUp()->whereNull('released_at')->pluck('students.id');
        return $lockUpStudentsIds;
    }

    private function kaziniPlatoonStudents($platoon){
    // Fetch students based on the collected student IDs and platoon name

    //return $students->pluck('platoon');
    return count($this->getKaziniStudentsIds($platoon));
    }

    private function getKaziniStudentsIds($platoon){
        $date = Carbon::yesterday()->format('Y-m-d');
        $guardAreas = $platoon->company->guardAreas;
        $patrolAreas = $platoon->company->patrolAreas;
        $beats = collect();
        //Loop for each guard area and patrol area then merge to the collection beat
        //so as to get all beats for specified date.
        foreach($guardAreas as $guardArea){
            $beats= $beats->merge($guardArea->beats()->where('date',$date)->where(function ($query) {
                $query->where('start_at', '18:00:00')
                      ->orWhere('start_at', '00:00:00');
            })->get());
        }
        foreach($patrolAreas as $patrolArea){
            $beats= $beats->merge($patrolArea->beats()->where('date',$date)->where(function ($query) {
                $query->where('start_at', '18:00:00')
                      ->orWhere('start_at', '00:00:00');
            })->get());        }
        
        
        /**
         * Iterate the beats find the students and assign the number of students who are on duty
         */
        $studentIds = [];  // Initialize as a collection to use the merge method correctly
    foreach ($beats as $beat) {
        $studentIds = array_merge($studentIds,json_decode($beat->student_ids));
    }
    $students = Student::whereIn('id', $studentIds)->where('platoon', $platoon->name)->get();

    $studentIds = $students->pluck('id')->toArray();
    return $studentIds;
    }
}
