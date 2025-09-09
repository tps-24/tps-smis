@extends('layouts.main')

@section('scrumb')
<!-- Breadcrumb Navigation -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Course Work</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Coursework (CW) Assessment types for {{  $course->courseName }}</a></li>
      </ol>
    </nav>
  </div>
</nav>
@endsection

@section('content')
<!-- Main Content -->
 
@session('success')
<div class="alert alert-success alert-dismissible " role="alert">
    {{ $value }}
</div>
@endsession
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row gx-4">
    <div class="col-sm-12 col-12">
        <div class="card mb-4">            
            <div class="card-header">
                <div class="pull-right">
                    <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('coursework_results.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
                </div>

                <div class="mt-1">
                <p>&nbspHere you can configure course result assessment types. If you encounter any issues, feel free to contact support.</p>
                </div>
                
                <div class="pull-right" style="float:right !important;">
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCourseworkModal">
                        <i class="fa fa-plus"></i> Add Exam
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($course->semesterExams->isNotEmpty())
                
                <div class="table-outer">
                    <div class="table-responsive">
                        <table class="table table-striped truncate m-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Coursework Title</th>
                                    <th>Max Score</th>
                                    <th>Date</th>
                                    <th scope="col" width="280px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                  <tr>
                                      <td>1</td>
                                      <td>{{ $course->courseName }}</td>
                                      <td>{{ $course->semesterExams[0]->max_score }}</td>
                                      <td>{{ $course->semesterExams[0]->exam_date }}</td>
                                      <td>
                                          <a class="btn btn-info btn-sm" href="{{ route('course_works.show', $course->id) }}">
                                              <i class="fa-solid fa-list"></i> Show
                                          </a>
                                          <a class="btn btn-primary btn-sm" href="{{ route('course_works.edit', $course->id) }}">
                                              <i class="fa-solid fa-pen-to-square"></i> Edit
                                          </a>
                                          <form method="POST" action="{{ route('course_works.destroy', $course->id) }}" style="display:inline">
                                              @csrf
                                              @method('DELETE')
                                              <button type="submit" class="btn btn-danger btn-sm">
                                                  <i class="fa-solid fa-trash"></i> Delete
                                              </button>
                                          </form>
                                      </td>
                                  </tr>
                          </tbody>

                        </table>
                    </div>
                </div>@else
                No Course exam configured
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addCourseworkModal" tabindex="-1" aria-labelledby="addCourseworkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCourseworkModalLabel">Add Course Exam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('course.exam_result.store', ['courseId' => $course->id]) }}">
                    @csrf
                
                    <div class="form-group">
                        <strong>Max Score:</strong>
                        <input type="number" name="max_score" placeholder="Enter max score" class="form-control">
                    </div>
                    <div class="form-group">
                        <strong>Date:</strong>
                        <input type="date" name="exam_date" placeholder="Enter  Date" class="form-control">
                    </div>
                    <input type="number" name="created_by" value="{{ Auth::user()->id }}" hidden>
                    <div class="text-center mt-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-floppy-disk"></i> Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
