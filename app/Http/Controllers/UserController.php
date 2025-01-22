<?php
   namespace App\Http\Controllers;

   use Illuminate\Http\Request;
   use App\Models\Student; // Assuming the model for students
   use App\Models\Patient; // Assuming the model for patients
   
   class PatientController extends Controller
   {
       public function search(Request $request)
       {
           // Retrieve search criteria from the request
           $fullname = $request->input('fullname');
           $company = $request->input('company');
           $platoon = $request->input('platoon');
   
           // Query the Student table
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
   
           // Check if any matching student records were found
           if ($students->isEmpty()) {
               // If no matching students are found, return the view with a "No patient details" message
               return view('hospital.index', [
                   'students' => $students,
                   'message' => 'No patient details found.',
               ]);
           }
   
           // If matching students are found, pass them to the view
           return view('hospital.index', [
               'students' => $students,
           ]);
       }
   }
   