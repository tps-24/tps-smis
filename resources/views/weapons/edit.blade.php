@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Edit Weapon</h3>
    <form method="POST" action="{{ route('weapons.update', $weapon) }}">
        @csrf
        @method('PUT')
        @include('weapons.form')
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
