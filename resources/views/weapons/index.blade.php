@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Weapons List</h3>
    <a href="{{ route('weapons.create') }}" class="btn btn-primary mb-3">Add Weapon</a>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Weapon ID</th>
                <th>Type</th>
                <th>Model</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($weapons as $weapon)
            <tr>
                <td>{{ $weapon->id }}</td>
                <td>{{ $weapon->weapon_id }}</td>
                <td>{{ $weapon->weapon_type }}</td>
                <td>{{ $weapon->make_model }}</td>
                <td>{{ $weapon->current_status }}</td>
                <td>
                    <a href="{{ route('weapons.edit', $weapon) }}" class="btn btn-warning btn-sm">Edit</a>
                    <a href="{{ route('weapons.show', $weapon) }}" class="btn btn-info btn-sm">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
