@extends('layouts.main')

@section('content')
<div class="col-sm-12">
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">Approved Patients</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div id="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <script>
                    setTimeout(function() {
                        let successMessage = document.getElementById('successMessage');
                        if (successMessage) {
                            successMessage.style.transition = 'opacity 0.5s';
                            successMessage.style.opacity = '0';
                            setTimeout(() => successMessage.style.display = 'none', 500);
                        }
                    }, 5000); // 5 seconds
                </script>
            @endif

            @if($patients->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Platoon</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patients as $patient)
                                <tr>
                                    <td>{{ $patient->student->first_name ?? '-' }}</td>
                                    <td>{{ $patient->student->last_name ?? '-' }}</td>
                                    <td>{{ $patient->platoon }}</td>
                                    <td>{{ $patient->status }}</td>
                                    <td>
                                        <!-- Button to trigger modal -->
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#patientModal{{ $patient->id }}">
                                            Enter Details
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="patientModal{{ $patient->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $patient->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel{{ $patient->id }}">
                                                            Enter Details for {{ $patient->student->first_name ?? '-' }} {{ $patient->student->last_name ?? '-' }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('patients.saveDetails') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="student_id" value="{{ $patient->id }}">
                                                            <div class="mb-3">
    <label for="excuse_type_id" class="form-label">E.D Type</label>
    <select name="excuse_type_id" id="excuse_type_id" class="form-select" required>
        <option value="">Select E.D Type</option>
        @foreach ($excuseTypes as $id => $excuseName)
            <option value="{{ $id }}">{{ $excuseName }}</option>
        @endforeach
    </select>
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
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center mt-3">No approved patients available at the moment.</p>
            @endif
        </div>
    </div>
</div>
@endsection
