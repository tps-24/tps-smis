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
        $user = auth()->user();
        $assignedCompany = Company::find($user->company_id);
    
        // Prevent errors when $assignedCompany is null
        if (!$assignedCompany && $user->hasRole('Sir Major')) {
            return redirect()->back()->with('error', 'Your assigned company was not found.');
        }
    
        // Fetch all companies for Super Administrator, Admin, and Teacher
        if ($user->hasRole('Super Administrator') || $user->hasRole('Admin') || $user->hasRole('Teacher')|| $user->hasRole('MPS Officer')) {
            $companies = Company::all(); // Get all companies
            $query = Student::query(); // No company restriction
        } else { 
            // Sir Major should only see students from their assigned company
            $companies = collect([$assignedCompany]); 
            $query = Student::where('company_id', $user->company_id);
        }
    
        // Filtering
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
    
        if ($request->filled('platoon')) {
            $query->where('platoon', $request->platoon);
        }
    
        if ($request->filled('fullname')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'LIKE', "%{$request->fullname}%")
                  ->orWhere('last_name', 'LIKE', "%{$request->fullname}%");
            });
        }
    
        if ($request->filled('student_id')) {
            $query->where('id', (int) $request->student_id);
        }
    
        $studentDetails = $query->get();
        $message = $studentDetails->isNotEmpty() ? '' : 'No student details found for the provided search criteria';

    
   // Statistics Calculation
// $today = Carbon::today();
//  $thisWeek = Carbon::now()->startOfWeek();
// $thisMonth = Carbon::now()->startOfMonth();

// // Ensure we count from the 'patients' table, not 'students'
// $dailyCount = Patient::whereDate('created_at', $today)
//     ->where('company_id', $user->company_id)
//     ->count();

// $weeklyCount = Patient::whereBetween('created_at', [$thisWeek, Carbon::now()])
//     ->where('company_id', $user->company_id)
//     ->count();

// $monthlyCount = Patient::whereBetween('created_at', [$thisMonth, Carbon::now()])
//     ->where('company_id', $user->company_id)
//     ->count();

    // Get the authenticated user
    $user = auth()->user();

    // Define time periods before using them
    $today = Carbon::today();
    $thisWeek = Carbon::now()->startOfWeek();
    $thisMonth = Carbon::now()->startOfMonth();

    // Check if the user is Super Admin or Admin
    if ($user->hasRole(['Super Administrator', 'Admin'])) {
        // Show statistics for all companies
        $dailyCount = Patient::whereDate('created_at', $today)->count();
        $weeklyCount = Patient::whereBetween('created_at', [$thisWeek, Carbon::now()])->count();
        $monthlyCount = Patient::whereBetween('created_at', [$thisMonth, Carbon::now()])->count();
    } else {
        // Show statistics only for the assigned company
        $dailyCount = Patient::whereDate('created_at', $today)
            ->where('company_id', $user->company_id)
            ->count();
        
        $weeklyCount = Patient::whereBetween('created_at', [$thisWeek, Carbon::now()])
            ->where('company_id', $user->company_id)
            ->count();
        
        $monthlyCount = Patient::whereBetween('created_at', [$thisMonth, Carbon::now()])
            ->where('company_id', $user->company_id)
            ->count();
    }




    
// Pie Chart Data (Annual Summary)
$patientStats = Patient::whereYear('created_at', now()->year)
    ->where('company_id', $user->company_id)
    ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
    ->groupBy('month')
    ->pluck('count', 'month')
    ->toArray();

// Ensure all months are included
$patientStats = array_replace(array_fill(1, 12, 0), $patientStats);


return view('hospital.index', compact('message', 'user', 'assignedCompany', 'companies', 'dailyCount', 'weeklyCount', 'monthlyCount', 'studentDetails'));
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
    \Log::info('sendToReceptionist method called.');
    \Log::info('Received student_id: ' . $request->student_id);

    // Validate input
    $request->validate([
        'student_id' => 'required|exists:students,id',
    ]);

    // Debug query
    $student = Student::where('id', $request->student_id)->first();
    \Log::info('Student Query Result: ' . json_encode($student));

    if (!$student) {
        \Log::error('Student with ID ' . $request->student_id . ' not found!');
        return redirect()->back()->with('error', 'Student not found.');
    }

    // Store patient details
    Patient::updateOrCreate(
        ['student_id' => $student->id],
        [
            'company_id' => $student->company_id,
            'platoon' => $student->platoon,
            'status' => 'pending',
        ]
    );

    return redirect()->route('hospital.index')->with('success', 'Details sent to receptionist for approval.');
}

public function sendForApproval(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,id',
    ]);

    $student = Student::findOrFail($request->student_id);

    // Check if already pending
    if (Patient::where('student_id', $student->id)->where('status', 'pending')->exists()) {
        return response()->json(['message' => 'Patient details already sent for approval.'], 400);
    }

    // Create new patient record for approval
    Patient::create([
        'student_id' => $student->id,
        'company_id' => $student->company_id,
        'platoon' => $student->platoon,
        'status' => 'pending',
    ]);

    return response()->json(['message' => 'Patient details sent for approval successfully.']);
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

public function dispensaryPage(Request $request)
{
    $query = Patient::query();

    // Filter by company_id if provided
    if ($request->filled('company_id')) {
        $query->where('company_id', $request->company_id);
    }

    // Filter by platoon if provided
    if ($request->filled('platoon')) {
        $query->where('platoon', $request->platoon);
    }

    $today = Carbon::today();
    $thisWeek = Carbon::now()->startOfWeek();
    $thisMonth = Carbon::now()->startOfMonth();
    $thisYear = Carbon::now()->startOfYear();

    // Count statistics based on the selected filters
    $dailyCount = (clone $query)->whereDate('created_at', $today)->count();
    $weeklyCount = (clone $query)->whereBetween('created_at', [$thisWeek, Carbon::now()])->count();
    $monthlyCount = (clone $query)->whereBetween('created_at', [$thisMonth, Carbon::now()])->count();

    // Fetch list of companies
    $companies = Company::all();

    // Patient distribution for the selected year (used in Pie Chart)
    $patientDistribution = (clone $query)
        ->whereBetween('created_at', [$thisYear, Carbon::now()])
        ->selectRaw('platoon, COUNT(*) as count')
        ->groupBy('platoon')
        ->pluck('count', 'platoon');

    return view('dispensary.index', compact('dailyCount', 'weeklyCount', 'monthlyCount', 'patientDistribution', 'companies'));
}


}
