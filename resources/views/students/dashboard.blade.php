@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-rms/students/">Students</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Dashboard</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
<div>
    <h3>Announcements</h3>
    <p> 1. <i>Second Semister exams will start at 3rd March,2025.</i></p>
    <h6>Anounced by <i class="primary" style="color: blue;">Staff Staff</i></h6>
</div>

<div class="row gx-4 mt-1">
    <!-- Attendence starts -->
    <div class="col-xxl-3 col-sm-6 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="p-3  me-3">
                        <img src="/tps-rms/resources/assets/images/attendance.png" style="height:50 !important; width:50"
                            alt="attendence image" />
                    </div>
                    <div class="p3 d-flex flex-column">
                        <p class="m-0 ">Not Attended</p>
                        <h2 class="lh-1 opacity-50">0 days</h2>
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
                        <h2 class="lh-1 opacity-50">0 days</h2>
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
                        <h2 class="lh-1 opacity-50">2 days</h2>
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
                        <h2 class="lh-1 opacity-50">1 days</h2>
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