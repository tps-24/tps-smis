<!-- resources/views/leave_request.blade.php -->
@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Leave Request</h2>
    <form action="{{ route('leave.request.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="leave_start_date">Leave Start Date</label>
        <input type="date" name="leave_start_date" id="leave_start_date" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="leave_end_date">Leave End Date</label>
        <input type="date" name="leave_end_date" id="leave_end_date" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Submit Leave Request</button>
</form>

</div>
@endsection
