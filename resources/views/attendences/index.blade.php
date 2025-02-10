@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-smis/" id="home">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-smis/attendences/">Today Attendence Summary</a>
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
<div class="d-flex  justify-content-end">
    <div class="col-4 z-index:1">
        <form action="{{url('attendences/create/' . $page->id)}}" method="POST">
            @csrf
            @method('POST')
            <div class=" d-flex gap-2 justify-content-end">
                <div class="">
                    <label for="">Company </label>
                    <select style="height:60%" class="form-select" name="company" id="companies" required
                        aria-label="Default select example">
                        <option value="">company</option>
                        @foreach ($companies as $company)
                            <option value="{{$company->name}}">{{$company->name}}</option>
                        @endforeach

                    </select>
                </div>

                <div class=""> <label class="form-label" for="abc4">Platoon</label>
                    <select style="height:60%" class="form-select" name="platoon" required id=""
                        aria-label="Default select example">
                        <option value="">platoon</option>
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
                    <button type="submit" class="btn btn-success ">New
                    </button>
                </div>
            </div>
    </div>
    </form>
</div>


<div class="row gx-4">
    <div class="col-sm-12 col-12">
        <div class=" mb-4">
            <div class="">
                <!-- Custom tabs start -->
                <div class="custom-tabs-container">
                    <!-- Nav tabs start -->
                    <ul class="nav nav-tabs" id="customTab2" role="tablist">
                        <?php
$i = 0;
                        ?>
                        @foreach ($companies as $company)
                            <li class="nav-item" role="presentation">
                                <a id="tab-one{{$company->name}}" data-bs-toggle="tab" href="#one{{$company->name}}" role="tab"
                                    aria-controls="one{{$company->name}}" aria-selected="true" @if ($i == 0)
                                    class="nav-link active" @else class="nav-link" @endif> {{$company->name}} Coy</a>
                            </li>
                            <?php    $i = +1; ?>
                        @endforeach
                    </ul>
                    <!-- Nav tabs end -->

                    <!-- Tab content start -->
                    <div class="tab-content h-300">
                        @for ($j = 0; $j < count($statistics); ++$j)
                            <div id="one{{$statistics[$j]['company_name']}}" @if ($j == 0) class="tab-pane fade show active" @else class="tab-pane fade" @endif
                                 role="tabpanel">
                                <!-- Row starts -->
                                <div class="row gx-4">
                                    <div class="col-sm-12 col-12">
                                        <div class="  mb-3">
                                            <div class="">
                                                <!-- Row starts -->
                                                <div class="row gx-4 mt-1">
                                                    <!-- Attendence starts -->
                                                    <div class="col-xxl-3 col-sm-6 col-12">
                                                        <div class="card mb-4">
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="p-3  me-3">
                                                                        <img src="/tps-smis/resources/assets/images/attendance.png"
                                                                            style="height:50 !important; width:50"
                                                                            alt="attendence image" />
                                                                    </div>
                                                                    <div class="p3 d-flex flex-column">
                                                                        <p class="m-0 ">Attended</p>
                                                                        <h2 class="lh-1 opacity-50">
                                                                            {{$statistics[$j]['statistics']['present']}}
                                                                        </h2>
                                                                    </div>
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-between mt-1">
                                                                        <a class="text-primary ms-4"
                                                                            href="{{url('/today/1/' . $page->id)}}">
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
                                                                        <img src="/tps-smis/resources/assets/images/bed.png"
                                                                            style="height:50 !important; width:50"
                                                                            alt="Sick image" />
                                                                    </div>
                                                                    <div class="p3 d-flex flex-column">
                                                                        <p class="m-0 ">Sick </p>
                                                                        <h2 class="lh-1 opacity-50">
                                                                            {{$statistics[$j]['statistics']['sick']}}
                                                                        </h2>
                                                                    </div>
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-between mt-1">
                                                                        <a class="text-primary ms-4"
                                                                            href="javascript:void(0);">
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
                                                                        <img src="/tps-smis/resources/assets/images/leave.png"
                                                                            style="height:50 !important; width:50"
                                                                            alt="Leave image" />
                                                                    </div>
                                                                    <div class="p3 d-flex flex-column">
                                                                        <p class="m-0 ">Safari </p>
                                                                        <h2 class="lh-1 opacity-50">
                                                                            {{$statistics[$j]['statistics']['safari']}}
                                                                        </h2>
                                                                    </div>
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-between mt-1">
                                                                        <a class="text-primary ms-4"
                                                                            href="javascript:void(0);">
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
                                                                        <img src="/tps-smis/resources/assets/images/prison.png"
                                                                            style="height:50 !important; width:50"
                                                                            alt="MPS image" />
                                                                    </div>
                                                                    <div class="p3 d-flex flex-column">
                                                                        <p class="m-0 ">MPS </p>
                                                                        <h2 class="lh-1 opacity-50">
                                                                            {{$statistics[$j]['statistics']['mps']}}
                                                                        </h2>
                                                                    </div>
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-between mt-1">
                                                                        <a class="text-primary ms-4"
                                                                            href="{{url("mps/HQ/company")}}">
                                                                            <span>View</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- MPS days  end. -->
                                                </div>
                                                <!-- Row ends -->

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Row ends -->

                            </div>
                        @endfor
                    </div>
                    <!-- Tab content end -->

                </div>
            </div>
        </div>
    </div>
</div>
@endsection