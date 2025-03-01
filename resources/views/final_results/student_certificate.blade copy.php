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
            <th width="280px">Certificate Status</th>
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
          <!-- @can('student-list')
          <a class="btn btn-info btn-sm" href="{{ route('students.show', $student->id) }}">
          Show</a>
          @endcan
          @can('student-edit')
          <a class="btn btn-primary btn-sm" href="{{ route('students.edit', $student->id) }}">Edit</a>
          @endcan -->
          @if ($student->transcript_printed == 1)
          <a class="btn btn-success btn-sm" href="#">
          Printed</a>
          @else
          <a class="btn btn-warning btn-sm" href="#">
          Not Printed</a>
          @endif
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