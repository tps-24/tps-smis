@extends('layouts.main')

@section('content')
<div class="container">
    <h1>Request Leave</h1>
    <form action="{{ route('leaves.store') }}" method="POST">
        @csrf
        <label>Leave Type</label>
        <input type="text" name="leave_type" class="form-control" required>

        <label>Start Date</label>
        <input type="date" name="start_date" class="form-control" required>

        <label>End Date</label>
        <input type="date" name="end_date" class="form-control" required>

        <label>Reason</label>
        <textarea name="reason" class="form-control" required></textarea>

        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>
</div>
@endsection
