<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use App\Models\ExcuseType;
use App\Models\Student;
use App\Models\Company;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;


class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:hospital-create')->only([
            'save',
            'sendToReceptionist',
            'index',
            'sirMajorStatistics'
        ]);

        $this->middleware('permission:hospital-list')->only([
            'index',
            'doctorPage'
        ]);

        $this->middleware('permission:hospital-approve')->only([
            'receptionistIndex',
            'approvePatient'
        ]);

        $this->middleware('permission:hospital-edit')->only([
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

    
        // Fetch staff record for the logged-in user
        $staff = $user->staff; 
    
        // Get company_id from staff table
        $assignedCompany = $staff ? Company::find($staff->company_id) : null;
    
        // Check if the user has the Sir Major role and ensure they have an assigned company
        if (!$assignedCompany && $user->hasRole('Sir Major')) {
            return redirect()->back()->with('error', 'Your assigned company was not found.');
        }
    
        // Super Admin, Admin, Teacher, and MPS Officer can view all companies
        if ($user->hasRole(['Super Administrator', 'Admin', 'Teacher', 'MPS Officer'])) {
            $companies = Company::all();
            $query = Student::query();
        } else {
            // Sir Major can only see students from their assigned company
            $companies = collect([$assignedCompany]);
            $query = Student::where('company_id', $staff->company_id);
        }
    
        // Filtering based on selected company
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
    
        // Get statistics
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
    
        $companyId = $request->company_id ?? ($staff ? $staff->company_id : null);
    
        $query = Patient::whereDate('created_at', $today);
        $weeklyQuery = Patient::whereBetween('created_at', [$thisWeek, Carbon::now()]);
        $monthlyQuery = Patient::whereBetween('created_at', [$thisMonth, Carbon::now()]);
    
        if ($companyId) {
            $query->where('company_id', $companyId);
            $weeklyQuery->where('company_id', $companyId);
            $monthlyQuery->where('company_id', $companyId);
        }
    
        $dailyCount = $query->count();
        $weeklyCount = $weeklyQuery->count();
        $monthlyCount = $monthlyQuery->count();
    
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
                ->with('student:id,first_name,last_name')
                ->get();

    // Fetch excuse names from excuse_types table
    $excuseTypes = ExcuseType::pluck('excuseName', 'id');

    return view('doctor.index', compact('patients', 'excuseTypes'));
}

public function saveDetails(Request $request)
{
    // Log the incoming student_id
    \Log::info('Incoming student_id:', ['student_id' => $request->student_id]);

    $patient = Patient::find($request->student_id);

    if (!$patient) {
        return redirect()->back()->with('error', 'Patient record not found.');
    }

    // Proceed with saving details
    $patient->excuse_type_id = $request->excuse_type_id;
    $patient->doctor_comment = $request->doctor_comment;
    $patient->rest_days = $request->rest_days;
    $patient->status = 'treated'; 
    $patient->admitted_type = $request->admitted_type ?? null;
    // $patient->discharge_date = Carbon::now()->addDays((int) $request->rest_days);

    $patient->save();

    // Update beat_status in students table
    $student = Student::where('id', $patient->student_id)->first();
    if ($student) {
        $student->beat_status = 0;
        $student->save();
    }

    return redirect()->back()->with('success', 'Patient details saved successfully.');
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

    // Fetch filters
    $company_id = $request->input('company_id', $sirMajor->company_id);
    $platoon = $request->input('platoon');

    // Start query
    $query = Patient::query();

    // Apply filters
    if ($company_id) {
        $query->where('company_id', $company_id);
    }

    if ($platoon) {
        $query->where('platoon', $platoon);
    }

    // Filter by timeframe
    switch ($timeframe) {
        case 'daily':
            $query->whereDate('created_at', Carbon::today());
            break;
        case 'weekly':
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()]);
            break;
        case 'monthly':
            $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()]);
            break;
    }

    // Fetch patients
    $patients = $query->get();

    // Check and return with a message if empty
    if ($patients->isEmpty()) {
        return view('hospital.viewDetails', [
            'patients' => [],
            'timeframe' => $timeframe,
            'company_id' => $company_id,
            'platoon' => $platoon,
            'message' => 'No patients found for this timeframe.',
        ]);
    }

    return view('hospital.viewDetails', compact('patients', 'timeframe', 'company_id', 'platoon'));
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


public function store(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'status' => 'required|in:Admitted,Excuse Duty,Light Duty',
        'rest_days' => 'required|integer|min:1',
    ]);

    $patient = new Patient();
    $patient->student_id = $request->student_id;
    $patient->company_id = $request->company_id;
    $patient->platoon = $request->platoon;
    $patient->status = $request->status;
    $patient->rest_days = $request->rest_days;
    $patient->save();

    // Update the student's beat_status to 0 if they are sick
    Student::where('id', $request->student_id)->update(['beat_status' => 0]);

    return back()->with('success', 'Patient record added successfully.');
}


public function updateSickReport(Request $request, $student_id)
{
    $student = Student::findOrFail($student_id);

    // Check if the student is a patient
    if (in_array($request->status, ['Light Duty', 'Excuse Duty', 'Admitted'])) {
        // Set beat_status to 0 and define sick period
        $student->beat_status = 0;
        $student->rest_days = $request->rest_days;
        $student->sick_until = Carbon::now()->addDays($request->rest_days);
    }

    $student->save();
}

public function discharge($id)
{
    $patient = Patient::findOrFail($id);

    if ($patient->is_discharged) {
        return response()->json(['success' => false, 'message' => 'Patient already discharged.']);
    }

    $patient->is_discharged = true;
    $patient->discharged_date = now();
    $patient->save();

    // Reset student's beat status
    if ($patient->student) {
        $patient->student->beat_status = 1;
        $patient->student->save();
    }

    return response()->json(['success' => true, 'message' => 'Patient discharged successfully.']);
}


public function downloadStatisticsReport(Request $request, $timeframe)
{
    $company_id = $request->input('company_id');
    $platoon = $request->input('platoon');

    // Fetch patient details based on the timeframe
    $query = Patient::query()->whereYear('created_at', now()->year);

    if ($company_id) {
        $query->where('company_id', $company_id);
    }

    if ($platoon) {
        $query->where('platoon', $platoon);
    }

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

    // Fetch students who have received an excuse type 5+ times
    $frequentExcuses = Student::select('students.first_name', 'students.last_name', 'patients.platoon')
        ->join('patients', 'students.id', '=', 'patients.student_id')
        ->selectRaw('COUNT(patients.excuse_type_id) as excuse_count')
        ->groupBy('students.id', 'students.first_name', 'students.last_name', 'patients.platoon')
        ->having('excuse_count', '>=', 5)
        ->orderByDesc('excuse_count')
        ->get();

    // Load the PDF view with data
    $pdf = Pdf::loadView('pdf.statistics', compact('patients', 'frequentExcuses', 'timeframe', 'company_id', 'platoon'));

    return $pdf->download('statistics_report.pdf');
}

}

