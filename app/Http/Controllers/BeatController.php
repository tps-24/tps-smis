<?php

namespace App\Http\Controllers;

use App\Models\Beat;
use App\Models\Company;
use App\Models\Area;
use App\Models\Student;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class BeatController extends Controller
{

    protected $round = 1;
    protected $start_at;
    protected $end_at;
    protected $area_id;
    protected $beatType_id;
    protected $company_id;

    public function __construct()
    {

        // $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','view']]);
        // $this->middleware('permission:user-create', ['only' => ['create','store']]);
        // $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:user-delete', ['only' => ['destroy']]);
        // $this->middleware('permission:user-profile-list|user-profile-create|user-profile-edit|user-profile-delete', ['only' => ['profile']]);
        // $this->middleware('permission:user-profile-edit', ['only' => ['updateProfile']]);

        // $this->area_id = $area_id;
        // $this->beatType_id = $beatType_id;
        // $this->company_id = $company_id;

        $companyBeat = Beat::orderBy('beats.id', 'desc')
            ->leftJoin('students', 'students.id', 'beats.student_id')
            ->leftJoin('companies', 'companies.name', 'students.company')
            ->where('companies.id', $this->company_id)
            ->select('beats.*')
            ->get();
        if (count($companyBeat) > 1) {
            $this->round = $companyBeat[0]->round;
        } else {
            $this->round = 1;
        }

        //$this->start_at = Carbon::createFromTime(18, 00, 0)->format('H:i:s');
        //$this->end_at = Carbon::createFromTime(00, 00, 0)->format('H:i:s');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $areas = Area::all();
        $companies = Company::all();
        return view('beats.index', compact('areas','companies'));
    }

    public function list_guards($area_id)
    {
        $beats = Area::find($area_id)->beats()->where('beatType_id', 1)->get();
        return view('beats.list_guards', compact('beats'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create($area_id)
    {
        $area = Area::find($area_id);
        if (!$area) {

        }
        $companies = Company::all();

        return view('beats.search_students', compact('area', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($area_id, $beatType_id, $company_id,$start_at, $end_at)
    {
        // $area = Area::find($area_id);
        // $students_ids = $request->input('student_ids');
        // for ($i = 0; $i < count($students_ids); ++$i) {
        //     Beat::create([
        //         'area_id' => $area->id,
        //         'student_id' => $students_ids[$i],
        //         'assigned_by' => Auth::user()->id,
        //         'start_at' => $request->start_at,
        //         'end_at' => $request->end_at
        //     ]);

        // }
        // $area->is_assigned = true;
        // $area->save();
        // return $students_ids[0];

         $this->area_id=$area_id;
        $this->beatType_id =$beatType_id;
        $this->company_id = $company_id;
        $this->start_at = $start_at;
        $this->end_at = $end_at;

        $platoon = 1;
        $company = Company::find($company_id);
        $students = $company->students()->orderBy('id')->get();
        $area = Area::find($area_id);
        $number_of_students = $area->number_of_guards;
        /**
         * Get Platoon students.
         */
        $beat = Beat::orderBy('id', 'desc');

        if (count($beat->get()) > 0) {
            $this->round = $beat->get()[0]->round;
            /**
             * Get students that do not appear on the beat table
             */
            $beat_students = $company->students()->leftJoin('beats', 'students.id', '=', 'beats.student_id')
                ->orderBy('platoon')
                ->where('students.vitengo_id', NULL)
                ->where('beats.student_id', NULL)
                ->select('students.*');
            if ($beat_students->get()->isNotEmpty()) {
                if (count($beat_students->get()) >= $number_of_students) {
                    return $this->store_beat($company, $area_id, $beatType_id, $beat_students->take($number_of_students)->get());
                } else {
                    $this->store_beat($company, $area_id, $beatType_id, $beat_students->get());
                    if ($platoon == 14) {
                        $platoon = 1;
                    } else {
                        ++$platoon;
                    }
                    $this->store_beat($company, $area_id, $beatType_id, $this->get_platoon_students($company, $platoon, $number_of_students - count($beat_students->get())));
                }
            } else {
                $not_attended = Beat::where('status', 0)->get();
                if ($not_attended->isNotEmpty()) {
                    $this->store_beat($company, $area_id, $beatType_id, $this->get_platoon_students($company, $platoon, $number_of_students - count($not_attended)));
                } else {
                    $lastBeat = Beat::orderBy('id', 'desc')->get()[0];
                    $index = $students->search(function ($student) use ($lastBeat) {
                        return $student->id == $lastBeat->student_id; // Compare directly with student_id
                    });
                    if (count($students->slice($index + 1)->values()) < $number_of_students) {
                        $extra = $number_of_students - count($students->slice($index + 1)->values());
                        $this->store_beat($company, $area_id, $beatType_id, $students->slice($index + 1)->values());
                        $this->store_beat($company, $area_id, $beatType_id, $students->slice(0, $extra)->values());
                    } else {
                        $this->store_beat($company, $area_id, $beatType_id, $students->slice($index + 1, $number_of_students)->values());
                    }
                    return $students->slice($index + 1, $number_of_students)->values();
                }
            }

        } else {

            $this->store_beat($company, $area_id, $beatType_id, $this->get_platoon_students($company, $platoon, $number_of_students));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($area_id)
    {
        $area = Area::find($area_id);
        $todayBeats = $area->beats()
            ->whereDate('date', Carbon::today())
            ->get();
        $tomorowBeats = $area->beats()
            ->whereDate('date', Carbon::tomorrow())
            ->get();

        return view('beats.show', compact('todayBeats', 'tomorowBeats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Beat $beat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Beat $beat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Beat $beat)
    {
        //
    }

    public function search(Request $request, $area_id)
    {
        $area = Area::find($area_id);
        $validatedData = $request->validate([
            'company' => 'required',
            'platoon' => 'required',
        ]);
        $companies = Company::all();
        $students = Student::where('company', $request->company)->where('platoon', $request->platoon)->get();
        //if($students && $request->name){
        // $students = $students->where('last_name','LIKE', "%{$request->name}%")
        // ->orWhere('first_name','LIKE', "%{$request->name}%")->get();
        // }
        return view('beats.search_students', compact('area', 'companies', 'students'));
    }

    public function update_area(Request $request, $area_id){
        $area = Area::findOrFail($area_id);
        $request->validate([
            'name' => 'required',
            'company' => 'required',
            'number_of_guards' => 'required'
        ]);

        $area-> update([
            'name' => $request->name,
            'company_id' => $request->company,
            'number_of_guards' => $request->number_of_guards
        ]);

        return redirect()->back()->with('success', 'Area updated Successfully.');

    }
    public function assign_beats()
    {


    }

    public function store_beat($company, $area_id, $beatType_id, $students)
    {

        $last_student = $company->students()->orderBy('id', 'desc')->get()[0];
        /**
         *  Assign students to a beat
         */
        foreach ($students as $student) {
            /**
             * Needs modifications to check if student is the last one
             */

            if ($last_student->id == $student->id) {
                $this->round += 1;
            }
            Beat::create([
                'beatType_id' => $beatType_id,
                'area_id' => $area_id,
                'student_id' => $student->id,
                'round' => $this->round,
                'date' => Carbon::tomorrow()->format('d-m-Y'),
                'start_at' =>  Carbon::createFromTime($this->start_at, 00, 0)->format('H:i:s'),
                'end_at' => Carbon::createFromTime($this->end_at, 00, 0)->format('H:i:s')
            ]);
        }
    }

    public function get_platoon_students($company, $platoon, $count)
    {
        $platoon_students = new \Illuminate\Database\Eloquent\Collection();
        do {
            $students_to_push = $company->students()->where('platoon', $platoon)->take($count)->get();
            if ($students_to_push->isNotEmpty())
                foreach ($students_to_push as $student_to_push) {
                    $platoon_students->push($student_to_push);
                    --$count;
                }
            ++$platoon;
        } while (count($platoon_students) < $count);
        return $platoon_students;
    }

    public function approve_presence(Request $request)
    {
        $beat_ids = $request->input('beat_ids');
        foreach ($beat_ids as $beat_id) {
            $beat = Beat::find($beat_id);
            if ($beat) {
                $beat->update([
                    'status' => 1
                ]);
            }

        }
        return redirect('/beats')->with('success', "Ok");
    }


}
