@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Course Work Results</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Course Work Results Lists</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
 
@endsection
@section('content')
<!-- Row starts -->
<div class="row gx-4">
  
</div>

<!-- Row starts -->
<div class="row gx-4">
  <!-- Left section starts-->
  <div class="col-sm-4">
    <div class="card mb-3">
      <div class="card-header">
        <span>Choose the course below to view the coursework results <br> Just click the course below!</span>
      </div>
      <div class="card-body">
        @foreach ($courses as $course)
            <ul>
              <li><a href="#" class="course-link" data-course-id="{{ $course->id }}"> {{ $course->courseName }}</a></li>
            </ul>
        @endforeach
      </div>
    </div>
  </div>
  <!-- Left section ends-->

  <!-- Right section starts-->
  <div class="col-sm-8">
    <div class="card mb-3">
      <div class="card-header">
          
          <div class="pull-right" >
              <span style="font-size:30px !important">Coursework Results</span>
              <a class="btn btn-success mb-2" href="{{ route('coursework_results.create', $programme->id ) }}" style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Create New course</a>
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
                  <th>Course</th>
                  <th>Coursework</th>
                  <th>Score</th>
                  <th>Semester</th>
                  <th scope="col" width="280px">Actions</th>
                </tr>
              </thead>
              <tbody id="coursework-results">
                <!-- Coursework results will be loaded here -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Right section ends-->
</div>
<!-- Row ends -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.course-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const courseId = this.getAttribute('data-course-id');
            fetchCourseworkResults(courseId);
        });
    });

    function fetchCourseworkResults(courseId) {
        fetch(`/coursework_results/${courseId}`)
            .then(response => response.json())
            .then(data => {
                const resultsContainer = document.getElementById('coursework-results');
                resultsContainer.innerHTML = '';
                data.forEach(result => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${result.id}</td>
                        <td>${result.student.first_name}</td>
                        <td>${result.course.courseName}</td>
                        <td>${result.coursework.coursework_title}</td>
                        <td>${result.score}</td>
                        <td>${result.semester.semester_name}</td>
                        <td>
                            <a class="btn btn-info btn-sm" href="/coursework_results/${result.id}"><i class="fa-solid fa-list"></i> Show</a>
                            <a class="btn btn-primary btn-sm" href="/coursework_results/${result.id}/edit"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                            <form method="POST" action="/coursework_results/${result.id}" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    `;
                    resultsContainer.appendChild(row);
                });
            });
    }
});
</script>
@endsection






Route::get('/coursework_results/{course}', 'CourseworkResultsController@getResultsByCourse');



public function getResultsByCourse($courseId)
{
    $results = CourseworkResult::where('course_id', $courseId)->with(['student', 'course', 'coursework', 'semester'])->get();
    return response()->json($results);
}
