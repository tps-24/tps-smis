@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Course Work</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Course Work (CW) Lists</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
 
@endsection
@section('content')
<!-- Row starts -->
 
<div class="container">
    
      <div class="pull-right" >
          <a class="btn btn-success mb-2" href="{{ route('course.coursework.create',['courseId'=>$course->id]) }}" style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Create New course</a>
      </div>
      <div class="card-body">
        <div class="table-outer">
          <div class="table-responsive">
              <table class="table table-striped truncate m-0">
                  <thead>
                  <tr>
                    <th></th>
                    <th>Assessment Type</th>
                    <th>Coursework Title</th>
                    <th>Max Score</th>
                    <th>Due Date</th>
                    <th scope="col" width="280px">Actions</th>
                  </tr>
                  @php
                    $i = 0;
                  @endphp
                    @foreach ($course->courseWorks as $courseWork)
                    <tr>
                      <td>{{ ++$i }}.</td>
                        <td>{{ $courseWork->AssessmentType->type_name }}</td>
                        <td>{{ $courseWork->coursework_title }}</td>
                        <td>{{ $courseWork->max_score }}</td>
                        <td>{{ $courseWork->due_date }}</td>
                        <td>
                            <a class="btn btn-info btn-sm" href="{{ route('course_works.show',$courseWork->id) }}"><i class="fa-solid fa-list"></i> Show</a>
                            <a class="btn btn-primary btn-sm" href="{{ route('course_works.edit',$courseWork->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                              <form method="POST" action="{{ route('course_works.destroy', $courseWork->id) }}" style="display:inline">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
                              </form>
                        </td>
                    </tr>
                    @endforeach
              </table>
          </div>
        </div>
      </div>
    </div>

<!-- Row ends -->
@endsection
