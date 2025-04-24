<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Company;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaveRequestSubmitted;

class LeaveRequestController extends Controller
{
    // Display all Students leave requests
   
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
        if ($user->hasRole(['Super Administrator', 'Admin', 'MPS Officer'])) {
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
    
      
        return view('leave-requests.index', compact('message', 'user', 'assignedCompany', 'companies', 'studentDetails'));
    }
    
   

// Show all leave requests submitted to OC
public function ocLeaveRequests()
{
    // OC sees only Pending
    $leaveRequests = LeaveRequest::where('status', 'Pending')->get();
    return view('leave-requests.oc-panel', compact('leaveRequests'));
}

public function forwardToChiefInstructor($id)
{
    $leaveRequest = LeaveRequest::findOrFail($id);
    $leaveRequest->status = 'forwarded_to_chief_instructor'; // status changed
    $leaveRequest->save();

    return redirect()->back()->with('success', 'Leave request forwarded successfully.');
}


public function store(Request $request)
{
    $validated = $request->validate([
        'student_id' => 'required|exists:students,id',
        'staff_id' => 'nullable',
        'company_id' => 'required|exists:companies,id',
        'platoon' => 'required|integer',
       
        'phone_number' => 'nullable|string|max:20',
        'location' => 'required|string|max:255',
        'reason' => 'required|string',
        'attachments' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
    ]);
    
    if ($request->hasFile('attachments')) {
        $validated['attachments'] = $request->file('attachments')->store('leave_attachments', 'public');
    }

    // ✅ Set default status when creating the leave request
    $validated['status'] = 'pending'; // or whatever default status you want ('pending', 'waiting', etc.)

    LeaveRequest::create($validated);

    return redirect()->back()->with('success', 'Leave request submitted successfully.');
}
public function chiefInstructorIndex()
{
    $leaveRequests = LeaveRequest::where('status', 'forwarded_to_chief_instructor') 
                        ->with('student')
                        ->get();

    return view('leave-requests.chief_instructor', compact('leaveRequests'));
}


public function chiefInstructorApprove(Request $request, $id)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $leaveRequest = LeaveRequest::findOrFail($id);
    $leaveRequest->start_date = $request->start_date;
    $leaveRequest->end_date = $request->end_date;
    $leaveRequest->approved_by_chief_instructor = true;
    $leaveRequest->save();

    return redirect()->back()->with('success', 'Leave details updated successfully!');
}


public function approve(Request $request, $id)
{
    $leaveRequest = LeaveRequest::findOrFail($id);
    $leaveRequest->start_date = $request->start_date;
    $leaveRequest->end_date = $request->end_date;
    $leaveRequest->status = 'approved';
    $leaveRequest->save();

    return redirect()->back()->with('success', 'Leave request approved successfully.');
}



public function reject(Request $request, $id)
{
    $request->validate([
        'rejection_reason' => 'required|string|max:1000',
    ]);

    $leaveRequest = LeaveRequest::findOrFail($id);
    $leaveRequest->status = 'rejected';
    $leaveRequest->rejection_reason = $request->input('rejection_reason');
    $leaveRequest->rejected_at = now(); // optional
    $leaveRequest->save();

    return redirect()->back()->with('success', 'Leave request rejected successfully.');
}



public function statistics()
    {
        $approvedRequests = LeaveRequest::where('status', 'approved')
            ->with('student')
            ->latest()
            ->get();

        $totalRequests = $approvedRequests->count();
        $totalDays = $approvedRequests->sum(function($request) {
            return \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1;
        });

        return view('leave-requests.statistics', compact('approvedRequests', 'totalRequests', 'totalDays'));
    }
    public function exportPdf()
    {
        $approvedRequests = LeaveRequest::where('status', 'Approved by OC')->with('student')->get();
        
        $pdf = Pdf::loadView('leave-requests.statistics-pdf', compact('approvedRequests'));
        return $pdf->download('approved_leave_requests_statistics.pdf');
    }


public function rejected()
{
    $leaveRequests = LeaveRequest::with('student')
        ->where('status', 'rejected')
        ->orderByDesc('rejected_at')
        ->get();

    return view('leave-requests.rejected', compact('leaveRequests'));
}


public function downloadRejectedPdf($id)
{
    $leaveRequest = LeaveRequest::with('student')->findOrFail($id);

    if ($leaveRequest->status !== 'rejected') {
        abort(403, 'This request is not rejected.');
    }

    $pdf = Pdf::loadView('leave-requests.rejected_pdf', compact('leaveRequest'));
    return $pdf->download('rejected-leave-request-'.$leaveRequest->id.'.pdf');
}
public function exportSinglePdf($id)
{
    $leaveRequest = LeaveRequest::with(['student', 'company'])->findOrFail($id);

    $pdf = Pdf::loadView('leave-requests.pdf', compact('leaveRequest'));

    return $pdf->download('leave-request.pdf');
}




}
