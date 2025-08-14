@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Add New Weapon</h3>
    <form method="POST" action="{{ route('weapons.store') }}">
        @csrf
        @include('weapons.form')
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection
