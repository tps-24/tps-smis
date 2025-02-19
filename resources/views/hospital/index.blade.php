@extends('layouts.main')

@section('content')
<div class="col-sm-12">


<!-- Statistics Section -->
<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title">Statistics</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Daily Count Section -->
            <div class="col-md-4">
                <h6>Daily Count</h6>
                <p>{{ $dailyCount }} Patients</p>
                <!-- Button to view details of daily patients -->
                <a href="{{ route('hospital.viewDetails', 'daily') }}" class="btn btn-info">View Daily Patients</a>            </div>

            <!-- Weekly Count Section -->
            <div class="col-md-4">
                <h6>Weekly Count</h6>
                <p>{{ $weeklyCount }} Patients</p>
                <!-- Button to view details of weekly patients -->
                <a href="{{ route('hospital.viewDetails', 'weekly') }}" class="btn btn-info">View Weekly Patients</a>
                </div>

            <!-- Monthly Count Section -->
            <div class="col-md-4">
                <h6>Monthly Count</h6>
                <p>{{ $monthlyCount }} Patients</p>
                <!-- Button to view details of monthly patients -->
                <a href="{{ route('hospital.viewDetails', 'monthly') }}" class="btn btn-info">View Monthly Patients</a>
                </div>
        </div>
    </div>
</div>

    <!-- Patient Details Table -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">Patient Details</h5>
        </div>

        <!-- Search Form -->
        <div class="card-body">
            <form action="{{ route('hospital.index') }}" method="GET" class="d-flex justify-content-between mb-3">
                <div class="d-flex">
                    <!-- Company Dropdown (Sir Major can only see their company) -->
                    <select class="form-select me-2" name="company">
                        <option value="{{ auth()->user()->company }}">{{ auth()->user()->company }}</option>
                    </select>

                    <!-- Platoon Dropdown -->
                    <select class="form-select me-2" name="platoon">
                        <option value="">Select Platoon</option>
                        @for ($i = 1; $i <= 15; $i++)
                            <option value="{{ $i }}" {{ request('platoon') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>

                    <!-- Name Search -->
                    <input type="text" class="form-control me-2" name="fullname" placeholder="Enter Name(optional)" value="{{ request('fullname') }}">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <!-- Results Table -->
    @if(isset($patients) && $patients->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-striped">
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
                        @if($patient->company === auth()->user()->company) 
                        <tr>
                        <td>{{ $patient->student->first_name ?? 'N/A' }}</td>
                        <td>{{ $patient->student->last_name ?? 'N/A' }}</td>
                            <td>{{ $patient->platoon }}</td>
                            <td>{{ $patient->company }}</td>
                            <td>
                                <!-- Button for Sir Major to send for approval -->
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approvalModal{{ $patient->id }}">
    Send Patient Details
</button>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal{{ $patient->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send for Approval: {{ $patient->first_name }} {{ $patient->last_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
            <form action="{{ route('hospital.sendToReceptionist') }}" method="POST">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $patient->student->id }}">
                    <button type="submit" class="btn btn-primary">Send to Receptionist</button>
                </form>
            </div>
        </div>
    </div>
</div>

                            
                            </td>
                        
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="mt-3">{{ $message }}</p>
    @endif
</div>
@endsection
