@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Course Assignments</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Assign Course for {{ $programme->programmeName }}</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
 
@endsection
@section('content')
<!-- Row starts -->
<div class="row gx-4">
  <div class="col-sm-12">
    <div class="card mb-3">
      <div class="card-header">
        
<!-- <h2>Assigned Courses for {{ $programme->programmeName }}</h2> -->
      </div>
      <div class="pull-right" >
          <a class="btn btn-success mb-2" href="{{ route('assign-courses.create') }}" style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Assign New Courses</a>
      </div>
      <div class="card-body">
        <div class="table-outer">

@if (session('success'))
<p>{{ session('success') }}</p>
@endif
@php
$i=1;
@endphp

<div class="card-body">
        <div class="table-outer">
          <div class="table-responsive">
            <table class="table table-striped truncate m-0">
              <thead>
                    <tr>
                    <th scope="col">No</th>
                    <th scope="col">Course Name</th>
                    <th scope="col">Course Code</th>
                    <th scope="col">Semester</th>
                    <th scope="col">Course Type</th>
                    <th scope="col">Credit Weight</th>
                    <th scope="col" width="280px">Actions</th>
                </tr>
                </thead> 
                <tbody> 
                  @foreach ($courses as $course) 
                  <tr> 
                    <td>{{$i++}}</td> 
                    <td>{{ $course->courseName }}</td> 
                    <td>{{ $course->courseCode }}</td> 
                    <td>{{ $semester->semester_name }}</td> 
                    <td>{{ $course->pivot->course_type }}</td>
                    <td>{{ $course->pivot->credit_weight }}</td>                   
                    <td>
                        <a class="btn btn-primary btn-sm" href="{{ route('assign-courses.edit',$course->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                          <form method="POST" action="{{ route('assign-courses.destroy', $course->id ) }}" style="display:inline">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Remove</button>
                          </form>
                    </td>
                  </tr> 
                  @endforeach 
                </tbody> 
              </table>
          </div>
        </div>
      </div>



        </div>
      </div>
    </div>
  </div>
</div>
<!-- Row ends -->
@endsection
