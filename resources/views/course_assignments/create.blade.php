@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Courses Assignments</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Assign Course for {{ $programme->programmeName }}</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
@endsection
@section('content')
<div class="row gx-4">
    <div class="col-sm-8 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-left">
                            <h2>Add New Course</h2>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('assign-courses.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    </div>
                @endif


                
<form action="{{ route('assign-courses.store', [$programme->id, $semester->id, $sessionProgramme->id]) }}" method="POST">
    @csrf
    
           
    <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label for="course_type">Course Type</label> 
                                <select name="course_type" id="course_type" class="form-control"> 
                                    <option value="core">Core</option> 
                                    <option value="minor">Minor</option> 
                                    <option value="optional">Optional</option> 
                                </select> 
                            </div> 
                        </div>          
    <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label for="course_id">Courses</label> 
                                <select name="course_ids[]" multiple id="course_id" class="form-control"> 
                                    @foreach ($courses as $course) 
                                    <option value="{{ $course->id }}">{{ $course->courseName }}</option> 
                                    @endforeach 
                                </select> 
                            </div> 
                        </div> 


                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label for="credit_weight">Credit Weight</label> 
                                <input type="text" name="credit_weight" id="credit_weight" class="form-control"> 
                            </div> 
                        </div> 
                        <input type="number" name="session_programme_id " value="4" class="form-control" hidden>
                        <input type="number" name="programme_id " value="1" class="form-control" hidden>
                        <input type="number" name="semester_id " value="1" class="form-control" hidden>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
                        </div>
</form>
            </div>
        </div>
     
    </div>
  
    <div class="col-sm-4 col-12">
        <div class="card mb-8">
            <div class="card-body">
            </div>
        </div>
    </div>
</div>
@endsection



<!DOCTYPE html>
<html>
<head>
    <title>Assign Courses</title>
</head>
<body>
</body>
</html>
