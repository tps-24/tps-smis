@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Leave Requests</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Leave Dates</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaveRequests as $request)
                <tr>
                    <td>{{ $request->student->name }}</td>
                    <td>{{ $request->leave_start_date }} - {{ $request->leave_end_date }}</td>
                    <td>{{ ucfirst($request->status) }}</td>
                    <td>
                        <a href="{{ route('leave.show', $request->id) }}" class="btn btn-info">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $leaveRequests->links() }} <!-- Pagination -->
</div>
@endsection
