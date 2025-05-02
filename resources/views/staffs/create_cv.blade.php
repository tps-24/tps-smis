@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Staffs</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Update Curriculum Vitae (CV) </a>
                </li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->
@endsection
@section('content')
<!-- Custom tabs start -->
<div class="custom-tabs-container">

    <!-- Nav tabs start -->
    <ul class="nav nav-tabs" id="customTab2" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="tab-oneA" data-bs-toggle="tab" href="#oneA" role="tab" aria-controls="oneA"
                aria-selected="true"><i class="bi bi-person me-2"></i>Personal
                Particulars</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="tab-twoA" data-bs-toggle="tab" href="#twoA" role="tab" aria-controls="twoA"
                aria-selected="false"><i class="bi bi-info-circle me-2"></i>Education and Training</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="tab-threeA" data-bs-toggle="tab" href="#threeA" role="tab" aria-controls="threeA"
                aria-selected="false"><i class="bi bi-credit-card-2-front me-2"></i>Other Courses, Proffession
                examination </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="tab-fourA" data-bs-toggle="tab" href="#fourA" role="tab" aria-controls="fourA"
                aria-selected="false"><i class="bi bi-eye-slash me-2"></i>Work Exprience</a>
        </li>
    </ul>

    <div class="tab-content h-300">
        <div class="tab-pane fade show active" id="oneA" role="tabpanel">

            <!-- Row starts -->
            <form name="add-blog-post-form" id="add-blog-post-form" method="POST"
                action="{{route('staff.update-cv', ['staffId'=>$staff->id])}}">
                @csrf
                @method('POST')
                <div class="row gx-4">
                    @php
                    $fatherParticulars = $staff->fatherParticulars == null? null :
                    json_decode($staff->fatherParticulars);
                    @endphp
                    <h3>Father's particulars</h3><br>
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Names </label>
                                    <input type="text" value="{{$fatherParticulars[0]?? null}}" class="form-control"
                                        id="father_names" name="father_names" placeholder="Enter father's names">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Village 0f Birth </label>
                                    <input type="text" value="{{$fatherParticulars[1]?? null}}" class="form-control"
                                        id="father's_names" name="father_village_of_birth"
                                        placeholder="Enter father's village of birth">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Ward 0f Birth </label>
                                    <input type="text" value="{{$fatherParticulars[2]?? null}}" class="form-control"
                                        id="father's_names" name="father_ward_of_birth"
                                        placeholder="Enter father's Ward of birth">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">District 0f Birth </label>
                                    <input type="text" value="{{$fatherParticulars[3]?? null}}" class="form-control"
                                        id="father's_names" name="father_district_of_birth"
                                        placeholder="Enter father's district of birth">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Region 0f Birth </label>
                                    <input type="text" value="{{$fatherParticulars[4]?? null}}" class="form-control"
                                        id="father's_names" name="father_region_of_birth"
                                        placeholder="Enter father's region of birth">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php

                $motherParticulars = $staff->motherParticulars == null? null :
                json_decode($staff->motherParticulars);//dd($motherParticulars );
                @endphp
                <h3>Mother's particulars</h3><br>
                <div class="row gx-4">
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Names </label>
                                    <input type="text" value="{{$motherParticulars[0]?? null}}" class="form-control"
                                        id="father_names" name="mother_names" placeholder="Enter father's names">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Village 0f Birth </label>
                                    <input type="text" value="{{$motherParticulars[1]?? null}}" class="form-control"
                                        id="father's_names" name="mother_village_of_birth"
                                        placeholder="Enter father's village of birth">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Ward 0f Birth </label>
                                    <input type="text" value="{{$motherParticulars[2]?? null}}" class="form-control"
                                        id="father's_names" name="mother_ward_of_birth"
                                        placeholder="Enter father's Ward of birth">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">District 0f Birth </label>
                                    <input type="text" value="{{$motherParticulars[3]?? null}}" class="form-control"
                                        id="father's_names" name="mother_district_of_birth"
                                        placeholder="Enter mother's district of birth">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Region 0f Birth </label>
                                    <input type="text" value="{{$motherParticulars[4]?? null}}" class="form-control"
                                        id="father's_names" name="mother_region_of_birth"
                                        placeholder="Enter mother's region of birth">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                $parentsAddress = $staff->parentsAddress == null? null :
                json_decode($staff->parentsAddress);
                @endphp
                <h3>Parent current addres</h3><br>
                <div class="row gx-4">
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Village </label>
                                    <input type="text" value="{{$parentsAddress[0]?? null}}" class="form-control"
                                        id="father's_names" name="parentsVillage" placeholder="Enter current village">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Ward</label>
                                    <input type="text" value="{{$parentsAddress[1]?? null}}" class="form-control"
                                        id="father's_names" name="parentsWard" placeholder="Enter current ward">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">District</label>
                                    <input type="text" value="{{$parentsAddress[2]?? null}}" class="form-control"
                                        id="father's_names" name="parentsDistrict" placeholder="Enter current district">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Region</label>
                                    <input type="text" value="{{$parentsAddress[3]?? null}}" class="form-control"
                                        id="father's_names" name="parentsRegion" placeholder="Enter current region">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-sm btn-primary" type="submit">Update</button>
                </div>
            </form>
            <!-- Row ends -->

        </div>
        <div class="tab-pane fade" id="twoA" role="tabpanel">

            <!-- Row starts -->
            <form name="add-blog-post-form" id="add-blog-post-form" method="POST"
                action="{{route('staff.update_school-cv', ['staffId'=>$staff->id])}}">
                @csrf
                @method('POST')
                <div class="row gx-4">
                    <h3>Primary Schools</h3><br><br>
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Name </label>
                                    <input type="text" class="form-control" id="" name="primary_school_name"
                                        placeholder="Name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Village </label>
                                    <input type="text" class="form-control" id="" name="primary_school_village"
                                        placeholder="Village">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Ward </label>
                                    <input type="text" class="form-control" id="" name="primary_school_ward"
                                        placeholder="Ward">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">District </label>
                                    <input type="text" class="form-control" id="" name="primary_school_district"
                                        placeholder="District">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Region </label>
                                    <input type="text" class="form-control" id="" name="primary_school_region"
                                        placeholder="Region">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Year of Admission </label>
                                    <input type="text" class="form-control" id="" name="primary_school_YoA"
                                        placeholder="1982">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Year of Graduation </label>
                                    <input type="text" class="form-control" id="" name="primary_school_YoG"
                                        placeholder="1988">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-sm btn-primary" type="button">Add School</button>
                    </div>
                    <!-- <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Education Level </label>
                                    <select class="form-control" name="" id="">
                                        <option value="" disabled>select level</option>
                                        @foreach ($education_levels as $education_level)
                                            <option value="{{$education_level->id}}">{{$education_level->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> -->

                </div>
                <div class="row gx-4">
                    <h3>Secondary Schools</h3><br><br>
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Name </label>
                                    <input type="text" class="form-control" id="" name="secondary_school_name"
                                        placeholder="Name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Village </label>
                                    <input type="text" class="form-control" id="" name="secondary_school_village"
                                        placeholder="Village">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Ward </label>
                                    <input type="text" class="form-control" id="" name="secondary_school_ward"
                                        placeholder="Ward">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">District </label>
                                    <input type="text" class="form-control" id="" name="secondary_school_district"
                                        placeholder="District">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Region </label>
                                    <input type="text" class="form-control" id="" name="secondary_school_region"
                                        placeholder="Region">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Year of Admission </label>
                                    <input type="text" class="form-control" id="" name="secondary_school_YoA"
                                        placeholder="1982">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Year of Graduation </label>
                                    <input type="text" class="form-control" id="" name="secondary_school_YoG"
                                        placeholder="1988">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-sm btn-primary" type="button">Add School</button>
                    </div>
                </div>

                <div class="row gx-4">
                    <h3>Universities/Colleges</h3><br><br>
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Name </label>
                                    <input type="text" class="form-control" id="" name="colleges_name"
                                        placeholder="Name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Duration </label>
                                    <input type="text" class="form-control" id="" name="duration" placeholder="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Region/Country </label>
                                    <input type="text" class="form-control" id="" name="colleges_name_region"
                                        placeholder="Tanzania">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Award </label>
                                    <input type="text" class="form-control" id="" name="colleges_award">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Year of Admission </label>
                                    <input type="text" class="form-control" id="" name="colleges_YoA"
                                        placeholder="1982">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="m-0">
                                    <label class="form-label" for="abc">Year of Graduation </label>
                                    <input type="text" class="form-control" id="" name="colleges_YoG"
                                        placeholder="1988">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-sm btn-primary" type="button">Add College</button>
                    </div>
                </div><br>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-sm btn-primary" type="submit">Update</button>
                </div>
            </form>
            <!-- Row ends -->

        </div>
        <div class="tab-pane fade" id="threeA" role="tabpanel">
            <div class="d-flex justify-content-end mb-2">
                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#other">Add</button>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td>S/NO</td>
                        <th>College/Organization</th>
                        <th>Duration</th>
                        <th>Theme and Award</th>
                        <th>Venue</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    @php
                    $i = 0;
                    @endphp
                    @if ($staff->schools)
                    @foreach ($staff->schools as $school)
                    @if ($school->education_level_id == 4)
                    <tr>
                        <td>{{++$i}}</td>
                        <td>{{ $school->name }}</td>
                        <td>{{ $school->duration }}</td>
                        <td>{{ $school->award }}</td>
                        <td>{{ $school->country }}</td>
                        <td class="">
                            <a href="" class="btn btn-sm btn-warning">Edit</a>
                            <a href="" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    @endif
                </tbody>

            </table>
            <div class="modal fade" id="other" tabindex="-1" aria-labelledby="other" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newLabel">Add work or Experience</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form name="add-blog-post-form" id="add-blog-post-form" method="POST"
                                action="{{route('staff.update_other_courses-cv', ['staffId'=>$staff->id])}}">
                                @csrf
                                @method('POST')
                                <!-- Row starts -->
                                <div class="row gx-4">
                                    <div class="col-sm-6 col-12">
                                        <div class="card mb-2">
                                            <div class="card-body">
                                                <div class="m-0">
                                                    <label class="form-label" for="abc">College/Organization </label>
                                                    <input type="text" class="form-control" id="" name="college"
                                                        placeholder="Name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <div class="card mb-2">
                                            <div class="card-body">
                                                <div class="m-0">
                                                    <label class="form-label" for="abc">Duration </label>
                                                    <input type="number" min="1" class="form-control" id=""
                                                        name="duration" placeholder="1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <div class="card mb-2">
                                            <div class="card-body">
                                                <div class="m-0">
                                                    <label class="form-label" for="abc">Theme and Award </label>
                                                    <input type="text" class="form-control" id="" name="award">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-12">
                                        <div class="card mb-2">
                                            <div class="card-body">
                                                <div class="m-0">
                                                    <label class="form-label" for="abc">Venue </label>
                                                    <input type="text" class="form-control" id="" name="venue"
                                                        placeholder="Tanzania">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-sm btn-primary" type="submit">Update</button>
                                    </div>
                                </div><br>
                            </form>
                        </div>

                    </div>



                </div>

            </div>


            <!-- Row ends -->

        </div>
        <div class="tab-pane fade" id="fourA" role="tabpanel">

            <!-- Row starts -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-2">
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#new">Add</button>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>S/NO</td>
                                <th>ORGANIZATION</th>
                                <th>LOCATION</th>
                                <th>POSITION</th>
                                <th>DUTIES</th>
                                <th>From-To</th>
                                <th>Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i = 0;
                            @endphp
                            @foreach ($staff->work_experiences as $work_experience)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$work_experience->institution}}</td>
                                <td>{{$work_experience->address}}</td>
                                <td>{{$work_experience->position}}</td>
                                <td>
                                    @php
                                    $duties = $work_experience->duties == null? null :
                                    json_decode($work_experience->duties);
                                    @endphp
                                    <ul>
                                        @foreach ($duties as $duty)
                                        <li>{{ $duty }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{$work_experience->start_date}} - {{$work_experience->end_date}}</td>
                                <td class="">
                                    <a href="" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="" class="btn btn-sm btn-danger">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
                <div class="modal fade" id="new" tabindex="-1" aria-labelledby="new" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="newLabel">Add work or Experience</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="work_experienceForm"
                                    action="{{route('staff.update_work_experience-cv', ['staffId' => $staff->id])}}"
                                    method="POST">
                                    @csrf
                                    <div class="row gx-4">
                                        <div class="col-sm-6 col-12">
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                        <label class="form-label" for="abc">From </label>
                                                        <input type="date" class="form-control" id="" name="start_date"
                                                            placeholder="12-12-1990">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                        <label class="form-label" for="abc">To </label>
                                                        <input type="date" min="1" class="form-control" id=""
                                                            name="end_date" placeholder="12-12-1996">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                        <label class="form-label" for="abc">Organization/Institution
                                                        </label>
                                                        <input type="text" class="form-control" id="" name="institution"
                                                            placeholder="TPS">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                        <label class="form-label" for="abc">Title </label>
                                                        <input type="text" class="form-control" id="" name="job_title">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-12">
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                        <label class="form-label" for="abc">Position</label>
                                                        <input type="text" class="form-control" id="" name="position"
                                                            placeholder="Manager">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-12">
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                        <label class="form-label" for="abc">Location</label>
                                                        <input type="text" class="form-control" id="" name="address"
                                                            placeholder="Arusha">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-12">
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                        <label class="form-label" for="abc">Duties </label>
                                                        <div class="d-flex gap-2 mb-2 task-row" data-index="0">

                                                            <textarea class="form-control" id="duties" name="duties[]"
                                                                placeholder="Enter job duties here..."
                                                                rows="2"></textarea>
                                                            <button style="height:40px;" type="button"
                                                                class="btn btn-danger delete-task-btn"
                                                                onclick="deleteTask(0)">Delete</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button id="addDutyButton" class="btn btn-sm btn-warning" type="button">Add
                                            Duty</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" onclick="submitForm()" class="btn btn-primary">Update</button>
                            </div>

                        </div>



                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Row ends -->

</div>
</div>
</div>
<script>
function submitForm() {
    document.getElementById("work_experienceForm").submit();
}
// Get the button and task container elements
const addDutyButton = document.getElementById('addDutyButton');
const taskContainer = document.getElementById('dutyContainer');

// Function to add a new task input field
addDutyButton.addEventListener('click', function() {
    const taskCount = document.querySelectorAll('.duty-row').length; // Get the next task index

    // Create a new row for the new task
    const newTaskRow = document.createElement('div');
    newTaskRow.classList.add('d-flex', 'gap-2', 'mb-2', 'duty-row');
    newTaskRow.setAttribute('data-index', taskCount); // Assign a unique index to the new row

    // Add HTML structure for the task input and delete button
    newTaskRow.innerHTML = `
            <label for="">Task</label>
            <input class="form-control" type="text" name="tasks[]" id="task-${taskCount}">
            <button type="button" class="btn btn-danger delete-task-btn" onclick="deleteTask(${taskCount})">Delete</button>
        `;

    // Append the new task row to the task container
    taskContainer.appendChild(newTaskRow);

    // Recheck the delete button state (disable if only one task)
    checkAndDisableDeleteButton();
});
</script>
@endsection