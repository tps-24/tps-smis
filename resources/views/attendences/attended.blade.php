@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-smis/attendences/">Attendences</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Today Attendence</a>
                </li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection

@section('content')
@if (count($attendences) == 0)
    <h1>No attendence recorded today.</h1>
@else
    <div class="table-responsive">
        <table class="table table-striped truncate m-0">
            <thead>
                <tr>
                    <th>Platoon</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Sentry</th>
                    <th>Mess</th>
                    <th>Off</th>
                    <th>Safari</th>
                    <th>Total</th>
                    <th width="280px">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php    $i = 0;?>
                @foreach ($attendences as $key => $attendence)
                    <tr>
                        <td>{{$attendence->platoon->company->name}} - {{$attendence->platoon->name}}</td>
                        <td>{{$attendence->present}}</td>
                        <td>{{$attendence->absent}}</td>
                        <td>{{$attendence->sentry}}</td>
                        <td>{{$attendence->mess}}</td>
                        <td>{{$attendence->off}}</td>
                        <td>{{$attendence->safari}}</td>
                        <td>{{$attendence->total}}</td>
                        <td>
                            <button class="btn  btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#MoreAbsent{{$attendence->id}}">Absents</button>
                            <button class="btn  btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#MoreSafari{{$attendence->id}}">Safari</button>
                            <div class="modal fade" id="MoreAbsent{{$attendence->id}}" tabindex="-1"
                                aria-labelledby="statusModalLabelMore{{$attendence->id}}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="statusModalLabelMore">
                                                Absent Students
                                            </h5>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if (count($attendence->absent_students) < 1)
                                                <p>No absent students recorded</p>
                                            @endif
                                            <ol>
                                                @foreach($attendence->absent_students as $student)
                                                    @if ($student != NULL)
                                                        <li>{{$student->first_name}} {{$student->middle_name}} {{$student->last_name}}
                                                        </li>
                                                    @endif

                                                @endforeach
                                            </ol>

                                        </div>
                                        <div class="modal-footer">
                                            <a
                                                href="{{url('attendences/list-absent_students/' . $company->id . '/' . $attendence->id)}}"><button
                                                    class="btn btn-sm btn-primary">Add absents</button></a>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="MoreSafari{{$attendence->id}}" tabindex="-1"
                                aria-labelledby="statusModalLabelMore{{$attendence->id}}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="statusModalLabelMore">
                                                Safari Students
                                            </h5>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <ol>
                                                @foreach($attendence->safari_students as $student)
                                                    @if ($student != null)
                                                        <li>{{$student->first_name}} {{$student->middle_name}} {{$student->last_name}}
                                                        </li>
                                                    @else
                                                        <p>No safari students recorded.</p>
                                                    @endif
                                                @endforeach
                                            </ol>

                                        </div>
                                        <div class="modal-footer">
                                            <a
                                                href="{{url('attendences/list-safari_students/' . $company->id . '/' . $attendence->id)}}"><button
                                                    class="btn btn-sm btn-primary">Add safari</button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection