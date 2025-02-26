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

<div class="row">
  @session('success')
    <div class="alert alert-success" role="alert">
    {{ $value }}
    </div>
  @endsession
  <div class="row">
    @can('student-create')
    <div class="col-3">
      <a href="{{ route('uploadStudents') }}" class="btn btn-sm btn-primary">Upload Students</a>
    </div>
    @endcan
    <div class="col-6 " style="float: right;">
      <form class="d-flex" action="{{route('students.search')}}" method="POST">
        <!-- <div class="mx-auto p-2" style="width: 200px;"> Search </div> -->
        @csrf
        @method("POST")
        <div class="d-flex">
          <!-- Name Search -->
          <input type="text" value="{{ request('name')}}" class="form-control me-2" name="name" placeholder="name(option)">
            <!-- Company Dropdown -->
            <select class="form-select me-2" name="company_id" required>
                <option value="">Select Company</option>
                @foreach ($companies as $company)
                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                @endforeach
            </select>
            <!-- Platoon Dropdown -->
            <select onchange="this.form.submit()" class="form-select me-2" name="platoon" required>
                <option value="">Select Platoon</option>
                @for ($i = 1; $i < 15; $i++)
                    <option value="{{ $i }}" {{ request('platoon') == $i ? 'selected' : '' }}> {{ $i }}</option>
                @endfor
            </select>
            </div>
      </form>
    </div>



    <!-- <div class="col col-lg-2">
      <a class="btn btn-success btn-sm mb-2" href="{{url('students/create')}}">Create Student</a>
    </div> -->
    @can('student-create')
    <div class="col-3 pull-right">
    
      <a class="btn btn-success mb-2 btn-sm" href="{{ url('students/create') }}"
        style="float:right !important; margin-right:-22px"><i class="fa fa-plus"></i> Create New Student</a>
    </div>
    @endcan
  </div>
</div>

<div class="card-body">
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
            <th width="280px">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 0;?>
          @foreach ($students as $key => $student)

        <tr>
        <td>{{++$i}}</td>
        <td>{{$student->force_number ?? ''}}</td>
        <td>{{$student->first_name}} {{$student->middle_name}} {{$student->last_name}}</td>
        <td>{{$student->company->name ?? ''}}</td>
        <td>{{$student->platoon}}</td>
        <td>{{$student->phone}}</td>
        <td>{{$student->home_region}}</td>
        <td>
          @can('student-list')
          <a class="btn btn-info btn-sm" href="{{ route('students.show', $student->id) }}">
          Show</a>
          @endcan
          @can('student-edit')
          <a class="btn btn-primary btn-sm" href="{{ route('students.edit', $student->id) }}">Edit</a>
          @endcan
          @can('student-delete')
          <!-- <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
          data-bs-target="#createNewContact{{$student->id}}">Delete</button> -->
          @endcan
          @if ($student->beat_status == 1)
          <a class="btn btn-warning btn-sm" href="{{ route('students.deactivate_beat_status', $student->id) }}">
          Deactivate</a>
          @else
          <a class="btn btn-warning btn-sm" href="{{ route('students.activate_beat_status', $student->id) }}">
          Activate</a>
          @endif
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

</div>

{!! $students->links('pagination::bootstrap-5') !!}

@endsection