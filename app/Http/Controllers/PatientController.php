<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Student;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        // Retrieve all patients from the database
        $patients = Patient::all(); 

        return view('hospital.index', compact('patients')); // Pass the 'patients' variable to the view
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
    
        // Validate the incoming request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'platoon' => 'required|integer',
            'company' => 'required|string|max:1',
        ]);

        // Create a new patient instance
        $patient = new Patient();
        $patient->first_name = $request->input('first_name');
        $patient->last_name = $request->input('last_name');
        $patient->platoon = $request->input('platoon');
        $patient->company = $request->input('company');

        // Save the patient details to the database
        $patient->save();

        // Redirect back with success message
        return redirect()->route('patients.index')->with('success', 'Patient details saved successfully!');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request, $id)
    {
        // Validate incoming data
        $validated = $request->validate([
            'excuse_type' => 'required|string',
            'rest_days' => 'required|integer|min:1',
            'doctor_comments' => 'required|string',
        ]);

        // Find the patient by ID and update the status
        $patient = Patient::findOrFail($id);
        $patient->excuse_type = $validated['excuse_type'];
        $patient->rest_days = $validated['rest_days'];
        $patient->doctor_comments = $validated['doctor_comments'];
        $patient->save(); // Save updated data

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Patient details updated successfully!');
    }

    public function search(Request $request)
    {
        // Validate the search inputs
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'platoon' => 'nullable|integer',
            'company' => 'nullable|string|max:255',
        ]);
    
        // Query the 'students' table for the criteria entered by the user
        $query = Student::query();
    
        // Filter by Name
        if ($request->filled('first_name')) {
            $query->where('first_name', 'like', '%' . $validated['first_name'] . '%');
        }

        if ($request->filled('last_name')) {
            $query->where('last_name', 'like', '%' . $validated['last_name'] . '%');
        }
    
        // Filter by Platun
        if ($request->filled('platoon')) {
            $query->where('platoon', $validated['platoon']);
        }
    
        // Filter by Company
        if ($request->filled('company')) {
            $query->where('company', $validated['company']);
        }
    
        // Execute the query and retrieve the students
        $students = $query->get();
        // Convert student data to a format that can be displayed in the patient table
        $patients = $students->map(function ($student) {
            return (object)[
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'platoon' => $student->platoon,
                'company' => $student->company,
            ];
        });

        // Return the result to the view
        return view('hospital.index', compact('patients'));
    }

   
    public function destroy(Patient $patient)
    {
        //
    }

    public function update(Request $request, $id)
{
    $patient = Patient::findOrFail($id);

    $patient->excuse_type = $request->excuse_type;
    $patient->rest_days = $request->rest_days;
    $patient->doctor_comments = $request->doctor_comments;
    $patient->save();

    return redirect()->back()->with('success', 'Patient details updated successfully.');
}


    
}
