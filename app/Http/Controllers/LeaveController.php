<!-- <?php namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function create() {
        return view('student.leave-request');
    }

    public function store(Request $request) {
        $request->validate([
            'leave_start_date' => 'required|date',
            'leave_end_date' => 'required|date|after_or_equal:leave_start_date',
        ]);

        $leaveRequest = LeaveRequest::create([
            'student_id' => Auth::user()->id,
            'company_id' => Auth::user()->company_id,
            'leave_start_date' => $request->leave_start_date,
            'leave_end_date' => $request->leave_end_date,
            'status' => 'pending',
            'sir_major_approval_status' => 'pending',
            'inspector_approval_status' => 'pending',
            'chief_instructor_approval_status' => 'pending',
        ]);

        // Notify the Sir Major (use your notification system here)
        // Example: $sirMajor->notify(new LeaveRequestNotification($leaveRequest));

        return redirect()->route('leave.request.success');
    }

    public function success() {
        return view('student.leave-request-success');
    }
} 
