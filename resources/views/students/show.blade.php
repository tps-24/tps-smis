@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="/tps-rms/students/">Students</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">View</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
 
@endsection
@section('content')

<div class="row gx-4 mt-1">
    <!-- Attendence starts -->
    <div class="col-xxl-3 col-sm-4 col-12">
        <div class="card ">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="  me-3">
                        <img src="/tps-rms/resources/assets/images/profile.png" style="height:50 !important; width:50"
                            alt="profile image" />
                        <p>{{ $student->force_number }}, {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</p>
                        <p>{{$student->rank}}</p>
                        <p> Company: {{$student->company}}</p>
                        <p>Platoon: {{$student->platoon}}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-4 col-12">
        <div class="card mb-2">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="p-3  me-3">
                        <p>Date of birth: {{$student->dob}}</p>
                        <p>Education: {{$student->education_level}}</p>
                        
                        <p>NIDA: {{$student->nin}}</p>
                        <p>Phone: {{$student->phone}}</p>
                        <p>Home: {{$student->home_region}}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-xxl-3 col-sm-4 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="p-4  me-3">
                    <p>Gender: Male</p>
                        <p>Blood Group: {{$student->blood_group}}</p>
                        <p>height: {{$student->height}} ft</p>
                        <p>Weight: {{$student->weight}} kg</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <h4>Next Of Kin Informations</h4>
        <p>Names: {{$student->next_kin_names}}</p>
        <p>Relationship: {{$student->next_kin_relationship}}</p>
        <p>Phone: {{$student->next_kin_phone}}</p>
        <p>Address: {{$student->next_kin_address}}</p>
    </div>
</div>

</div>
@endsection