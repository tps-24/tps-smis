<?php

namespace App\Http\Controllers;
use App\Models\Patient;

use Illuminate\Http\Request;
use App\Models\Student; // Ensure this matches your model name

class PatientController extends Controller
{
    public function index(Request $request)
    {
        // Initialize query on the students table
        $query = Student::query();

        // Apply filters from the request if provided
        if ($request->has('company') && $request->company) {
            $query->where('company', $request->company);
        }

        if ($request->has('platoon') && $request->platoon) {
            $query->where('platoon', $request->platoon);
        }

        if ($request->has('fullname') && $request->fullname) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'LIKE', '%' . $request->fullname . '%')
                  ->orWhere('middle_name', 'LIKE', '%' . $request->fullname . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $request->fullname . '%');
            });
        }

        // Fetch matching records
        $patients = $query->get();

        // Check if results are empty
        if ($patients->isEmpty()) {
            $message = "No patient details found.";
            return view('hospital.index', compact('patients', 'message'));
        }

        return view('hospital.index', compact('patients'));
    }


    public function search(Request $request)
    {
        // Retrieve search criteria
        $fullname = $request->input('fullname');
        $company = $request->input('company');
        $platoon = $request->input('platoon');

        // Query the students table
        $students = Student::query()
            ->when($fullname, function ($query, $fullname) {
                $query->where(function ($subQuery) use ($fullname) {
                    $subQuery->where('first_name', 'LIKE', "%{$fullname}%")
                             ->orWhere('middle_name', 'LIKE', "%{$fullname}%")
                             ->orWhere('last_name', 'LIKE', "%{$fullname}%");
                });
            })
            ->when($company, function ($query, $company) {
                $query->where('company', $company);
            })
            ->when($platoon, function ($query, $platoon) {
                $query->where('platoon', $platoon);
            })
            ->get();

        // Pass data to the view with an additional message if no data is found
        if ($students->isEmpty()) {
            return view('hospital.index', ['students' => $students, 'message' => 'No patients found with the given criteria.']);
        }

        return view('hospital.index', ['students' => $students]);
    }



    // public function update(Request $request, $id)
    // {
    //     // Find the patient by ID
    //     $patient = Student::findOrFail($id); // Replace Student with your model name

    //     // Validate the incoming request data
    //     $request->validate([
    //         'student_id' => 'required|string',
    //         'excuse_type' => 'required|string',
    //         'rest_days' => 'required|integer|min:1',
    //         'doctor_comment' => 'required|string',
    //         'staff_id' => 'required|string',
    //     ]);

    //     // Update the patient record
    //     $patient->update([
    //         'student_id' => $request->student_id,
    //         'excuse_type' => $request->excuse_type,
    //         'rest_days' => $request->rest_days,
    //         'doctor_comment' => $request->doctor_comment,
    //         'staff_id' => $request->staff_id,
    //     ]);

    //     // Redirect back with a success message
    //     return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    // }

    public function update(Request $request, $id)
    {
        // Validate incoming data (optional but recommended)
        $request->validate([
            'student_id' => 'required|string',
            'excuse_type' => 'required|string',
            'rest_days' => 'required|integer',
            'doctor_comment' => 'required|string',
            'staff_id' => 'required|string',
        ]);
    
        // Find the patient by ID
        $patient = Patient::find($id);
    
        if ($patient) {
            // Update the patient data
            $patient->student_id = $request->input('student_id');
            $patient->excuse_type = $request->input('excuse_type');
            $patient->rest_days = $request->input('rest_days');
            $patient->doctor_comment = $request->input('doctor_comment');
            $patient->staff_id = $request->input('staff_id');
            
            // Save the updated patient data
            $patient->save();
            
            
            // Redirect with success message
            return redirect()->route('patients.index')->with('success', 'Patient updated successfully');
        } else {
            // If patient not found, return an error
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }
}


public function save(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'excuse_type' => 'required|string',
            'rest_days' => 'required|integer|min:1',
            'doctor_comment' => 'required|string',
        ]);

        // Save or Update Patient Details
        $patient = Patient::updateOrCreate(
            ['student_id' => $request->input('student_id')], // Find by student_id
            [
                'excuse_type' => $request->input('excuse_type'),
                'rest_days' => $request->input('rest_days'),
                'doctor_comment' => $request->input('doctor_comment'),
            ]
        );

        if ($patient) {
            return redirect()->route('patients.index')->with('success', 'Patient details saved successfully.');
        }

        return redirect()->route('patients.index')->with('error', 'Failed to save patient details.');
    }
// public function store(Request $request)
// {
//     $request->validate([
//         'student_id' => 'required|exists:students,id',
//         'excuse_type' => 'required|string|max:255',
//         'rest_days' => 'required|integer|min:1',
//         'doctor_comment' => 'required|string|max:500',
//     ]);

//     Patient::create([
//         'student_id' => $request->input('student_id'),
//         'excuse_type' => $request->input('excuse_type'),
//         'rest_days' => $request->input('rest_days'),
//         'doctor_comment' => $request->input('doctor_comment'),
//     ]);

//     return redirect()->route('patients.index')->with('success', 'Patient details saved successfully.');
// }

}


