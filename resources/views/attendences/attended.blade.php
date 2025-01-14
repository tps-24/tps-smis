@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-rms/attendences/">Attendences</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Today</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection

@section('content')

<div class="table-responsive">
    <table class="table table-striped truncate m-0">
        <thead>
            <tr>
                <th>Platoon</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Sentry</th>
                <th>Excuse Duty</th>
                <th>Kazini</th>
                <th>Mess</th>
                <th>Sick</th>
                <th>Off</th>
                <th>Safari</th>
                <th>Total</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0;?>
            @foreach ($attendences as $key => $attendence)

                <tr>
                    <td>{{$attendence->platoon->company->name}} - {{$attendence->platoon->name}}</td>
                    <td>{{$attendence->present}}</td>
                    <td>{{$attendence->absent}}</td>
                    <td>{{$attendence->sentry}}</td>
                    <td>{{$attendence->excuse_duty}}</td>
                    <td>{{$attendence->kazini}}</td>
                    <td>{{$attendence->mess}}</td>
                    <td>{{$attendence->sick}}</td>
                    <td>{{$attendence->off}}</td>
                    <td>{{$attendence->safari}}</td>
                    <td>{{$attendence->total}}</td>
                    <td>
                        <a class="btn btn-info btn-sm" href="">More</a>
                        <a class="btn btn-warning btn-sm" href="">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection