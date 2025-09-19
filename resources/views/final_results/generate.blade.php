@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Final Results</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Final Results Lists</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
@include('layouts.sweet_alerts.index')
<!-- Row starts -->
<div class="row gx-4">
    <div class="col-sm-12">
        <div class="card mb-3">
            <div class="card-header">

            </div>
            <div class="pull-right">
                <!-- <a class="btn btn-success mb-2" href="{{ route('final_results.create') }}" style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Create New course</a> -->

                <form action="{{ route('final_results.generate.all') }}" method="GET" class="mb-3">
                    @csrf
                    <button type="submit" class="btn btn-primary"
                        style="float:right !important; margin-right:1%">Generate Final Results</button>
                </form>

                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
                @endif
            </div>

            <div class="row gx-4">
                <div class="col-sm-8 col-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Course</th>
                                        <th>Session Programme</th>
                                        <th width="250px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $i = 0;
                                    @endphp
                                    
                                        @foreach ($enrollments as $enrollment)
                                        <tr>
                                        <td>{{++$i}}.</td>
                                        <td>{{$enrollment->course->courseName}}</td>
                                        <td>{{$enrollment->sessionProgramme->session_programme_name}}</td>
                                        <td>
                                            <form id="generateResultsForm{{$enrollment->id}}"
                                                action="{{route('final_results.session.generate',$enrollment->session_programme_id)}}"
                                                method="post">
                                                @csrf
                                                <input type="text" value="{{$enrollment->course->id}}" name="course_id" id="" hidden>
                                                
                                                <button type="button"
                                                    onclick="confirmAction('generateResultsForm{{$enrollment->id}}', 'Generate Results',' results for {{$enrollment->sessionProgramme->session_programme_name}}','Generate')"
                                                    class="btn btn-sm btn-primary">Generate</button>
                                            </form>

                                        </td>
                                      </tr>
                                        @endforeach
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-12">
                    <div class="card mb-4">
                        <div class="card-body">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-outer">
                    <div class="table-responsive">
                        <table class="table table-striped truncate m-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>Semester</th>
                                    <th>Course</th>
                                    <th>Total Score</th>
                                    <th>Grade</th>
                                    <th>Actions</th>

                                    <!-- <tr>
                      <th scope="col">No</th>
                      <th scope="col">Course Name</th>
                      <th scope="col">Course Code</th>
                      <th scope="col">Department</th>
                      <th scope="col" width="280px">Actions</th>
                  </tr> -->
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i=1;
                                @endphp

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Row ends -->
@endsection