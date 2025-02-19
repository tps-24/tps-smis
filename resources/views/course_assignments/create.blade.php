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
                        <!-- <div class="pull-left">
                            <h2>Add New Course</h2>
                        </div> -->
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
                    
                    <div class="row">
                        <div class="col-12" id="pfn0">
                            <div class="card mb-4">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc4">Course Type</label>
                                    <select class="form-select" name="course_type" aria-label="Default select">
                                        <option value="" selected disabled>-- Choose course type</option> 
                                        <option value="core" {{ old('course_type', 'default_value') == 'core' ? 'selected' : '' }}>Core</option>
                                        <option value="minor" {{ old('course_type', 'default_value') == 'minor' ? 'selected' : '' }}>Minor</option>
                                        <option value="optional" {{ old('course_type', 'default_value') == 'optional' ? 'selected' : '' }}>Optional</option>
                                    </select>
                                </div>
                                @error('forceNumber')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            </div>
                        </div>        
                        <div class="col-12" id="pfn0">
                            <div class="card mb-4">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="course_id">Courses</label>
                                    <select name="course_ids[]" multiple id="course_id" class="form-control"> 
                                    <option value="" selected disabled>-- Choose course(s) to enroll</option> 
                                        @foreach ($courses as $course) 
                                        <option value="{{ $course->id }}">{{ $course->courseName }}</option> 
                                        @endforeach 
                                    </select> 
                                </div>
                                @error('course_id')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            </div>
                        </div>
                        <div class="col-12" id="pfn0">
                            <div class="card mb-4">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label abcd" for="abc">Credit Weight</label>
                                    <input type="number" class="form-control" name="credit_weight" placeholder="Enter weight of the course" value="{{old('credit_weight')}}" required>
                                </div>
                                @error('credit_weight')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            </div>
                        </div> 
                        <input type="number" name="session_programme_id" value="4" class="form-control" hidden>
                        <input type="number" name="programme_id" value="1" class="form-control" hidden>
                        <input type="number" name="semester_id" value="2" class="form-control" hidden>
                        <input type="number" name="created_by" value="{{ Auth::user()->id }}" class="form-control" hidden>  
                        <br> 
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
                        </div>
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
