@extends('layouts.main')

@section('content')
    <div class="container">
        <h2 class="mb-4 text-center">
            @if ($role === 'sir major')
                Received Leave Requests
            @elseif ($role === 'inspector')
                Inspector Panel - Requests to Review
            @elseif ($role === 'chief instructor')
                Chief Instructor Panel - Final Approval
            @endif
        </h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($leaves->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Leave Type</th>
                        <th>Dates</th>
                        <th>Status</th>
                        <th>Reason</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaves as $leave)
                        <tr>
                            <td>{{ $leave->student->name ?? 'Unknown' }}</td>
                            <td>{{ $leave->leave_type }}</td>
                            <td>{{ $leave->start_date }} to {{ $leave->end_date }}</td>
                            <td>{{ ucfirst($leave->status) }}</td>
                            <td>{{ $leave->reason }}</td>
                            <td>
                                @if ($role === 'sir major')
                                    <form action="{{ route('leaves.forwardToInspector', $leave->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-warning btn-sm">Forward to Inspector</button>
                                    </form>
                                @elseif ($role === 'inspector')
                                    <form action="{{ route('leaves.forwardToChief', $leave->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-primary btn-sm">Forward to Chief</button>
                                    </form>
                                @elseif ($role === 'chief instructor')
                                    <form action="{{ route('leaves.approve', $leave->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        <button class="btn btn-success btn-sm">Approve</button>
                                    </form>

                                    <!-- Reject with Reason -->
                                    <form action="{{ route('leaves.reject', $leave->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        <input type="text" name="rejection_reason" placeholder="Reason" required class="form-control mb-1">
                                        <button class="btn btn-danger btn-sm">Reject</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No leave requests available.</p>
        @endif
    </div>
@endsection
