@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                <li class="breadcrumb-item active"><a href="/tps-smis/attendences/">Attendences</a></li>
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
<h4>Attendances for {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}.</h4>
<div class="d-flex  justify-content-end">
    <a href="{{ route('attendences.generatePdf',['companyId'=>$company->id,'date'=>$date]) }}">
        <button title="Download report" class="btn btn-sm btn-success"><i class="gap 2 bi bi-download"></i> Report</button>
    </a>
</div>
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
                    <th>ME</th>
                    <th>KE</th>
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
                        <td>{{$attendence->male}}</td>
                        <td>{{$attendence->female}}</td>
                        <td>{{$attendence->total}}</td>
                        <td>
                            <button class="btn  btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#MoreAbsent{{$attendence->id}}">Absents</button>
                            <button class="btn  btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#MoreSafari{{$attendence->id}}">Safari</button>
                               <a href="{{ route('attendences.changanua',['attendenceId'=> $attendence->id]) }}"> <button class="btn  btn-info btn-sm" >More</button></a>
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
                                                href="{{url('attendences/list-absent_students/' . $company->id . '/' . $attendence->id.'/'.$date)}}"><button
                                                    class="btn btn-sm btn-primary">Add absents</button></a>
                                        
                                        </div>
                                    </div>
                              /div>
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
                                            <div class="d-flex gap-2">
                                                <label for="">Select category</label>
                                                <select style="width: 50%;" name="" id="" class="form-control">
                                                    <option value="" disabled selected>select</option>
                                                    <option value="">Mess</option>
                                                    <option value="">Sentry</option>
                                                    <option value="">Off</option>
                                                    <option value="">Safari</option>
                                                </select>
                                            </div>
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