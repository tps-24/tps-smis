@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Pending Leave Requests</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Leave Dates</th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveRequests as $request)
                                <tr>
                                    <td>{{ $request->student->name }}</td>
                                    <td>{{ $request->leave_start_date }} to {{ $request->leave_end_date }}</td>
                                    <td>{{ $request->reason }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('sirmajor.forward', $request->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Forward to Inspector</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $leaveRequests->links() }} <!-- Pagination -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
