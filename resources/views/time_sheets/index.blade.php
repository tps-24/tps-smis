@extends('layouts.main')
@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                    <li class="breadcrumb-item active"><a href="">Time Sheets</a></li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->

@endsection
@section('content')
    @session('success')
        <div class="alert alert-success alert-dismissible " role="alert">
            {{ $value }}
        </div>
    @endsession

    @php
        $i = 0;
        use Carbon\Carbon;
    @endphp
    <div style="display: flex; justify-content: end;">
        <a href="{{ route('timesheets.create') }}"><button class="btn btn-sm btn-success">New Time Sheet</button></a>
    </div>
    <div class="card">
        <div class="card-body">
            @if (count($timesheets) > 0)
                <div class="table-outer">
                    <div class="table-responsive">

                        <table class="table table-striped truncate m-0">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Approved By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($timesheets as $timesheet)
                                    <tr>
                                        <td>{{ ++$i}}.</td>
                                        <td>{{ Carbon::parse($timesheet->time_in)->format('H:i') }}</td>
                                        <td>{{ Carbon::parse($timesheet->time_out)->format('H:i') }}</td>
                                        <td>{{  Carbon::parse($timesheet->date)->format('d F, Y') }}</td>
                                        <td>{{ $timesheet->status }}</td>
                                        <td>{{ $timesheet->approvedBy->name?? '' }}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#statusModal{{ $timesheet->id ?? ''}}">
                                                More
                                            </button>

                                            @can('view-timesheet', $timesheet)
                                              <button @if($timesheet->status == 'approved') disabled @endif class="btn btn-sm btn-warning">   <a 
                                                    href="{{ route('timesheets.edit', $timesheet->id) }}"> Edit</a></button>
                                            @endcan

                                            <form action="{{ route('timesheets.destroy', $timesheet->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button @if($timesheet->status == 'approved') disabled @endif class="btn btn-sm btn-danger" type="submit">Delete</button>
                                            </form>
                                        </td>

                                        <div class="modal fade" id="statusModal{{  $timesheet->id ?? '' }}" tabindex="-1"
                                            aria-labelledby="statusModalLabel{{  $timesheet->id ?? '' }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="statusModalLabel{{  $timesheet->id ?? ''}}">
                                                            Task Description
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ $timesheet->task }}
                                                    </div>
                                                    @can('viewAny', $timesheet)
                                                    <div class="modal-footer">
                                                        <form action="{{ route('timesheets.approve', $timesheet->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button @if($timesheet->status == 'approved') disabled @endif class="btn btn-sm btn-primary">Approve</button>
                                                        </form>                                                       
                                                    </div>
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            @else
                No time Sheets available.
            @endif
        </div>
    </div>
@endsection