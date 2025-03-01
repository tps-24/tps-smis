@extends('layouts.main')

@section('content')
<!-- Row starts -->
<div class="row gx-4">
    <div class="col-xxl-3 col-sm-6 col-12">
    <div class="card mb-4">
        <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="p-1 border border-success rounded-circle me-3">
            <div id="radial2"></div>
            </div>
            <div class="d-flex flex-column">
            <h2 class="lh-1">{{count($staffs)}}</h2>
            <p class="m-0 opacity-50">Staffs Present</p>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-1">
            <a class="text-primary" href="{{ route('staffs.index') }}">
            <span>View All</span>
            <i class="bi bi-arrow-right ms-2"></i>
            </a>
            <div class="text-end">
            <p class="mb-0 text-success">100%</p>
            <span class="badge bg-success-subtle text-success small">Today</span>
            </div>
        </div>
        </div>
    </div>
    </div>
    <div class="col-xxl-3 col-sm-6 col-12">
    <div class="card mb-4">
        <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="p-1 border border-primary rounded-circle me-3">
            <div id="radial1"></div>
            </div>
            <div class="d-flex flex-column">
            <h2 class="lh-1">{{ count($denttotal) }}</h2>
            <p class="m-0 opacity-50">Students Present</p>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-1">
            <a class="text-primary" href="/tps-smis/students">
            <span>View All</span>
            <i class="bi bi-arrow-right ms-2"></i>
            </a>
            <div class="text-end">
            <p class="mb-0 text-primary">96%</p>
            <span class="badge bg-primary-subtle text-primary small">Today</span>
            </div>
        </div>
        </div>
    </div>
    </div>
    <div class="col-xxl-3 col-sm-6 col-12">
    <div class="card mb-4">
        <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="p-1 border border-info rounded-circle me-3">
            <div id="radial3"></div>
            </div>
            <div class="d-flex flex-column">
            <h2 class="lh-1">{{ count($patients) }}</h2>
            <p class="m-0 opacity-50">Sick Students (ED)</p>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-1">
            <a class="text-primary" href="{{ route('hospital.index') }}">
            <span>View All</span>
            <i class="bi bi-arrow-right ms-2"></i>
            </a>
            <div class="text-end">
            <p class="mb-0 text-info">0.0%</p>
            <span class="badge bg-info-subtle text-info small">Today</span>
            </div>
        </div>
        </div>
    </div>
    </div>
    <div class="col-xxl-3 col-sm-6 col-12">
    <div class="card mb-4 bg-primary">
        <div class="card-body text-white">
        <div class="d-flex align-items-center">
            <div class="p-1 border border-white rounded-circle me-3">
            <div id="radial4"></div>
            </div>
            <div class="d-flex flex-column">
            <h2 class="m-0 lh-1">{{ count($beats) }}</h2>
            <p class="m-0 opacity-50">Guards & Patrols</p>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-1">
            <a class="text-white" href="{{url('beats')}}">
            <span>View All</span>
            <i class="bi bi-arrow-right ms-2"></i>
            </a>
            <div class="text-end">
            <p class="mb-0 text-warning">2.4%</p>
            <span class="badge bg-danger text-white small">Today</span>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
<!-- Row ends -->

<!-- Row starts -->
<div class="row gx-4">
    <div class="col-xxl-6 col-sm-12 col-12">
    <div class="card mb-4 card-height-420">
        <div class="card-header">
        <h5 class="card-title">Grouped Bar Graph</h5>
        </div>
        <div class="card-body">

        <div class="graph-body auto-align-graph">
            <!-- <div id="orders"></div> -->
            <div id="sales"></div>
        </div>

        </div>
    </div>
    </div>
    <div class="col-xxl-3 col-sm-6 col-12">
    <div class="card mb-4 card-height-420">
        <div class="card-header">
        <h5 class="card-title">Events per Coy</h5>
        </div>
        <div class="card-body">

        <div class="d-flex flex-column justify-content-between h-100">

            <!-- Transactions starts -->
            <div class="d-flex flex-column gap-3">
            <div class="d-flex pb-3 border-bottom w-100">
                <div class="icon-box lg bg-primary-subtle rounded-5 me-3">
                <i class="bi bi-twittr fs-3 text-primary"></i>
                </div>
                <div class="d-flex flex-column">
                <p class="mb-1 opacity-50">Blaah Blaah </p>
                <h3 class="m-0 lh-1 fw-semibold">159</h3>
                </div>
            </div>
            <div class="d-flex pb-3 border-bottom w-100">
                <div class="icon-box lg bg-info-subtle rounded-5 me-3">
                <i class="bi bi-xbx fs-3 text-info"></i>
                </div>
                <div class="d-flex flex-column">
                <p class="mb-1 opacity-50">Blaah Blaah</p>
                <h3 class="m-0 lh-1 fw-semibold">36</h3>
                </div>
            </div>
            <div class="d-flex pb-3 border-bottom w-100">
                <div class="icon-box lg bg-danger-subtle rounded-5 me-3">
                <i class="bi bi-youtbe fs-3 text-danger"></i>
                </div>
                <div class="d-flex flex-column">
                <p class="mb-1 opacity-50">Blaah Blaah</p>
                <h3 class="m-0 lh-1 fw-semibold">23</h3>
                </div>
            </div>
            </div>
            <!-- Transactions ends -->

            <a href="javascript:void(0)" class="btn btn-dark">View All <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>

        </div>
    </div>
    </div>  <div class="col-xxl-3 col-sm-6 col-12">
    <div class="card mb-4 card-height-420">
        <div class="card-header">
        <h5 class="card-title">Events per Coy</h5>
        </div>
        <div class="card-body">

        <div class="d-flex flex-column justify-content-between h-100">

            <!-- Transactions starts -->
            <div class="d-flex flex-column gap-3">
            <div class="d-flex pb-3 border-bottom w-100">
                <div class="icon-box lg bg-primary-subtle rounded-5 me-3">
                <i class="bi bi-twittr fs-3 text-primary"></i>
                </div>
                <div class="d-flex flex-column">
                <p class="mb-1 opacity-50">Blaah Blaah </p>
                <h3 class="m-0 lh-1 fw-semibold">159</h3>
                </div>
            </div>
            <div class="d-flex pb-3 border-bottom w-100">
                <div class="icon-box lg bg-info-subtle rounded-5 me-3">
                <i class="bi bi-xbx fs-3 text-info"></i>
                </div>
                <div class="d-flex flex-column">
                <p class="mb-1 opacity-50">Blaah Blaah</p>
                <h3 class="m-0 lh-1 fw-semibold">36</h3>
                </div>
            </div>
            <div class="d-flex pb-3 border-bottom w-100">
                <div class="icon-box lg bg-danger-subtle rounded-5 me-3">
                <i class="bi bi-youtbe fs-3 text-danger"></i>
                </div>
                <div class="d-flex flex-column">
                <p class="mb-1 opacity-50">Blaah Blaah</p>
                <h3 class="m-0 lh-1 fw-semibold">23</h3>
                </div>
            </div>
            </div>
            <!-- Transactions ends -->

            <a href="javascript:void(0)" class="btn btn-dark">View All <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>

        </div>
    </div>
    </div>
</div>
<!-- Row ends -->

<!-- Row starts -->
<div class="row gx-4" >
    <div class="col-xxl-12">
    <div class="card" style="height: 150px !important">
        <div class="card-body">
        <!-- <div class="table-outer">
            <div class="table-responsive"> -->
            <!-- <table class="table align-middle truncate">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Purchase Date</th>
                    <th>Distribution</th>
                    <th>Clicks</th>
                    <th>Rating</th>
                    <th>Purchases</th>
                    <th>Views</th>
                    <th>Engagement</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                    <div class="d-flex flex-row align-items-center">
                        <img src="assets/images/thumbs/img7.jpg" class="img-5x rounded-2"
                        alt="Google Admin" />
                        <div class="d-flex flex-column ms-3">
                        <p class="m-0">Apple iPhone 15</p>
                        </div>
                    </div>
                    </td>
                    <td><span class="badge bg-success-subtle text-success">10/10/2023</span></td>
                    <td>
                    <span class="badge bg-danger-subtle text-danger"><i
                        class="bi bi-caret-up-fill"></i>9.5x</span>
                    </td>
                    <td>
                    <span class="badge bg-primary-subtle text-primary me-2">8000</span>
                    </td>
                    <td>
                    <div class="readonly5 rating-stars"></div>
                    </td>
                    <td>
                    <div id="orders1"></div>
                    </td>
                    <td>
                    <span class="badge bg-primary-subtle text-primary">17</span>
                    </td>
                    <td>
                    <span class="badge bg-danger-subtle text-danger"><i class="bi bi-caret-down-fill"></i>
                        13.5%</span>
                    </td>
                </tr>
                <tr>
                    <td>
                    <div class="d-flex flex-row align-items-center">
                        <img src="assets/images/thumbs/img10.jpg" class="img-5x rounded-2"
                        alt="Google Admin" />
                        <div class="d-flex flex-column ms-3">
                        <p class="m-0">Apple iPhone 16</p>
                        </div>
                    </div>
                    </td>
                    <td><span class="badge bg-success-subtle text-success">12/12/2023</span></td>
                    <td>
                    <span class="badge bg-danger-subtle text-danger"><i
                        class="bi bi-caret-up-fill"></i>8.8x</span>
                    </td>
                    <td>
                    <span class="badge bg-primary-subtle text-primary me-2">9000</span>
                    </td>
                    <td>
                    <div class="readonly5 rating-stars"></div>
                    </td>
                    <td>
                    <div id="orders2"></div>
                    </td>
                    <td>
                    <span class="badge bg-primary-subtle text-primary">38</span>
                    </td>
                    <td>
                    <span class="badge bg-danger-subtle text-danger"><i class="bi bi-caret-down-fill"></i>
                        18.9%</span>
                    </td>
                </tr>
                </tbody>
            </table> -->
            <!-- </div>
        </div> -->
        </div>
    </div>
    </div>
</div>
<!-- Row ends -->
@endsection