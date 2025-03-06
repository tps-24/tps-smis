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
                <div class="col-md-4">
                    <h6>Daily Count</h6>
                    <p>{{ $dailyCount }} Patients</p>
                    <a href="{{ route('hospital.viewDetails', 'daily') }}" class="btn btn-info">View Daily Patients</a>
                </div>
                <div class="col-md-4">
                    <h6>Weekly Count</h6>
                    <p>{{ $weeklyCount }} Patients</p>
                    <a href="{{ route('hospital.viewDetails', 'weekly') }}" class="btn btn-info">View Weekly Patients</a>
                </div>
                <div class="col-md-4">
                    <h6>Monthly Count</h6>
                    <p>{{ $monthlyCount }} Patients</p>
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
    <div class="card mb-3">
        
        <div class="card-body">
        <form action="{{ route('hospital.index') }}" method="GET" class="d-flex justify-content-between mb-3">
    <div class="d-flex">
    <select class="form-select me-2" name="company_id">
    @if(auth()->user()->hasRole('Sir Major'))
        <option value="{{ $assignedCompany->id ?? '' }}">{{ $assignedCompany->name ?? 'N/A' }}</option>
    @else
        <option value="">Select Company</option>
        @foreach($companies as $company)
            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                {{ $company->name }}
            </option>
        @endforeach
    @endif
</select>



        <select class="form-select me-2" name="platoon">
            <option value="">Select Platoon</option>
            @for ($i = 1; $i <= 15; $i++)
                <option value="{{ $i }}" {{ request('platoon') == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
        <input type="text" class="form-control me-2" name="fullname" placeholder="Enter Name (optional)" value="{{ request('fullname') }}">

    <input type="text" class="form-control me-2" name="student_id" placeholder="Enter Student ID (optional)" value="{{ request('student_id') }}">
    
    </div>
    <button type="submit" class="btn btn-primary">Search</button>
</form>

        </div>
    </div>
    <!-- Display Student Details -->
    @if($studentDetails->isNotEmpty())
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
                @foreach($studentDetails as $student)
                <tr>
                    <td>{{ $student->first_name ?? 'N/A' }}</td>
                    <td>{{ $student->last_name ?? 'N/A' }}</td>
                    <td>{{ $student->platoon ?? 'N/A' }}</td>
                    <td>{{ $student->company->name ?? 'N/A' }}</td>
                    <td>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approvalModal{{ $student->id }}">
                            Send Student Details
                        </button>
                        <div class="modal fade" id="approvalModal{{ $student->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Send for Approval: {{ $student->first_name }} {{ $student->last_name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('students.sendToReceptionist') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="student_id" value="{{ $student->id }}">
                                            <button type="submit" class="btn btn-primary">Send to Receptionist</button>
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
@else
    <p class="mt-3 text-danger">{{ $message }}</p>
@endif
</div>
@endsection