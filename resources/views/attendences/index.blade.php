@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-rms/attendences/">Attendences</a></li>
                <!-- <li class="breadcrumb-item active" aria-current="page"><a href="#">List</a></li> -->
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
<form action="{{url('attendences/create')}}" method="POST">
    @csrf
    @method('POST')
    <div class=" d-flex gap-2 justify-content-end">
        <div class="">
            <label for="">Company </label>
            <select style="height:50%" class="form-select" name="company" id="abc4" required aria-label="Default select example">
                <!-- <option >select company</option> -->
                <option value="1">HQ</option>
                <option value="2">A</option>
                <option value="3">B</option>
                <option value="4">C</option>
            </select>
        </div>
        <div class=""> <label class="form-label" for="abc4">Platoon</label>
            <select style="height:50%" class="form-select" name="platoon" id="abc4" aria-label="Default select example">
                <!-- <option selected="">select platoon</option> -->
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
            </select>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-success btn-sm">New
                    attendance</button>
        </div>
    </div>
    </div>
</form>

<div class="row gx-4 mt-1">
    <!-- Attendence starts -->
    <div class="col-xxl-3 col-sm-6 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="p-3  me-3">
                        <img src="/tps-rms/resources/assets/images/attendance.png"
                            style="height:50 !important; width:50" alt="attendence image" />
                    </div>
                    <div class="p3 d-flex flex-column">
                        <p class="m-0 ">Attended</p>
                        <h2 class="lh-1 opacity-50">{{$statistics['present']}}</h2>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-1">
                        <a class="text-primary ms-4" href="{{url('today')}}">
                            <span>View</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Attendence  end. -->

    <!-- Sick days starts -->
    <div class="col-xxl-3 col-sm-6 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="p-3  me-3">
                        <img src="/tps-rms/resources/assets/images/bed.png" style="height:50 !important; width:50"
                            alt="Sick image" />
                    </div>
                    <div class="p3 d-flex flex-column">
                        <p class="m-0 ">Sick </p>
                        <h2 class="lh-1 opacity-50">{{$statistics['sick']}} </h2>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-1">
                        <a class="text-primary ms-4" href="javascript:void(0);">
                            <span>View</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sick days  end. -->

    <!-- Leave days starts -->
    <div class="col-xxl-3 col-sm-6 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="p-3  me-3">
                        <img src="/tps-rms/resources/assets/images/leave.png" style="height:50 !important; width:50"
                            alt="Leave image" />
                    </div>
                    <div class="p3 d-flex flex-column">
                        <p class="m-0 ">Leave </p>
                        <h2 class="lh-1 opacity-50">{{$statistics['leave']}}</h2>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-1">
                        <a class="text-primary ms-4" href="javascript:void(0);">
                            <span>View</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave days  end. -->

    <!-- MPS days starts -->
    <div class="col-xxl-3 col-sm-6 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="p-3  me-3">
                        <img src="/tps-rms/resources/assets/images/prison.png" style="height:50 !important; width:50"
                            alt="MPS image" />
                    </div>
                    <div class="p3 d-flex flex-column">
                        <p class="m-0 ">MPS </p>
                        <h2 class="lh-1 opacity-50">{{$statistics['mps']}}</h2>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-1">
                        <a class="text-primary ms-4" href="javascript:void(0);">
                            <span>View</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MPS days  end. -->
</div>
@endsection