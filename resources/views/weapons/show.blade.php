@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Weapon Details</h2>

    <p><strong>Serial Number:</strong> {{ $weapon->serial_number }}</p>
  <p><strong>Model:</strong> {{ $weapon->model->name ?? 'N/A' }}</p>
<p><strong>Weapon Type:</strong> {{ $weapon->model->type->name ?? 'N/A' }}</p>
<p><strong>Category:</strong> {{ $weapon->model->category->name ?? 'N/A' }}</p>

   

@php
    $latestHandover = $weapon->handovers->sortByDesc('handover_date')->first();
    $isAvailable = !$latestHandover || $latestHandover->status === 'returned';
@endphp

{{-- Weapon Status --}}
<p>
    <strong>Status:</strong>
    @if($isAvailable)
        <span class="badge bg-success">Available</span>
    @else
        <span class="badge bg-danger">Taken</span>
    @endif
</p>

@if($isAvailable)
    <a href="{{ route('weapons.handover', $weapon) }}" class="btn btn-sm btn-success">
        Handover Weapon
    </a>
@else
    <button class="btn btn-sm btn-secondary" disabled>
        Weapon Already Assigned
    </button>
@endif


    <Center><h2>Weapon Movement History</h2><center>

    <hr>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Staff</th>
                <th>Status</th>
                <th>Handover Date</th>
                <th>Return Date</th>
                <th>Purpose </th>
        
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($weapon->handovers as $handover)
            <tr>
                <td>{{ $handover->staff->lastName ?? 'Unknown' }} ({{ $handover->staff->rank ?? '' }})</td>
                <td>{{ ucfirst($handover->status) }}</td>
                <td>{{ $handover->handover_date }}</td>
                <td>{{ $handover->return_date ?? 'N/A' }}</td>
                <td>{{ $handover->purpose?? 'N/A' }}</td>
               
          
                <td>
                    @if($handover->status == 'assigned')
                       
                        <form action="{{ route('handovers.return', $handover->id) }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" class="btn btn-success btn-sm">
        Mark as Returned
    </button>
</form>

                    @else
                        <span class="badge bg-success">Returned</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">No movement history found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
