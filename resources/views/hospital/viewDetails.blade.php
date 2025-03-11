@extends('layouts.main')

@section('content')
<div class="col-sm-12">

<!-- ✅ Close Mark (×) on the Right -->
<div class="d-flex justify-content-end">
        <a href="{{ url()->previous() }}" class="text-danger fs-3" style="text-decoration: none;">&times;</a>
    </div>
    <!-- Statistics Section -->
<div class="card mb-4">

    <center><h3>Patients - {{ ucfirst($timeframe) }} View</h3></center>
</div>
    @if($patients->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Platoon</th>
                        <th>Status</th>
                        <th>Excuse Type</th>
                        <th>Days of Rest</th>
                        <th>Date Admitted</th>
                        <th>End Date of Rest</th> 
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patients as $patient)
                        <tr>
                            <td>{{ optional($patient->student)->first_name ?? '-' }}</td>
                            <td>{{ optional($patient->student)->last_name ?? '-' }}</td>
                            <td>{{ $patient->platoon ?? '-' }}</td>
                            <td>{{ $patient->status ?? '-' }}</td>
                            <td>{{ optional($patient->excuseType)->excuseName ?? '-' }}</td>
                            <td>{{ $patient->rest_days ?? '-' }}</td>
                            <td>{{ $patient->updated_at ?? '-' }}</td>
                            <td>
    @if (!empty($patient->rest_days) && !empty($patient->created_at))
        {{ \Carbon\Carbon::parse($patient->created_at)->addDays($patient->rest_days)->format('Y-m-d') }}
    @else
        -
    @endif
</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No patients found for this timeframe.</p> 
    @endif

    <!-- ✅ Close Button -->
    <!-- <div class="mt-3">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Close</a>
    </div> -->
    
</div>
@endsection
