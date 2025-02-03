@extends('layouts.main')

@section('style')
<style>
    .breadcrumb {
        display: flex;
        width: 100%;
    }
    .breadcrumb-item {
        display: flex;
        align-items: center;
    }
    #date {
        position: absolute;
        bottom: 10px; /* Adjust as needed */
        right: 15px;  /* Adjust as needed */
    }
</style>
@endsection
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Courses</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Coursework Results</a></li>
                <li class="breadcrumb-item right-align"><a href="#" id="date">{{ now()->format('l jS \\o\\f F, Y') }}</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('style')
<style>
    .table-outer {
        overflow-x: auto;
    }
    .table thead th, .table tbody td {
        border: 1px solid #dee2e6;
    }
    .table tbody tr:last-child td {
        border-bottom: 1px solid #dee2e6;
    }

    .nd{
        margin-top:20px;
    }
</style>
@endsection
@section('content')
<!-- Row starts -->
<div class="row gx-4">
    <div class="col-sm-12">
        <div class="card mb-3">
            <div class="card-header">
                @if (session('success'))
                <div class="alert alert-success">
                    <p>{{ session('success') }}</p>
                </div>
                @endif
            </div>
                @php
                $i=1;
                $j=1;
                @endphp
            <div class="card-body">
                
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                    @foreach($groupedBySemester as $semesterId => $results)
                        <li class="nav-item">
                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="semester-{{ $semesterId }}-tab" data-toggle="tab" href="#semester-{{ $semesterId }}" role="tab" aria-controls="semester-{{ $semesterId }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                 {{ $results->first()->semester->semester_name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content" id="myTabContent">
                    @foreach($groupedBySemester as $semesterId => $results)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="semester-{{ $semesterId }}" role="tabpanel" aria-labelledby="semester-{{ $semesterId }}-tab">
                            <div class="table-outer mt-3">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered truncate m-0">
                                        <thead>
                                            <tr>
                                                <th scope="col" width="1%">S/N</th>
                                                <th scope="col" width="15%">Course Code</th>
                                                <th scope="col" width="50%">Course Name</th>
                                                <th scope="col" width="10%">Credits</th>
                                                <th scope="col" width="7%">Score</th>
                                                <th scope="col" width="10%">Remarks</th>
                                                <th scope="col" width="7%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                            @endphp

                                            @foreach($results as $result)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $result->course->courseCode }}</td>
                                                    <td>{{ $result->course->courseName }}</td>
                                                    <td>{{ $result->programmeCourseSemester->credit_weight }}</td>
                                                    <td>{{ $result->score }}</td>
                                                    <td><?php if($result->score < 16){ ?> <span style="color:red">Fail</span> <?php }else{ echo "Pass"; }?> </td>
                                                    
                                                    <td>
                                                        <a href="{{ route('coursework.summary', $result->id) }}" class="btn btn-info btn-sm">
                                                            <i class="fa-solid fa-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>



            </div>
       

        </div>
    </div>
</div>
<!-- Row ends -->
@endsection