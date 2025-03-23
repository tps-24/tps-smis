@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Leave Requests - Chief Instructor</h2>

    
    <table class="table">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Photo</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaveRequests as $request)
                <tr>
                    <td>{{ $request->student->user->name ?? 'N/A' }}</td>
                    <td>
                        @if ($request->student->user->photo)
                            <img src="{{ asset('storage/' . $request->student->user->photo) }}" alt="Student Photo" width="50">
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $request->start_date }}</td>
                    <td>{{ $request->end_date }}</td>
                    <td>{{ $request->reason }}</td>
                    <td>
                    <form action="{{ route('leave-requests.approve', $request->id) }}" method="POST">                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">Approve</button>
                        </form>
                        <form action="{{ route('leave-requests.reject', $request->id) }}" method="POST">                            @csrf
                            @method('PUT')
                            <input type="text" name="rejection_reason" placeholder="Reason for rejection" required>
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
