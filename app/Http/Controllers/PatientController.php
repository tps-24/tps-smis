<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Student;
use App\Models\Company;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:hospital-create')->only([
            'save',
            'sendToReceptionist'
        ]);

        $this->middleware('permission:hospital-list')->only([
            'index',
            'doctorPage'
        ]);

        $this->middleware('permission:hospital-approve')->only([
            'receptionistIndex',
            'approvePatient'
        ]);

        $this->middleware('permission:hospital-update')->only([
            'saveDetails',
            'doctorPage'
        ]);

        $this->middleware('permission:student-list')->only([
            'index'
        ]);
    }
    
    public function index(Request $request)
    {
        $sirMajor = Auth::user();
    
        // Ensure Sir Major only sees their company's data
        $patients = collect();
        $message = 'Please enter the required criteria to find patient details.';
    
        if ($request->has('company_id') || $request->has('platoon') || $request->has('fullname')) {
            $query = Patient::with('student') // ✅ Ensure we load the student relationship
            ->whereHas('student', function ($q) use ($sirMajor) {
                $q->where('company_id', $sirMajor->company_id);
            });

            
        if ($request->filled('company_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('company_id', $request->input('company_id'));
            });
        }

        if ($request->filled('platoon')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('platoon', $request->input('platoon'));
            });
        }

        if ($request->filled('fullname')) {
            $names = explode(' ', $request->input('fullname'));
            $query->whereHas('student', function ($subQuery) use ($names) {
                foreach ($names as $name) {
                    $subQuery->where(function ($q) use ($name) {
                        $q->orWhere('first_name', 'LIKE', '%' . $name . '%')
                          ->orWhere('middle_name', 'LIKE', '%' . $name . '%')
                          ->orWhere('last_name', 'LIKE', '%' . $name . '%');
                    });
                }
            });
        }

            // ✅ Order by first name in ascending order
         $patients = $query->orderBy(Student::select('first_name')
            ->whereColumn('students.id', 'patients.student_id'))->get();   
             
         $message = $patients->isEmpty() ? 'No students found with the given criteria.' : '';
    }
    

    // ✅ Statistics for Sir Major (Filtered by their company)
    $today = Carbon::today();
    $thisWeek = Carbon::now()->startOfWeek();

    $thisMonth = Carbon::now()->startOfMonth();
    $thisYear = Carbon::now()->startOfYear();

    $dailyCount = Patient::where('company_id', $sirMajor->company_id)
                        ->whereDate('created_at', $today)
                        ->count();

    $weeklyCount = Patient::where('company_id', $sirMajor->company_id)
                        ->whereBetween('created_at', [$thisWeek, Carbon::now()])
                        ->count();

    $monthlyCount = Patient::where('company_id', $sirMajor->company_id)
                        ->whereBetween('created_at', [$thisMonth, Carbon::now()])
                        ->count();

    // // ✅ Get Doctor's details for each student
    // $doctorDetails = Patient::where('company', $sirMajor->company)
    //                     ->whereNotNull('excuse_type') // Only patients with doctor input
    //                     ->orderBy('created_at', 'desc')
    //                     ->get(['first_name', 'last_name', 'excuse_type', 'rest_days', 'created_at']);




     // ✅ Fix the error by selecting student details from the relationship
     $doctorDetails = Patient::with('student:id,first_name,last_name') // Load only required student fields
     ->where('company_id', $sirMajor->company_id)
     ->whereNotNull('excuse_type') // Only patients with doctor input
     ->orderBy('created_at', 'desc')
     ->get();
     return view('hospital.index', compact('patients', 'message', 'dailyCount', 'weeklyCount', 'monthlyCount', 'doctorDetails'));

// // ✅ Update view to use `student->first_name` instead of `first_name`
// return view('hospital.index', compact('patients', 'message', 'doctorDetails'));
// }
}

public function show($id)
{
    // Fetch patient details from the database
    $patient = Patient::findOrFail($id);

    // Pass the patient data to the view
    return view('hospital.show', compact('patient'));
}
    
public function sendToReceptionist(Request $request)
{
    $user = auth()->user();
    
    // Validate input
    $request->validate([
        'student_id' => 'required|exists:students,id',
    ]);

    // Find the student
    $student = Student::where('id', $request->student_id)
                      ->where('company_id', $user->company_id) // Ensure same company
                      ->firstOrFail();

    // Store patient details
    Patient::updateOrCreate(
        ['student_id' => $student->id],
        [
            'student_id' => $student->id, // Store student_id instead of names
            'company_id' => $student->company_id,
            'platoon' => $student->platoon,
            'status' => 'pending',
        ]
    );

    return redirect()->route('hospital.index')->with('success', 'Details sent to receptionist for approval.');
}


public function receptionistIndex()
{
    // Ensure only users with the "Receptionist" role can access
    if (!auth()->user()->hasRole('Receptionist')) {
        abort(403, 'Unauthorized action.');
    }

    // Fetch only patients who are pending approval
    $patients = Patient::where('status', 'pending')
                ->with('student') // Load the related student details
                ->get();

    return view('receptionist.index', compact('patients'));
}

public function approvePatient(Request $request, $id)
{
    // Find the patient by ID
    $patient = Patient::findOrFail($id);

    // Update the patient's status to 'approved'
    $patient->status = 'approved';
    $patient->save();

    return redirect()->route('receptionist.index')->with('success', 'Patient approved and forwarded to the doctor.');
}

public function receptionistPage()
{
    // Fetch patients that need approval by the receptionist
    $patients = Patient::where('status', 'pending')->get();

    // Return the receptionist view
    return view('receptionist.index', compact('patients'));
}


public function doctorPage()
{
    if (!auth()->user()->hasRole('Doctor')) {
        abort(403, 'You do not have access to this page.');
    }

    // Fetch approved patients and include related student details
    $patients = Patient::where('status', 'approved')
                ->with('student:id,first_name,last_name') // Load only required fields
                ->get();

    return view('doctor.index', compact('patients'));

    
}

public function saveDetails(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:patients,id',
        'excuse_type' => 'required|string|max:255',
        'rest_days' => 'required|integer|min:1',
        'doctor_comment' => 'required|string'
    ]);

    $patient = Patient::findOrFail($request->student_id);
    
    // Save doctor's input
    $patient->update([
        'excuse_type' => $request->excuse_type,
        'rest_days' => $request->rest_days,
        'doctor_comment' => $request->doctor_comment,
        'status' => 'treated', // Mark as treated
    ]);

    return redirect()->route('doctor.page')->with('success', 'Patient details saved successfully.');
}


public function sirMajorStatistics()
{
    $company_id = auth()->user()->company_id; // Ensure Sir Major only sees their company's data

    $patients = Patient::where('company_id', $company_id)
        ->whereNotNull('excuse_type') // Ensure only patients with doctor inputs are retrieved
        ->orderBy('created_at', 'desc')
        ->get();

    return view('sirmajor.statistics', compact('patients'));
}

public function viewDetails(Request $request, $timeframe)
{
    $sirMajor = Auth::user();

    if (!$sirMajor) {
        return redirect()->route('login')->with('error', 'Please log in first.');
    }

    if (!in_array($timeframe, ['daily', 'weekly', 'monthly'])) {
        abort(404, 'Invalid timeframe');
    }

    $query = Patient::where('company_id', $sirMajor->company_id)
                    ->whereYear('created_at', now()->year); // Ensure only current year data is fetched

    switch ($timeframe) {
        case 'daily':
            $query->whereDate('created_at', now());
            break;
        case 'weekly':
            $query->whereBetween('created_at', [now()->startOfWeek(), now()]);
            break;
        case 'monthly':
            $query->whereBetween('created_at', [now()->startOfMonth(), now()]);
            break;
    }

    $patients = $query->get();

    return view('hospital.viewDetails', compact('patients', 'timeframe'));
}

}
