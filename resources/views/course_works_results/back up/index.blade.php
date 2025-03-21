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

<div class="row gx-4">
    <div class="col-sm-4">
        <div class="card mb-3">
            <div class="card-header">
                <!-- Semester Tabs -->
                <ul class="nav nav-tabs" id="semesterTabs" role="tablist">
                    @foreach ($semesters as $key => $semester)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $key == 0 ? 'active' : '' }}" id="tab-{{ $semester->id }}"
                            data-bs-toggle="tab" data-bs-target="#semester-{{ $semester->id }}" type="button" role="tab"
                            aria-controls="semester-{{ $semester->id }}"
                            aria-selected="{{ $key == 0 ? 'true' : 'false' }}">
                            {{ $semester->semester_name }}
                        </button>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="card-body">
                <!-- Tab Content for Semesters -->
                <div class="tab-content" id="semesterTabsContent">
                    @foreach ($semesters as $key => $semester)
                    <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="semester-{{ $semester->id }}"
                        role="tabpanel" aria-labelledby="tab-{{ $semester->id }}">
                        <h5>Courses for {{ $semester->semester_name }}</h5>
                        @if ($semester->courses->isNotEmpty())
                        <ul>
                            @foreach ($semester->courses as $course)
                            <li>
                                <a href="#" class="course-link" data-course-id="{{ $course->id }}">
                                    {{ $course->courseName }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <p>No courses available for this semester.</p>
                        @endif
                    </div>
                    @endforeach
                </div>

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
                    <!-- <h6>Here display the course choosen</h6> -->

                    <button disabled id="add_btn" class="btn btn-success mb-2"
                        style="float:right !important; margin-right:1%;">
                        <a href="" id="add_link" style="color:white;"> <i class="fa fa-plus"></i> Add Course Results</a>
                    </button>

                    <button disabled id="ca_configuration_btn" class="btn btn-success mb-2"
                        style="float:right !important; margin-right:1%;">
                        <a href="" id="ca_configuration_link" style="color:white;"> <i class="fa fa-plus"></i> CA
                            Configurations</a>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-outer">

                <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr id="coursework-headings">
                <!-- Dynamic headings will load here -->
            </tr>
        </thead>
        <tbody id="coursework-results">
            <!-- Dynamic results will load here -->
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="d-flex justify-content-end mt-3" id="pagination-container">
    <!-- Pagination links will dynamically load here -->
</div>






                </div>
            </div>
        </div>
    </div>
    <!-- Right section ends-->
</div>
<!-- Row ends -->
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Attach event listeners for all course links
    document.querySelectorAll('.course-link').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            // Get the course ID
            const courseId = this.getAttribute('data-course-id');

            // Enable buttons and update their links dynamically
            const addLink = document.getElementById('add_link');
            const addButton = document.getElementById('add_btn');
            const uploadExplanationRoute = @json(route('coursework.upload_explanation', ['courseId' => 'COURSE_ID_PLACEHOLDER']));
            addLink.setAttribute('href', uploadExplanationRoute.replace('COURSE_ID_PLACEHOLDER', courseId));
            addButton.disabled = false;

            const caConfigurationLink = document.getElementById('ca_configuration_link');
            const caConfigurationButton = document.getElementById('ca_configuration_btn');
            const caConfigurationRoute = @json(route('course.coursework', ['courseId' => 'COURSE_ID_PLACEHOLDER']));
            caConfigurationLink.setAttribute('href', caConfigurationRoute.replace('COURSE_ID_PLACEHOLDER', courseId));
            caConfigurationButton.disabled = false;

            console.log(`Selected Course ID: ${courseId}`);

            // Fetch and display results for the selected course
            fetchCourseworkResults(courseId);
        });
    });

    // Function to fetch and render coursework results
    function fetchCourseworkResults(courseId, page = 1) {
    const apiUrl = `/tps-smis/coursework_results/course/${courseId}?page=${page}`;
    const headingsContainer = document.getElementById('coursework-headings');
    const resultsContainer = document.getElementById('coursework-results');
    const paginationContainer = document.getElementById('pagination-container');

    // Check if necessary elements exist
    if (!headingsContainer || !resultsContainer || !paginationContainer) {
        console.error('Error: Necessary DOM elements are missing');
        return;
    }

    fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Clear previous content
            headingsContainer.innerHTML = `
                <th>#</th>
                <th>Force Number</th>
                <th>Student Name</th>
            `;
            resultsContainer.innerHTML = '';

            // Add dynamic coursework headings
            data.courseworks.forEach(coursework => {
                const heading = document.createElement('th');
                heading.style.textAlign = 'center';
                heading.innerText = coursework.coursework_title;
                headingsContainer.appendChild(heading);
            });

            // Add "Total CW" heading
            const totalHeading = document.createElement('th');
            totalHeading.style.textAlign = 'center';
            totalHeading.innerText = 'Total CW';
            headingsContainer.appendChild(totalHeading);

            // **Add Actions heading**
            const actionsHeading = document.createElement('th');
            actionsHeading.style.textAlign = 'center';
            actionsHeading.innerText = 'Actions';
            headingsContainer.appendChild(actionsHeading);

            // Populate table rows with results
            let rowIndex = 1;
            Object.entries(data.results).forEach(([studentId, studentResult]) => {
                const row = document.createElement('tr');

                // Add initial columns (student details)
                row.innerHTML = `
                    <td style="text-align: center;">${rowIndex++}</td>
                    <td style="text-align: center;">${result.student.force_number}</td>
                    <td>${result.student.first_name} ${result.student.middle_name ?? ''} ${result.student.last_name}</td>
                `;

                // Add scores for each coursework heading
                data.courseworks.forEach(coursework => {
                    const score = studentResult.scores[coursework.id] || '-';
                    row.innerHTML += `<td style="text-align: center;">${score}</td>`;
                });

                // Add "Total CW" column
                row.innerHTML += `<td style="text-align: center;">${studentResult.total_cw}</td>`;

                // **Add Actions column**
                row.innerHTML += `
                    <td style="text-align: center;">
                        <button class="btn btn-info btn-sm">View</button>
                        <button class="btn btn-primary btn-sm">Edit</button>
                    </td>
                `;

                resultsContainer.appendChild(row);
            });

            // Add pagination links dynamically
            paginationContainer.innerHTML = `
                <ul class="pagination justify-content-end">
                    ${data.results.links.map(link => `
                        <li class="page-item ${link.active ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${new URL(link.url).searchParams.get('page')}">
                                ${link.label}
                            </a>
                        </li>
                    `).join('')}
                </ul>
            `;

            // Attach event listeners for pagination links
            document.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    if (page) fetchCourseworkResults(courseId, page);
                });
            });
        })
        .catch(error => {
            console.error('Error fetching results:', error);

            // Display fallback message in the table
            resultsContainer.innerHTML = `
                <tr>
                    <td colspan="7" class="text-danger text-center">Failed to load results. Please try again later.</td>
                </tr>
            `;
        });
}

});


</script>
@endsection