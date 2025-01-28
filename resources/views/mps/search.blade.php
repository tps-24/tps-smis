@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-rms/mps/">MPS</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Search</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
<!-- Success Message -->
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card-body">
    <form action="{{url('/mps/search')}}" method="POST" class="d-flex justify-content-between mb-3">
        @csrf
        <div class="d-flex">
            <!-- Company Dropdown -->
            <select class="form-select me-2" name="company" required>
                <option value="">Select Company</option>
                <option value="HQ" {{ request('company') == 'HQ' ? 'selected' : '' }}>HQ</option>
                <option value="A" {{ request('company') == 'A' ? 'selected' : '' }}>A</option>
                <option value="B" {{ request('company') == 'B' ? 'selected' : '' }}>B</option>
                <option value="C" {{ request('company') == 'C' ? 'selected' : '' }}>C</option>
            </select>
            <!-- Platoon Dropdown -->
            <select class="form-select me-2" name="platoon" required>
                <option value="">Select Platoon</option>
                @for ($i = 1; $i <= 15; $i++)
                    <option value="{{ $i }}" {{ request('platoon') == $i ? 'selected' : '' }}> {{ $i }}</option>
                @endfor
            </select>
            <!-- Name Search -->
            <input type="text" class="form-control me-2" name="last_name" placeholder="Last name(option)">


        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
    @if(isset($students))
            <div class="table-outer">
                <div class="table-responsive">
                    <table class="table table-striped m-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Platoon</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td>{{$student->first_name}} {{$student->last_name}}</td>
                                    <td>{{$student->company}}</td>
                                    <td>{{$student->platoon}}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ route('students.show', $student->id) }}">View</a>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#statusModal{{ $student->id ?? ''}}">
                                            Enter Details
                                        </button>
                                        <div class="modal fade" id="statusModal{{  $student->id ?? '' }}" tabindex="-1"
                                            aria-labelledby="statusModalLabel{{  $student->id ?? '' }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="statusModalLabel{{  $student->id ?? ''}}">Enter
                                                            Student Details for {{ $student->first_name }} {{ $student->last_name }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{url('/mps/store/' . $student->id)}}" method="POST">
                                                            @csrf

                                                            @method('POST')

                                                            <div class="mb-3">
                                                                <label for="excuseType" class="form-label">Arrested At</label>
                                                                <input class="form-control" type="datetime-local" required
                                                                    name="arrested_at">
                                                            </div>
                                                            @error('arrested_at')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror

                                                            <div class="mb-3">
                                                                <label for="excuseType" class="form-label">Days</label>
                                                                <input class="form-control" value="1" min="1" type="number" required
                                                                    name="days">
                                                            </div>
                                                            @error('days')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror

                                                            <div class="mb-3">
                                                                <label for="description" class="form-label">Description</label>
                                                                <textarea class="form-control" id="" name="description" rows="3"
                                                                    required></textarea>
                                                            </div>
                                                            @error('description')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror

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
        </div>
    @else
        <h4>Please seearch the student.</h4>
    @endif
</div>

@endsection