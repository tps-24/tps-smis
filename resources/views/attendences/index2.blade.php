@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-rms/attendences/">Today {{$page->name}} Attendence Summary</a>
                </li>
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


<div class="row">
    <div class="col-8">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane"
                    type="button" role="tab" aria-controls="hq-tab-pane" aria-selected="true">HQ COY</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane"
                    type="button" role="tab" aria-controls="a-tab-pane" aria-selected="false">A COY</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane"
                    type="button" role="tab" aria-controls="b-tab-pane" aria-selected="false">B COY</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#disabled-tab-pane"
                    type="button" role="tab" aria-controls="c-tab-pane" aria-selected="false">C COY</button>
            </li>
        </ul>
    </div>
    <div class="col-4">
        <form action="{{url('attendences/create')}}" method="POST">
            @csrf
            @method('POST')
            <div class=" d-flex gap-2 justify-content-end">
                <div class="">

                </div>
                <div class="">
                    <label for="">Company </label>
                    <select style="height:50%" class="form-select" name="company" id="companies" required
                        aria-label="Default select example">
                        <!-- <option >select company</option> -->
                        @foreach ($companies as $company)
                            <option value="{{$company->id}}">{{$company->name}}</option>
                        @endforeach

                    </select>
                </div>

                <div class=""> <label class="form-label" for="abc4">Platoon</label>
                    <select style="height:50%" class="form-select" name="platoon" id=""
                        aria-label="Default select example">
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
</div>
</div>


<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
        <!-- Start of HQ Coy -->
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
                                <h2 class="lh-1 opacity-50">{{$statistics['hq']['present']}}</h2>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <a class="text-primary ms-4" href="{{url('/today/1/' . $page->id)}}">
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
                                <img src="/tps-rms/resources/assets/images/bed.png"
                                    style="height:50 !important; width:50" alt="Sick image" />
                            </div>
                            <div class="p3 d-flex flex-column">
                                <p class="m-0 ">Sick </p>
                                <h2 class="lh-1 opacity-50">{{$statistics['hq']['sick']}} </h2>
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
                                <img src="/tps-rms/resources/assets/images/leave.png"
                                    style="height:50 !important; width:50" alt="Leave image" />
                            </div>
                            <div class="p3 d-flex flex-column">
                                <p class="m-0 ">Leave </p>
                                <h2 class="lh-1 opacity-50">{{$statistics['hq']['leave']}}</h2>
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
                                <img src="/tps-rms/resources/assets/images/prison.png"
                                    style="height:50 !important; width:50" alt="MPS image" />
                            </div>
                            <div class="p3 d-flex flex-column">
                                <p class="m-0 ">MPS </p>
                                <h2 class="lh-1 opacity-50">{{$statistics['hq']['mps']}}</h2>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <a class="text-primary ms-4" href="{{url("mps/HQ/company")}}">
                                    <span>View</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- MPS days  end. -->
        </div>
    </div>
    <!-- End of HQ coy -->
    <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
        <div class="row gx-4 mt-1">
            <!-- Attendence starts -->

            <!-- Start of A Coy -->
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
                                <h2 class="lh-1 opacity-50">{{$statistics['a']['present']}}</h2>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <a class="text-primary ms-4" href="{{url('/today/2/' . $page->id)}}">
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
                                <img src="/tps-rms/resources/assets/images/bed.png"
                                    style="height:50 !important; width:50" alt="Sick image" />
                            </div>
                            <div class="p3 d-flex flex-column">
                                <p class="m-0 ">Sick </p>
                                <h2 class="lh-1 opacity-50">{{$statistics['a']['sick']}} </h2>
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
                                <img src="/tps-rms/resources/assets/images/leave.png"
                                    style="height:50 !important; width:50" alt="Leave image" />
                            </div>
                            <div class="p3 d-flex flex-column">
                                <p class="m-0 ">Leave </p>
                                <h2 class="lh-1 opacity-50">{{$statistics['a']['leave']}}</h2>
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
                                <img src="/tps-rms/resources/assets/images/prison.png"
                                    style="height:50 !important; width:50" alt="MPS image" />
                            </div>
                            <div class="p3 d-flex flex-column">
                                <p class="m-0 ">MPS </p>
                                <h2 class="lh-1 opacity-50">{{$statistics['a']['mps']}}</h2>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <a class="text-primary ms-4" href="{{url("mps/A/company")}}">
                                    <span>View</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- MPS days  end. -->
        </div>
    </div>
    <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
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
                                <h2 class="lh-1 opacity-50">{{$statistics['b']['present']}}</h2>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <a class="text-primary ms-4" href="{{url('/today/3/' . $page->id)}}">
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
                                <img src="/tps-rms/resources/assets/images/bed.png"
                                    style="height:50 !important; width:50" alt="Sick image" />
                            </div>
                            <div class="p3 d-flex flex-column">
                                <p class="m-0 ">Sick </p>
                                <h2 class="lh-1 opacity-50">{{$statistics['b']['sick']}} </h2>
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
                                <img src="/tps-rms/resources/assets/images/leave.png"
                                    style="height:50 !important; width:50" alt="Leave image" />
                            </div>
                            <div class="p3 d-flex flex-column">
                                <p class="m-0 ">Leave </p>
                                <h2 class="lh-1 opacity-50">{{$statistics['b']['leave']}}</h2>
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
                                <img src="/tps-rms/resources/assets/images/prison.png"
                                    style="height:50 !important; width:50" alt="MPS image" />
                            </div>
                            <div class="p3 d-flex flex-column">
                                <p class="m-0 ">MPS </p>
                                <h2 class="lh-1 opacity-50">{{$statistics['b']['mps']}}</h2>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <a class="text-primary ms-4" href="{{url("mps/B/company")}}">
                                    <span>View</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- MPS days  end. -->
        </div>
    </div>
    <div class="tab-pane fade" id="disabled-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">
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
                                <h2 class="lh-1 opacity-50">{{$statistics['c']['present']}}</h2>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <a class="text-primary ms-4" href="{{url('/today/4/' . $page->id)}}">
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
                                <img src="/tps-rms/resources/assets/images/bed.png"
                                    style="height:50 !important; width:50" alt="Sick image" />
                            </div>
                            <div class="p3 d-flex flex-column">
                                <p class="m-0 ">Sick </p>
                                <h2 class="lh-1 opacity-50">{{$statistics['c']['sick']}} </h2>
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
                                <img src="/tps-rms/resources/assets/images/leave.png"
                                    style="height:50 !important; width:50" alt="Leave image" />
                            </div>
                            <div class="p3 d-flex flex-column">
                                <p class="m-0 ">Leave </p>
                                <h2 class="lh-1 opacity-50">{{$statistics['c']['leave']}}</h2>
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
                                <img src="/tps-rms/resources/assets/images/prison.png"
                                    style="height:50 !important; width:50" alt="MPS image" />
                            </div>
                            <div class="p3 d-flex flex-column">
                                <p class="m-0 ">MPS </p>
                                <h2 class="lh-1 opacity-50">{{$statistics['c']['mps']}}</h2>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <a class="text-primary ms-4" href="{{url("mps/C/company")}}">
                                    <span>View</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- MPS days  end. -->
        </div>
    </div>
</div>

@endsection