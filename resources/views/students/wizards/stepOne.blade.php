@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-rms/students/">Students</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">
                        @if(isset($student))
                            Update
                        @else
                            Create
                        @endif
                        Step One</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
<?php $typeToAppend ="";
    if(isset(($student))){
        $typeToAppend = "edit";

    }else {
         $typeToAppend = "create";
    }

?>
<form action="{{url('students/create/post-step-one/'.$typeToAppend)}}" method="POST">
    @csrf
    @method('POST')

    <div class="row gx-4">
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <input @if(isset($student)) value="{{$student->id}}" @endif type="text" class="form-control" id="id" name="id" hidden>
                        <label class="form-label" for="abc">Force Number </label>
                        <input @if(isset($student)) value="{{$student->force_number}}" @endif type="text"
                            class="form-control" id="force_number" name="force_number" placeholder="Enter force number" value="{{old('force_number')}}">
                    </div>
                    @error('force_number')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc4">Rank</label>
                        <select class="form-select" id="abc4" name="rank" required
                            aria-label="Default select example">
                            <option value="default_value" {{ old('rank', 'default_value') == 'default_value' ? 'selected' : '' }} disabled>Select rank</option>
                            <!-- <option @if(isset($student) && $student->rank == "Recruit") selected @endif value="Recruit" {{ old('rank', 'default_value') == 'Recruit' ? 'selected' : '' }}>Basic Recruit</option> -->
                            <option @if(isset($student) && $student->rank == "Constable") selected @endif value="Constable" {{ old('rank', 'default_value') == 'Constable' ? 'selected' : '' }}>Police Constable</option>
                            <!-- <option @if(isset($student) && $student->rank == "Corporal") selected @endif value="Corporal" {{ old('rank', 'default_value') == 'Corporal' ? 'selected' : '' }}>Corporal</option>
                            <option @if(isset($student) && $student->rank == "Sergeant") selected @endif value="Sergeant" {{ old('rank', 'default_value') == 'Sergeant' ? 'selected' : '' }}>Sergeant Major</option> -->

                        </select>
                    </div>
                    @error('rank')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">First Name</label>
                        <input @if(isset($student)) value="{{$student->first_name}}" @endif type="text"
                            class="form-control" id="first_name" name="first_name" required
                            placeholder="Enter firstname" value="{{old('first_name')}}">
                    </div>
                    @error('first_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">Middle Name</label>
                        <input @if(isset($student)) value="{{$student->middle_name}}" @endif type="text"
                            class="form-control" id="middle_name" name="middle_name" required
                            placeholder="Enter middlename" value="{{old('middle_name')}}">
                    </div>
                    @error('middle_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">Last Name</label>
                        <input @if(isset($student)) value="{{$student->last_name}}" @endif type="text"
                            class="form-control" id="last_name" name="last_name" required placeholder="Enter lastname" value="{{old('last_name')}}">
                    </div>
                    @error('last_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc4">Education Level</label>
                        <select class="form-select" id="abc4" name="education_level" value="{{old('education_level')}}" required
                            aria-label="Default select example">
                            <option value = "" selected disabled >select education</option>
                            <option @if(isset($student) && $student->education_level == "Form Four") selected @endif
                                value="Form Four" {{ old('education_level', 'default_value') == 'Form Four' ? 'selected' : '' }}>Form Four</option>
                            <option @if(isset($student) && $student->education_level == "Form Six") selected @endif value="Form Six" {{ old('education_level', 'default_value') == 'Form Six' ? 'selected' : '' }}>Form Six</option>
                            <option @if(isset($student) && $student->education_level == "Certificate") selected @endif value="Certificate" {{ old('education_level', 'default_value') == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                            <option @if(isset($student) && $student->education_level == "Diploma") selected @endif value="Diploma" {{ old('education_level', 'default_value') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                            <option @if(isset($student) && $student->education_level == "Degree") selected @endif value="Degree" {{ old('education_level', 'default_value') == 'Degree' ? 'selected' : '' }}>Degree</option>

                        </select>
                    </div>
                    @error('education_level')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">Home Region</label>
                        <input @if(isset($student)) value="{{$student->home_region}}" @endif type="text" class="form-control" id="home_region" name="home_region" 
                            placeholder="Enter home region" value="{{old('home_region')}}">
                    </div>
                    @error('home_region')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-6 text-left">

                </div>
                <div class="card-footer">
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="submit" class="btn btn-primary">Next</button>
                    </div>
                </div>
            </div>
        </div>
</form>
@endsection