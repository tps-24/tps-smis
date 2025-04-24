@extends('layouts.main')

@section('content')
<div class="container mx-auto p-6">
<div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Rejected Leave Requests</h5>
        <a href="{{ route('leave-requests.chief-instructor') }}" class="btn btn-secondary">
            Back to Leave Requests
        </a>
    </div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <center><h2 class="text-3xl font-bold text-gray-800">Approved Leave Requests Details</h2></center>

    </div>

    <!-- Approved Requests Table -->
    <div class="bg-white shadow-md rounded p-6 overflow-x-auto">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th> Student Name</th>
                    <th> Reason</th>
                    <th> Start date</th>
                    <th> End date</th>
                    <th>Approved At</th>
                    <th>Action</th>
                    
                </tr>
            </thead>
            <tbody>
                @forelse ($approvedRequests as $request)
                    <tr>
                        
                        <td>
                            {{ $request->student->first_name ?? '' }} {{ $request->student->last_name ?? '' }}
                        </td>
                        <td>
                            {{ $request->reason }}
                        </td>
                        <td >
                            {{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }}
                        </td>
                        <td >
                            {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($request->approved_at)->format('d M Y H:i') }}
                        </td>
                  
                        <td class="d-flex gap-2">
                        
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal{{ $request->id }}">
                            <a href="{{ route('leave-requests.single.pdf', $request->id) }}">
                               Download 
                            </button>
            
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            No approved leave requests found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
