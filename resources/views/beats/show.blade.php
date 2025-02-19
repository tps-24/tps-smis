@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item "><a href="/tps-smis/beats">Beats</a></li>
                <li class="breadcrumb-item active"><a href="#">Guards </a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')


<div class="row gx-4">
    <div class="col-sm-12 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <!-- Custom tabs start -->
<<<<<<< HEAD

<div class="container">
    <h2>Beat Details</h2>
    <p><strong>Date:</strong> {{ $beat->date }}</p>
    <p><strong>Start Time:</strong> {{ $beat->start_at }}</p>
    <p><strong>End Time:</strong> {{ $beat->end_at }}</p>

    <h3>Assigned Students</h3>
    <ul>
        @foreach($students as $student)
            <li>{{ $student->first_name }} {{ $student->last_name }} (PLT {{ $student->platoon }})</li>
        @endforeach
    </ul>
</div>

=======
                <div class="custom-tabs-container">

                    <!-- Nav tabs start -->
                    <ul class="nav nav-tabs" id="customTab2" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="tab-oneA" data-bs-toggle="tab" href="#oneA" role="tab"
                                aria-controls="oneA" aria-selected="true"> Today</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="tab-twoA" data-bs-toggle="tab" href="#twoA" role="tab"
                                aria-controls="twoA" aria-selected="false">Tomorrow</a>
                        </li>
                    </ul>
                    <!-- Nav tabs end -->

                    <!-- Tab content start -->
                    <div class="tab-content h-300">
                        <div class="tab-pane fade show active" id="oneA" role="tabpanel">

                            <!-- Row starts -->
                            <div class="row gx-4">
                                <div class="col-sm-12 col-12">
                                    <div class="card border mb-3">
                                        <div class="card-body">
                                            <!-- Row starts -->
                                            @if ($todayBeats->isEmpty())

                                                <h5>No beats available.</h5>

                                            @else
                                                <div class="table-responsive">
                                                    <table class="table table-striped truncate m-0">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Name</th>
                                                                <th>Rank</th>
                                                                <th>Status</th>
                                                                <th>Round</th>
                                                                <th>Start</th>
                                                                <th>End</th>
                                                                <th width="280px">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php    $i = 0;?>
                                                            @foreach ($todayBeats as $beat)
                                                                <tr>
                                                                    <td>{{++$i}}</td>
                                                                    <td>{{$beat->student->first_name}}
                                                                        {{$beat->student->middle_name}}
                                                                        {{$beat->student->last_name}}
                                                                    </td>
                                                                    <td>{{$beat->student->rank}}</td>
                                                                    <td>
                                                                        @if ($beat->status == 1)
                                                                            Attended
                                                                        @elseif($beat->status == 0)
                                                                            Not Attended
                                                                        @else
                                                                            Not approved
                                                                        @endif
                                                                    </td>
                                                                    <td>{{$beat->round}}</td>
                                                                    <td>{{$beat->start_at}}</td>
                                                                    <td>{{$beat->end_at}}</td>
                                                                    <td>
                                                                        @if($beat->status != 1)
                                                                            <button class="btn btn-sm btn-info">View reason</button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            <!-- Row ends -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Row ends -->

                        </div>
                        <div class="tab-pane fade" id="twoA" role="tabpanel">

                            <!-- Row starts -->
                            <div class="row gx-4">
                                <div class="col-sm-12 col-12">
                                    <div class="card border mb-3">
                                        <div class="card-body">
                                            <!-- Row starts -->
                                            @if ($tomorowBeats->isEmpty())

                                                <h5>No beats available.</h5>

                                            @else
                                                <div class="table-responsive">
                                                    <table class="table table-striped truncate m-0">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Name</th>
                                                                <th>Rank</th>
                                                                <th>Status</th>
                                                                <th>Round</th>
                                                                <th>Start</th>
                                                                <th>End</th>
                                                                <th width="280px">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php    $i = 0;?>
                                                            @foreach ($tomorowBeats as $beat)
                                                                <tr>
                                                                    <td>{{++$i}}</td>
                                                                    <td>{{$beat->student->first_name}}
                                                                        {{$beat->student->middle_name}}
                                                                        {{$beat->student->last_name}}
                                                                    </td>
                                                                    <td>{{$beat->student->rank}}</td>
                                                                    <td>
                                                                        @if ($beat->status == 1)
                                                                            Attended
                                                                        @elseif($beat->status == 0)
                                                                            Not Attended
                                                                        @else
                                                                            Not approved
                                                                        @endif
                                                                    </td>
                                                                    <td>{{$beat->round}}</td>
                                                                    <td>{{$beat->start_at}}</td>
                                                                    <td>{{$beat->end_at}}</td>
                                                                    <td>
                                                                        @if($beat->status != 1)
                                                                            <button class="btn btn-sm btn-info">View reason</button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            <!-- Row ends -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Row ends -->

                        </div>

                    </div>
                    <!-- Tab content end -->

                </div>
>>>>>>> 7d61e4df868b37df109c9a8e92bdee3250c6fbd9
            </div>
        </div>
    </div>
</div>
@endsection