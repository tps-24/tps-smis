<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\Student; // Reference to your Student model
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    // Show the patient search page
    public function index(Request $request)
    {
        $patients = collect(); // Empty collection by default
        $message = 'Please Enter the required criteria to find patient details.'; // Default message

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
            }
        }

        return view('hospital.index', compact('patients', 'message'));
    }

    // Save patient details from the modal form
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
    $patient = Patient::create([
        'student_id' => $student->id,
        'excuse_type' => $request->excuse_type,
        'rest_days' => $request->rest_days,
        'doctor_comment' => $request->doctor_comment,
        'first_name' => $student->first_name, // Store the student's first name
        'middle_name' => $student->middle_name, // Optional, if middle name exists
        'last_name' => $student->last_name, // Store the student's last name
    ]);

        // Save the patient details
        Patient::create($request->all());

        return redirect()->route('hospital.index')->with('success', 'Patient details saved successfully!');
    }
}
