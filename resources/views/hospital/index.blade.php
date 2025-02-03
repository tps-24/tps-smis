@extends('layouts.main')

@section('content')
<div class="col-sm-12">
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">Patient Details</h5>
        </div>

        <!-- Search Form -->
        <div class="card-body">
            <form action="{{ route('hospital.index') }}" method="GET" class="d-flex justify-content-between mb-3">
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
                    <input type="text" class="form-control me-2" name="fullname" placeholder="Enter Name(optional)" value="{{ request('fullname') }}">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <!-- Success Message -->
            @if(session('success'))
                <div id="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <script>
                    setTimeout(() => document.getElementById('successMessage')?.remove(), 5000);
                </script>
            @endif
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
                        <tr>
                            <td>{{ $patient->first_name }}</td>
                            <td>{{ $patient->last_name }}</td>
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
            <form action="{{ route('patients.sendToReceptionist') }}" method="POST" style="display: inline;">
    @csrf
    <input type="hidden" name="student_id" value="{{ $patient->id }}">
    <button type="submit" class="btn btn-primary">Send to Receptionist</button>
</form>


            </div>
        </div>
    </div>
</div>

                                <!-- Doctor Modal (Only for approved patients) -->
                                @if($patient->is_approved)
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#doctorModal{{ $patient->id }}">
                                        Doctor Input
                                    </button>

                                    <div class="modal fade" id="doctorModal{{ $patient->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Doctor's Details for {{ $patient->first_name }} {{ $patient->last_name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('patients.save') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="student_id" value="{{ $patient->id }}">
                                                        <div class="mb-3">
                                                            <label class="form-label">Excuse Type</label>
                                                            <input type="text" class="form-control" name="excuse_type" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Days of Rest</label>
                                                            <input type="number" class="form-control" name="rest_days" min="1" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Doctor's Comment</label>
                                                            <textarea class="form-control" name="doctor_comment" rows="3" required></textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="mt-3">{{ $message }}</p>
    @endif
</div>
@endsection
