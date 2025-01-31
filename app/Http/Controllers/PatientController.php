<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Student;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:hospital-create')->only(['save', 'sendToReceptionist','index']);
        $this->middleware('permission:hospital-list')->only(['doctorIndex', 'receptionistIndex']);
        $this->middleware('permission:hospital-approve')->only(['receptionistIndex', 'receptionistPage', 'approvePatient']);
        $this->middleware('permission:hospital-update')->only(['saveDetails', 'doctorPage']);
        $this->middleware('permission:student-list')->only(['index']);
    }


    public function index(Request $request)
    {
        $patients = collect(); // Empty collection by default
        $message = 'Please enter the required criteria to find patient details.'; // Default message

        // Check if there is a search query
        if ($request->has('company') || $request->has('platoon') || $request->has('fullname')) {
            $query = Student::query();

            // Apply filters from the request
            if ($request->filled('company')) {
                $query->where('company', $request->input('company'));
            }

            if ($request->filled('platoon')) {
                $query->where('platoon', $request->input('platoon'));
            }

            if ($request->filled('fullname')) {
                $names = explode(' ', $request->input('fullname'));
                $query->where(function ($subQuery) use ($names) {
                    foreach ($names as $name) {
                        $subQuery->orWhere('first_name', 'LIKE', '%' . $name . '%')
                                 ->orWhere('middle_name', 'LIKE', '%' . $name . '%')
                                 ->orWhere('last_name', 'LIKE', '%' . $name . '%');
                    }
                });
            }

            // Execute query and fetch results
            $patients = $query->orderBy('first_name', 'asc')->get();

            // Update message if no patients found
            if ($patients->isEmpty()) {
                $message = 'No students found with the given criteria.';
            } else {
                $message = ''; // Clear the message if results are found
            }
        }

        return view('hospital.index', compact('patients', 'message'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'excuse_type' => 'required|string',
            'rest_days' => 'required|integer|min:1',
            'doctor_comment' => 'required|string',
        ]);

        // Retrieve the student's details
        $student = Student::findOrFail($request->student_id);

        // Create or update the patient record
        Patient::create([
            'student_id' => $student->id,
            'excuse_type' => $request->excuse_type,
            'rest_days' => $request->rest_days,
            'doctor_comment' => $request->doctor_comment,
            'first_name' => $student->first_name,
            'middle_name' => $student->middle_name,
            'last_name' => $student->last_name,
        ]);

        return redirect()->route('hospital.index')->with('success', 'Patient details saved successfully!');
    }


//     public function approve($id)
// {
//     $patient = Patient::findOrFail($id);
//     $patient->update(['is_approved' => true]);

//     return back()->with('success', 'Patient details sent to the receptionist for approval.');
// }

public function receptionistIndex()
{
    $patients = Patient::where('status', 'pending')->get();

    return view('receptionist.index', compact('patients'));
}

// public function approve($id)
// {
//     $patient = Patient::findOrFail($id);
//     $patient->update(['status' => 'approved']);

//     return redirect()->route('receptionist.index')->with('success', 'Patient details sent to the doctor.');
// }

public function doctorIndex()
{
    $patients = Patient::where('status', 'approved')->get();

    return view('doctor.index', compact('patients'));
}

public function sendToReceptionist(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,id',
    ]);

    // Retrieve the student data
    $student = Student::findOrFail($request->student_id);

    // Insert or update the patient record
    $patient = Patient::updateOrCreate(
        ['student_id' => $student->id],
        [
            'first_name' => $student->first_name,
            'last_name' => $student->last_name,
            'company' => $student->company,
            'platoon' => $student->platoon,
            'status' => 'pending',
        ]
    );

    return redirect()->route('hospital.index')->with('success', 'Details sent to receptionist for approval.');
}

public function receptionistPage()
{
    $patients = Patient::where('status', 'pending')->get(); // Only fetch patients with 'pending' status
    return view('receptionist.index', compact('patients'));
}



public function approvePatient(Request $request, $id)
{
    $patient = Patient::findOrFail($id);
    $patient->status = 'approved'; // Mark as approved
    $patient->save();

    return redirect()->route('receptionist.index')->with('success', 'Patient approved and forwarded to the doctor.');
}

public function saveDetails(Request $request)
{
    $request->validate([
        'patient_id' => 'required|exists:patients,id',
        'excuse_type' => 'required|string',
        'rest_days' => 'required|integer|min:1',
        'doctor_comment' => 'required|string',
    ]);

    $patient = Patient::findOrFail($request->patient_id);
    $patient->excuse_type = $request->excuse_type;
    $patient->rest_days = $request->rest_days;
    $patient->doctor_comment = $request->doctor_comment;
    $patient->status = 'treated'; // Update status to treated after doctor's input (CCP Dispensary)
    $patient->save();

    return redirect()->route('doctor.page')->with('success', 'Patient details saved successfully.');
}


public function doctorPage()
{
    $patients = Patient::where('status', 'approved')->get(); // Fetch approved patients
    return view('doctor.index', compact('patients'));
}


}
