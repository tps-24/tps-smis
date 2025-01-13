@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="/tps-rms/students/">Students</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">List</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
 
@endsection
</table>
@section('content')
<div class="row">

  <div class="col-lg-12 margin-tb">
    <div class="row">
      <div class="col-3">
        <form method="POST" action="{{url('students/bulkimport')}}" style="display:inline"
          enctype="multipart/form-data">
          @csrf
          @method('POST')
          <input required type="file" name="import_file" class="form-control">
          <button title="Upload by CSV/excel file" type="submit" class="btn btn-primary ">Upload Students</i></button>
        </form>
      </div>

      <!-- <div class="col-6">
        <a class="btn btn-success mb-2" href="{{url('students/create')}}">Create Student</a>
      </div> -->
      <div class="pull-right" >
          <a class="btn btn-success mb-2" href="{{ route('students.create') }}" style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Create New Student</a>
      </div>
    </div>
  </div>
</div>

@session('success')
  <div class="alert alert-success" role="alert">
    {{ $value }}
  </div>
@endsession
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
        <td>{{$student->force_number}}</td>
        <td>{{$student->first_name}} {{$student->last_name}}</td>
        <td>{{$student->company}}</td>
        <td>{{$student->platoon}}</td>
        <td>{{$student->phone}}</td>
        <td>{{$student->home_region}}</td>
        <td>
          <a class="btn btn-info btn-sm" href="{{ route('students.show',$student->id) }}">
             Show</a>
          <a class="btn btn-primary btn-sm"  href="{{ route('students.edit',$student->id) }}">Edit</a>
          <form method="POST" action="{{url('students/' . $student->id . '/delete')}}" style="display:inline">
          @csrf
          @method('POST')

          <button type="submit" class="btn btn-danger btn-sm">Delete</i></button>
          </form>
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