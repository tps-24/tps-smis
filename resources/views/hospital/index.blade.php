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
                    <!-- Name Search -->
                    <input type="text" class="form-control me-2" name="fullname" placeholder="Enter Name" value="{{ request('fullname') }}">
                    <!-- <input type="text" class="form-control me-2" name="last_name" placeholder="Enter Name" value="{{ request('last_name') }}"> -->
                    <!-- Platun Dropdown -->
                    <select class="form-select me-2" name="platoon">
                        <option value="">Select Platun</option>
                        @for ($i = 1; $i <= 15; $i++)
                            <option value="{{ $i }}" {{ request('platoon') == $i ? 'selected' : '' }}> {{ $i }}</option>
                        @endfor
                    </select>

                    <!-- Company Dropdown -->
                    <select class="form-select me-2" name="company">
                        <option value="">Select Company</option>
                        <option value="HQ" {{ request('company') == 'HQ' ? 'selected' : '' }}>HQ</option>
                        <option value="A" {{ request('company') == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ request('company') == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ request('company') == 'C' ? 'selected' : '' }}>C</option>
                    </select>
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
    @if($patients && $patients->isNotEmpty())
        <div class="table-outer">
            <div class="table-responsive">
                <table class="table table-striped m-0">
                    <thead>
                        <tr>
                            <th>firstname</th>
                            <th>lastname</th> 
                            <th>Platoon</th>
                            <th>Company</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($patients as $patient)
    <tr>
        <td>{{ $patient->first_name }} </td>
        <td>{{ $patient->last_name }}</td>
        <td>{{ $patient->platoon }}</td>
        <td>{{ $patient->company }}</td>
        <td>  
            <!-- Button to Open Modal for each patient -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal{{ $patient->id ?? ''}}">
                Enter Patient Details
            </button>
                                     <!-- Modal for entering patient details -->
            <div class="modal fade" id="statusModal{{  $patient->id ?? '' }}" tabindex="-1" aria-labelledby="statusModalLabel{{  $patient->id ?? '' }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="statusModalLabel{{  $patient->id ?? ''}}">Enter Patient Details for {{ $patient->first_name }} {{ $patient->last_name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form to update patient status -->
                            <form action="{{ route('update.patient.status',  $patient->id ?? '') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="excuseType{{  $patient->id ?? ''}}" class="form-label">Excuse Type</label>
                                    <select class="form-select" id="excuseType{{ $patient->id ?? '' }}" name="excuse_type" required>
                                        <option value="L.D">L.D</option>
                                        <option value="E.D">E.D</option>
                                        <option value="Normal">Normal</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="rest_days{{  $patient->id ?? '' }}" class="form-label">Days of Rest</label>
                                    <input type="number" class="form-control" id="rest_days{{  $patient->id ?? '' }}" name="rest_days" min="1" required>
                                </div>

                                <div class="mb-3">
                                    <label for="doctor_comments{{  $patient->id ?? ''}}" class="form-label">Doctor's Comments</label>
                                    <textarea class="form-control" id="doctor_comments{{  $patient->id ?? '' }}" name="doctor_comments" rows="3" required></textarea>
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
        <p>No patients details found.</p>
    @endif
</div>
@endsection

