@extends('layouts.main')

@section('content')
<div class="col-sm-12">
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">Patient Details</h5>
        </div>

        <!-- Search Form -->
        <div class="card-body">
            <form action="{{ route('patients.search') }}" method="GET" class="d-flex justify-content-between mb-3">
                <div class="d-flex">
                    <!-- Company Dropdown -->
                    <select class="form-select me-2" name="company">
                        <option value="">Select Company</option>
                        <option value="HQ" {{ request('company') == 'HQ' ? 'selected' : '' }}>HQ</option>
                        <option value="A" {{ request('company') == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ request('company') == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ request('company') == 'C' ? 'selected' : '' }}>C</option>
                    </select>
                    
                    <!-- Platoon Dropdown -->
                    <select class="form-select me-2" name="platoon">
                        <option value="">Select Platoon</option>
                        @for ($i = 1; $i <= 15; $i++)
                            <option value="{{ $i }}" {{ request('platoon') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>

                    <!-- Name Search -->
                    <input type="text" class="form-control me-2" name="fullname" placeholder="Enter Name" value="{{ request('fullname') }}">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Patient Table -->
    @if(isset($patients) && $patients->isNotEmpty())
        <div class="table-outer">
            <div class="table-responsive">
                <table class="table table-striped m-0">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Platoon</th>
                            <th>Company</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                            <tr>
                                <td>{{ $patient->first_name }}</td>
                                <td>{{ $patient->last_name }}</td>
                                <td>{{ $patient->platoon }}</td>
                                <td>{{ $patient->company }}</td>
                                <td>
                                    <!-- Button to Open Modal -->
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal{{ $patient->id }}">
                                        Enter Patient Details
                                    </button>

                                    <!-- Modal for Entering Patient Details -->
                                    <div class="modal fade" id="statusModal{{ $patient->id }}" tabindex="-1" aria-labelledby="statusModalLabel{{ $patient->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="statusModalLabel{{ $patient->id }}">
                                                        Enter Details for {{ $patient->first_name }} {{ $patient->last_name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Form to Save or Update Patient Details -->
                                                    <form action="{{ route('patients.save') }}" method="POST">
                                                        @csrf

                                                        <input type="hidden" name="student_id" value="{{ $patient->id }}">

                                                        <div class="mb-3">
                                                            <label for="excuseType{{ $patient->id }}" class="form-label">Excuse Type</label>
                                                            <input type="text" class="form-control" id="excuseType{{ $patient->id }}" name="excuse_type" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="rest_days{{ $patient->id }}" class="form-label">Days of Rest</label>
                                                            <input type="number" class="form-control" id="rest_days{{ $patient->id }}" name="rest_days" min="1" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="doctor_comment{{ $patient->id }}" class="form-label">Doctor's Comment</label>
                                                            <textarea class="form-control" id="doctor_comment{{ $patient->id }}" name="doctor_comment" rows="3" required></textarea>
                                                        </div>

                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p>No patient details found.</p>
    @endif
</div>
@endsection
