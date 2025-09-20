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
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Registered Courses for You</a></li>
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
                <div class="table-outer">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered truncate m-0">
                            <thead>
                                <tr>
                                    <th colspan="6">Semester One</th>
                                </tr>
                                <tr>
                                    <th scope="col" width="1%">No</th>
                                    <th scope="col" width="15%">Course Code</th>
                                    <th scope="col" width="50%">Course Name</th>
                                    <th scope="col" width="14%">Course Type</th>
                                    <th scope="col" width="10%">Credit Weight</th>
                                    <th scope="col" width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                    $nonOptionalCourses = $courses->filter(function ($course) {
                                        return $course->pivot->course_type != 'Optional' && $course->pivot->semester_id == 1;
                                    });
                                    $optionalCourses = $courses->filter(function ($course) {
                                        return $course->pivot->course_type === 'Optional' && $course->pivot->semester_id == 1;
                                    });
                                @endphp

                                @forelse ($nonOptionalCourses as $course)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $course->courseCode }}</td>
                                        <td>{{ $course->courseName }}</td>
                                        <td>{{ $course->pivot->course_type }}</td>
                                        <td>{{ $course->pivot->credit_weight }}</td>
                                        <td></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">There are no courses registered for this semester!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            
                            <!-- <tbody>
                                <tr>
                                    <th colspan="6">Optional Course(s)</th>
                                </tr>
                                @forelse ($optionalCourses as $course)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $course->courseCode }}</td>
                                        <td>{{ $course->courseName }}</td>
                                        <td>{{ $course->pivot->course_type }}</td>
                                        <td>{{ $course->pivot->credit_weight }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('assign-courses.destroy', $course->id) }}" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa-solid fa-trash"></i> Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">There are no optional courses registered for this semester!</td>
                                    </tr>
                                @endforelse
                            </tbody> -->
                        </table>
                    </div>
                </div>
                
                <div class="table-outer nd">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered truncate m-0">
                            <thead>
                                <tr>
                                    <th colspan="6">Semester Two</th>
                                </tr>
                                <tr>
                                    <th scope="col" width="1%">No</th>
                                    <th scope="col" width="15%">Course Code</th>
                                    <th scope="col" width="50%">Course Name</th>
                                    <th scope="col" width="14%">Course Type</th>
                                    <th scope="col" width="10%">Credit Weight</th>
                                    <th scope="col" width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                    $nonOptionalCourses = $courses->filter(function ($course) {
                                        return $course->pivot->course_type != 'Optional' && $course->pivot->semester_id == 2;
                                    });
                                    $optionalCourses = $courses->filter(function ($course) {
                                        return $course->pivot->course_type === 'Optional' && $course->pivot->semester_id ==2;
                                    });
                                @endphp

                                @forelse ($nonOptionalCourses as $course)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $course->courseCode }}</td>
                                        <td>{{ $course->courseName }}</td>
                                        <td>{{ $course->pivot->course_type }}</td>
                                        <td>{{ $course->pivot->credit_weight }}</td>
                                        <td></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">There are no courses registered for this semester!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            
                            <!-- <tbody>
                                <tr>
                                    <th colspan="6">Optional Course(s)</th>
                                </tr>
                                @forelse ($optionalCourses as $course)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $course->courseCode }}</td>
                                        <td>{{ $course->courseName }}</td>
                                        <td>{{ $course->pivot->course_type }}</td>
                                        <td>{{ $course->pivot->credit_weight }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('assign-courses.destroy', $course->id) }}" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa-solid fa-trash"></i> Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">There are no optional courses registered for this semester!</td>
                                    </tr>
                                @endforelse
                            </tbody> -->
                        </table>
                    </div>
                </div>
            </div>
       

        </div>
    </div>
</div>
<!-- Row ends -->
@endsection
