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

    // dd($typeToAppend);
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
                            class="form-control" id="force_number" name="force_number" placeholder="Enter force number">
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
                        <label class="form-label" for="abc">First Name</label>
                        <input @if(isset($student)) value="{{$student->first_name}}" @endif type="text"
                            class="form-control" id="first_name" name="first_name" required
                            placeholder="Enter firstname">
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
                            placeholder="Enter middlename">
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
                            class="form-control" id="last_name" name="last_name" required placeholder="Enter lastname">
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
                        <select class="form-select" id="abc4" name="education_level" required
                            aria-label="Default select example">
                            <!-- <option selected="">select gender</option> -->
                            <option @if(isset($student) && $student->education_level == "Form Four") selected @endif
                                value="Form Four">Form Four</option>
                            <option @if(isset($student) && $student->education_level == "Form Six") selected @endif value="Form Six">Form Six</option>
                            <option @if(isset($student) && $student->education_level == "Certificate") selected @endif value="Certificate">Certificate</option>
                            <option @if(isset($student) && $student->education_level == "Diploma") selected @endif value="Diploma">Diploma</option>
                            <option @if(isset($student) && $student->education_level == "Degree") selected @endif value="Degree">Degree</option>

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
                        <input @if(isset($student)) value="{{$student->home_region}}" @endif type="text" class="form-control" id="home_region" name="home_region" required
                            placeholder="Enter home region">
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