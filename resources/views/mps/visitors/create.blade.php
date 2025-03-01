@extends('layouts.main')
@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="home">Home</a></li>
                    <li class="breadcrumb-item "><a href="#">MPS</a></li>
                    <li class="breadcrumb-item "><a href="#">Visitors</a></li>
                    <li class="breadcrumb-item active"><a href="#">Create</a></li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->

@endsection
@section('content')
    @session('success')
        <div class="alert alert-success" role="alert">
            {{ $value }}
        </div>
    @endsession  
<div class="card-body">
    <form action="{{ route('visitors.searchStudent') }}" method="POST" class="d-flex justify-content-between mb-3">
        @csrf
        <div class="d-flex">
            <!-- Company Dropdown -->
            <select class="form-select me-2" name="company_id" required>
                <option value="" selected disabled>Select Company</option>
                @foreach ($companies as $company)
                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                @endforeach
            </select>
            <!-- Platoon Dropdown -->
            <select class="form-select me-2" name="platoon" required>
                <option value="">Select Platoon</option>
                @for ($i = 1; $i < 15; $i++)
                    <option value="{{ $i }}" {{ request('platoon') == $i ? 'selected' : '' }}> {{ $i }}</option>
                @endfor
            </select>
            <!-- Name Search -->
            <input type="text" value="{{ request('name')}}" class="form-control me-2" name="name" placeholder="name(option)">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
    @if(isset($students))
    <?php $i = 0; ?>
            <div class="table-outer">
                <div class="table-responsive">
                    <table class="table table-striped m-0">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Platoon</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td>{{ ++$i }}.</td>
                                    <td>{{$student->first_name}} {{$student->middle_name}} {{$student->last_name}}</td>
                                    <td>{{$student->company->name}}</td>
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
                                                            Visitor Details for {{ $student->first_name }} {{ $student->last_name }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{url('/visitors/store/' . $student->id)}}" method="POST">
                                                            @csrf
                                                            @method('POST')
                                                            <div class="mb-3">
                                                                <label for="visitor_name" class="form-label">Visitor Name</label>
                                                                <input class="form-control"   type="text" required
                                                                    name="visitor_name">
                                                            </div>
                                                            @error('visitor_name')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                            <div class="mb-3">
                                                                <label for="visitor_phone" class="form-label">Visitor Phone</label>
                                                                <input class="form-control"   type="text" required
                                                                    name="visitor_phone">
                                                            </div>
                                                            @error('visitor_phone')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                            <div class="mb-3">
                                                                <label for="visitor_relation" class="form-label">Visitor Relation</label>
                                                                <input class="form-control"   type="text" required
                                                                    name="visitor_relation">
                                                            </div>
                                                            @error('visitor_relation')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                            <div class="mb-3">
                                                                <label for="visted_at" class="form-label">Visited At</label>
                                                                <input class="form-control" type="datetime-local" required
                                                                    name="visited_at">
                                                            </div>
                                                            @error('visited_at')
                                                                <div class="error">{{ $message }}</div>
                                                            @enderror
                                                            <div style="display: flex; justify-content: flex-end; margin-right: 2px;">
                                                                <button type="submit" class="btn btn-primary">Save</button>
                                                            </div>
                                                            
                                                            
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