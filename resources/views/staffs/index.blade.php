@extends('layouts.main')

@section('scrumb')
  <!-- Scrumb starts -->
  <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Staffs</a></li>
      <li class="breadcrumb-item active" aria-current="page"><a href="#">Staff Lists</a></li>
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
  @php
    $i = 0;
  @endphp
  <!-- Row starts -->
  <div class="row gx-4">
    <div class="col-sm-12">
    <div class="card mb-3">
      <div class="card-header">

      </div>
      <div class="row">
      @can('student-create')
      <div class="col">
      <a href="{{ route('uploadStaff') }}" class="btn btn-sm btn-primary">Upload Staff</a>
      
      </div>
      <div class="pull-right col" style="margin-right: 0px;">
      <a class="btn btn-success mb-2 btn-sm" href="{{ route('staffs.create') }}"
      style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Create New Staff</a>
      </div>
      </div>

    @endcan

      <div class="card-body">
      <div class="table-outer">
        <div class="table-responsive">
        <table class="table table-striped truncate m-0">
          <thead>
          <tr>
            <th scope="col">No</th>
            <th scope="col">PF Number</th>
            <th scope="col">Name</th>
            <th scope="col">Rank</th>
            <th scope="col">Department</th>
            <th scope="col">Phone Number</th>
            <th scope="col" width="280px">Actions</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($staffs as $key => $staff)
        <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $staff->forceNumber }}</td>
        <td>{{ $staff->firstName }} {{ $staff->middleName }} {{ $staff->lastName }}</td>
        <td>{{ $staff->rank }}</td>
        <td>{{ $staff->department->departmentName ?? '' }}</td>
        <td>{{ $staff->phoneNumber }}</td>
        <td>
        <a class="btn btn-info btn-sm" href="{{ route('staffs.show', $staff->id) }}"><i
          class="fa-solid fa-list"></i> Show</a>
        <a class="btn btn-primary btn-sm" href="{{ route('staffs.edit', $staff->id) }}"><i
          class="fa-solid fa-pen-to-square"></i> Edit</a>
        <form method="POST" action="{{ route('staffs.destroy', $staff->id) }}" style="display:inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i>
          Delete</button>
        </form>
        </td>
        </tr>
      @endforeach
          </tbody>
        </table>
        {!! $staffs->links('pagination::bootstrap-5') !!}
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- Row ends -->
@endsection