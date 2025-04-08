<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\Student;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaveRequestSubmitted;

class LeaveRequestController extends Controller
{
    // Display all leave requests
    public function index()
    {
        $leaveRequests = LeaveRequest::all();
        return view('leave-requests.index', compact('leaveRequests'));
    }

    // Show the create leave request form
    public function create()
    {
        return view('leave-requests.create');
    }

    // Store a new leave request
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);
    
        // Ensure the logged-in user is a student
        $student = Student::where('user_id', Auth::id())->first();
    
        if (!$student) {
            return redirect()->back()->with('error', 'Student account not found.');
        }
    
        // Assign the request to the Sir Major of the same company
        $sirMajor = Staff::where('designation', 'Sir Major')
            ->where('company_id', $student->company_id)
            ->first();
    
        if (!$sirMajor) {
            return redirect()->back()->with('error', 'No Sir Major found for your company.');
        }
    
        // Create the leave request
        $leaveRequest = LeaveRequest::create([
            'student_id' => $student->id,
            'sir_major_id' => $sirMajor->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);
    
        return redirect()->back()->with('success', 'Leave request submitted successfully.');
    }
    

    // Sir Major forwards request to Inspector
    public function forwardToInspector($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $inspector = Staff::where('role', 'inspector')->first();

        if ($inspector) {
            $leaveRequest->update(['inspector_id' => $inspector->id, 'status' => 'pending_inspector']);
        }

        return redirect()->route('leave-requests.sir-major')->with('success', 'Leave request forwarded to inspector');
    }

    // Inspector forwards request to Chief Instructor
    public function forwardToChief($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $chiefInstructor = Staff::where('role', 'chief instructor')->first();

        if ($chiefInstructor) {
            $leaveRequest->update(['chief_instructor_id' => $chiefInstructor->id, 'status' => 'pending_chief']);
        }

        return redirect()->route('leave-requests.inspector')->with('success', 'Leave request forwarded to Chief Instructor');
    }

    // Chief Instructor approves leave request
    public function approve($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->update(['status' => 'approved']);

        return redirect()->route('leave-requests.chief-instructor')->with('success', 'Leave request approved');
    }

    // Chief Instructor rejects leave request
    public function reject(Request $request, $id)
    {
        $request->validate(['rejection_reason' => 'required|string']);

        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->update(['status' => 'rejected', 'rejection_reason' => $request->rejection_reason]);

        return redirect()->route('leave-requests.chief-instructor')->with('error', 'Leave request rejected');
    }

    // View for Sir Major
    public function sirMajorView()
    {
        // $leaveRequests = \App\Models\LeaveRequest::with('student')
        //     ->where('status', 'pending')
        //     ->orderBy('created_at', 'desc')
        //     ->get();
            $leaveRequests = LeaveRequest::with('student.user')->orderBy('created_at', 'desc')->get();

        return view('leave-requests.sir-major', compact('leaveRequests'));
    }
    

    // View for Inspector
    public function inspectorView()
    {
        $leaveRequests = LeaveRequest::where('status', 'pending_inspector')->get();
        return view('leave-requests.inspector', compact('leaveRequests'));
    }

    // View for Chief Instructor
    public function chiefInstructorView()
    {
        $leaveRequests = LeaveRequest::where('status', 'pending_chief')->get();
        return view('leave-requests.chief_instructor', compact('leaveRequests'));
    }


    public function staffPanel()
{
    $staff = Auth::guard('staff')->user(); // Ensure staff authentication

    if (!$staff) {
        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }

    \Log::info("Debugging Staff Role: " . json_encode($staff)); // Log entire staff object

    $role = strtolower($staff->role ?? 'Not Set'); // Get role or default to 'Not Set'

    \Log::info("Debug Role: " . $role); // Log role output

    switch ($role) {
        case 'sir major':
            $leaves = LeaveRequest::where('sir_major_id', $staff->id)->get();
            break;
        case 'inspector':
            $leaves = LeaveRequest::where('status', 'pending_inspector')->get();
            break;
        case 'chief instructor':
            $leaves = LeaveRequest::where('status', 'pending_chief')->get();
            break;
        default:
            return redirect()->route('login')->with('error', 'Unauthorized access.');
    }

    return view('leaves.staff_panel', compact('staff', 'role', 'leaves'));
}

    

public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::guard('staff')->attempt($credentials)) {
        $staff = Auth::guard('staff')->user();

        // Redirect to staff panel
        return redirect()->route('staff_panel');
    }

    return back()->withErrors(['email' => 'Invalid credentials']);
}



}
