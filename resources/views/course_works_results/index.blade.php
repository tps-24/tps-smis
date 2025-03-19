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
@session('success')
        <div class="alert alert-success alert-dismissible " role="alert">
            {{ $value }}
        </div>
    @endsession
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
      <div class="pull-right">
        <span style="font-size:30px !important">Coursework Results</span>

        <button disabled id="add_btn" class="btn btn-success mb-2" style="float:right !important; margin-right:1%;">
        <a href="" id="add_link" style="color:white;"> <i class="fa fa-plus"></i> Add Course Results</a>
        </button>
        
        <button disabled id="ca_configuration_btn" class="btn btn-success mb-2" style="float:right !important; margin-right:1%;">
        <a href="" id="ca_configuration_link" style="color:white;"> <i class="fa fa-plus"></i> CA Configurations</a>
        </button>
      </div>
      </div>
      <div class="card-body">
      <div class="table-outer">
        <div class="table-responsive">
        <table class="table table-striped truncate m-0">
          <thead>
          <tr>
            <th>SNo</th>
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
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.course-link').forEach(function (link) {
      link.addEventListener('click', function (e) {
      e.preventDefault();
      const courseId = this.getAttribute('data-course-id');
      add_link = document.getElementById('add_link');
      add_btn = document.getElementById('add_btn');
      var uploadExplanationRoute = @json(route('coursework.upload_explanation', ['courseId' => 'COURSE_ID_PLACEHOLDER']));
      const newHref = uploadExplanationRoute.replace('COURSE_ID_PLACEHOLDER', courseId);
      add_link.setAttribute('href', newHref);
      add_btn.disabled = false;

      ca_configuration_link = document.getElementById('ca_configuration_link');
      ca_configuration_btn = document.getElementById('ca_configuration_btn');
      var ca_configuration_route = @json(route('coursework_results.index'));
      //const newHrefCAConfiguration = ca_configuration_link.replace('COURSE_ID_PLACEHOLDER', courseId);
      ca_configuration_link.setAttribute('href', ca_configuration_route);
      ca_configuration_btn.disabled = false;
      console.log(`Course ID clicked: ${courseId}`); // Log the course ID to the console
      fetchCourseworkResults(courseId);
      });
    });

    function fetchCourseworkResults(courseId, page = 1) {
      var i = 1;
      const basePath = '/tps-smis'; // Ensure this matches your base path
      console.log(`Fetching results for course ID: ${courseId}`); // Log before the fetch call
      fetch(`${basePath}/coursework_results/course/${courseId}?page=${page}`)
      .then(response => {
        console.log(`Response status: ${response.status}`); // Log response status
        if (!response.ok) {
        throw new Error(`Network response was not ok: ${response.statusText}`);
        }
        return response.json();
      })
      .then(data => {
        console.log('Data received:', data); // Log the received data
        const resultsContainer = document.getElementById('coursework-results');
        resultsContainer.innerHTML = '';

        if (data.data.length === 0) {
        resultsContainer.innerHTML = '<tr><td colspan="7">No results found for this course.</td></tr>';
        } else {
        data.data.forEach(result => {
          const row = document.createElement('tr');
          row.innerHTML = `
                <td>${i++}</td>
                <td>${result.student.first_name}</td>
                <td>${result.course.courseName}</td>
                <td>${result.coursework.coursework_title}</td>
                <td>${result.score}</td>
                <td>${result.semester.semester_name}</td>
                <td>
                  <a class="btn btn-info btn-sm" href="${basePath}/coursework_results/${result.id}"><i class="fa-solid fa-list"></i> Show</a>
                  <a class="btn btn-primary btn-sm" href="${basePath}/coursework_results/${result.id}/edit"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                  <form method="POST" action="${basePath}/coursework_results/${result.id}" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
                  </form>
                </td>
              `;
          resultsContainer.appendChild(row);
        });

        // Add pagination links
        const paginationContainer = document.createElement('div');
        paginationContainer.classList.add('pagination-container');
        paginationContainer.innerHTML = `
              <nav aria-label="Page navigation">
                <ul class="pagination">
                ${data.links.map(link => `
                  <li class="page-item ${link.active ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${link.url ? new URL(link.url).searchParams.get('page') : ''}">${link.label}</a>
                  </li>
                `).join('')}
                </ul>
              </nav>
            `;
        resultsContainer.appendChild(paginationContainer);

        // Add event listeners for pagination links
        document.querySelectorAll('.page-link').forEach(function (link) {
          link.addEventListener('click', function (e) {
          e.preventDefault();
          const page = this.getAttribute('data-page');
          if (page) {
            fetchCourseworkResults(courseId, page, i);
          }
          });
        });
        }
      })
      .catch(error => {
        console.error('Fetch error:', error); // Log any errors
        alert('An error occurred: ' + error.message); // Show error message to the user
      });
    }
    });

  </script>
@endsection