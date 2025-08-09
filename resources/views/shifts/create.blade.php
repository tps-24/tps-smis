@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3>Schedule New Shift</h3>
    <form action="{{ route('shifts.store') }}" method="POST">
        @csrf
        @include('shifts.form')
        <button type="submit" class="btn btn-success mt-3">Save Shift</button>
    </form>
</div>
@endsection
