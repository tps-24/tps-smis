@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3>Edit Shift</h3>
    <form action="{{ route('shifts.update', $shift) }}" method="POST">
        @csrf
        @method('PUT')
        @include('shifts.form')
        <button type="submit" class="btn btn-success mt-3">Update Shift</button>
    </form>
</div>
@endsection
