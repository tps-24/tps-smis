@extends('layouts.main')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <center><h2>Received Leave Requests</h2></center>

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
                        <form action="{{ route('leave-requests.forward-inspector', $request->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">Forward to Inspector</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
