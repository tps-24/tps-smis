@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-smis/students/">Students</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">List</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection

@section('content')
@include('layouts.sweet_alerts.index')
<div class="row">
    @can('student-create')
    <div class="col-2">
        <a href="{{ route('uploadStudents') }}" class="btn btn-sm btn-primary">Upload Students</a>
    </div>
    @endcan
    @can('student-edit')
    <div class="col-2">
        <a href="{{ route('updateStudents') }}" class="btn btn-sm btn-secondary">Update Students</a>
    </div>
    @endcan
    <div class="col-5 " style="float: right;">
        <form class="d-flex" action="{{route('students.search')}}" method="GET">
            @csrf
            @method("POST")
            <div class="d-flex">
                <!-- Name Search -->
                <input type="text" value="{{ request('name')}}" class="form-control me-2" name="name"
                    placeholder="name(option)">
                <!-- Company Dropdown -->
                <select onchange="this.form.submit()" class="form-select me-2" name="company_id">
                    <option value="" selected disabled>Select Company</option>
                    @foreach ($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                    @endforeach
                </select>
                <!-- Platoon Dropdown -->
                <select onchange="this.form.submit()" class="form-select me-2" name="platoon">
                    <option value="" selected disabled>Select Platoon</option>
                    @for ($i = 1; $i < 15; $i++) <option value="{{ $i }}"
                        {{ request('platoon') == $i ? 'selected' : '' }}> {{ $i }}</option>
                        @endfor
                </select>
            </div>
        </form>
    </div>



    <!-- <div class="col col-lg-2">
      <a class="btn btn-success btn-sm mb-2" href="{{url('students/create')}}">Create Student</a>
    </div> -->
    @can('student-create')
    <div class="d-flex justify-content-end gap-2 col-3">
        @if(request()->has('platoon'))
        <a class="btn btn-success btn-sm mb-2"
            href="{{ route('students.generatePdf', [request('platoon'),request('company_id')]) }}">
            <i class="gap 2 bi bi-download"></i> Sheet
        </a>
        @endif
        <a class="btn btn-success btn-sm mb-2" href="{{ url('students/create') }}">
            <i class="fa fa-plus"></i> Create New Student
        </a>
    </div>

    @endcan
</div>
<br>
<center><span>Waliohakikiwa: {{ $approvedCount }}</span><center>
</div>

<div class="card-body">
    @if ($students->isEmpty())
    <h3>No student available for provided criteria.</h3>
    @else
    <div class="table-outer">
        <div class="table-responsive">
            <table class="table table-striped truncate m-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Force Number</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Platoon</th>
                        <th>Phone</th>
                        <th>Home Region</th>
                        <th>Action</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php  $i = 0;?>
                    @foreach ($students as $key => $student)

                    <tr>
                        <td>{{++$i}}</td>
                        <td>{{$student->force_number ?? ''}}</td>
                        <td>{{$student->first_name}} {{$student->middle_name}} {{$student->last_name}}</td>
                        <td>{{$student->company->name ?? ''}}</td>
                        <td>{{$student->platoon}}</td>
                        <td>{{$student->phone}}</td>
                        <td>{{$student->home_region}}</td>
                        <td class="d-flex gap-2">
                            @can('student-list')
                            <a class="btn btn-info btn-sm" href="{{ route('students.show', $student->id) }}">
                                Show</a>
                            @endcan
                            @can('student-edit')
                            <a class="btn btn-primary btn-sm" href="{{ route('students.edit', $student->id) }}">Edit</a>
                            @if($student->status != 'approved')
                            <form id="confirmForm{{ $student->id }}" action="{{ route('students.approve', $student->id) }}" method="post">
                                @csrf
                                <button onclick="confirmAction('confirmForm{{ $student->id }}', 'Verify','{{$student->force_number}} {{$student->rank}} {{$student->first_name}}','Verify')" type="button" class="btn btn-sm btn-warning">
                                    Verify
                                </button>
                                
                            </form>
                            @else
                            <button type="button" class="btn btn-sm btn-success">
                                Verified
                            </button>
                            @endif
                            @endcan

                            @can('student-delete')
                            <!-- <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
        data-bs-target="#createNewContact{{$student->id}}">Delete</button> -->
                            @endcan

                        </td>

                        @can('beat-edit')
                        <td>
                            @if($student->beat_status == '1')
                            <form action="{{ route('students.deactivate_beat_status', $student->id) }}" method="POST"
                                id="toggleForm{{ $student->id }}">
                                @csrf
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="statusToggle{{ $student->id }}"
                                        name="status{{ $student->id }}" @if($student->beat_status == '1') checked
                                    @endif>
                                </div>
                                <button type="submit" style="display: none;">Submit</button>
                            </form>

                            @else
                            <form action="{{ route('students.activate_beat_status', $student->id) }}" method="POST"
                                id="toggleForm{{ $student->id }}" class="d-flex gap-2">
                                @csrf
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="statusToggle{{ $student->id }}"
                                        name="status{{ $student->id }}">
                                </div>
                                <button type="submit" style="display: none;">Submit</button>
                            </form>
                            @endif
                        </td>
                        @endcan()
                        <script>
                        // Listen for changes to the toggle
                        document.getElementById('statusToggle{{ $student->id }}').addEventListener('change',
                            function() {
                                // Automatically submit the form when toggle is changed
                                document.getElementById('toggleForm{{ $student->id }}').submit();
                            });
                        </script>


                        <div class="modal fade" id="createNewContact{{$student->id}}" tabindex="-1"
                            aria-labelledby="createNewContactLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header flex-column">
                                        <div class="text-center">
                                            <h4 class="text-danger">Delete Student</h4>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <h5>You are about to delete {{$student->first_name}} {{$student->middle_name}}
                                            {{$student->last_name}}.
                                        </h5>
                                        <p>Please confirm to delete.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                                            Cancel
                                        </button>
                                        <form method="POST" action="{{url('students/' . $student->id . '/delete')}}"
                                            style="display:inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-danger btn-sm">Confirm</i></button>
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
    @endif
</div>

{!! $students->links('pagination::bootstrap-5') !!}

@endsection