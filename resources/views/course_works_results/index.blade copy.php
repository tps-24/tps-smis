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

            // Fetch results for the selected course
            fetchCourseworkResults(courseId);
        });
    });

    // Function to fetch and display coursework results
    function fetchCourseworkResults(courseId, page = 1) {
        const headingsContainer = document.getElementById('coursework-headings');
        const resultsContainer = document.getElementById('coursework-results');
        const paginationContainer = document.getElementById('pagination-container');
        const loader = document.getElementById('loading-indicator'); // Optional loader for UX
        const basePath = '/tps-smis'; // Adjust base path if necessary

        console.log(`Fetching results for Course ID: ${courseId} (Page: ${page})`);

        // Show loader while fetching data
        if (loader) loader.style.display = 'block';

        fetch(`${basePath}/coursework_results/course/${courseId}?page=${page}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                // Clear previous headings, results, and pagination
                headingsContainer.innerHTML = `
                    <th style="width: 5%; text-align: center;">#</th>
                    <th style="width: 25%; text-align: center;">Force Number</th>
                    <th style="width: 25%; text-align: center;">Student Name</th>
                `;
                resultsContainer.innerHTML = '';
                paginationContainer.innerHTML = '';

                // Add dynamic coursework headings
                data.courseworks.forEach(coursework => {
                    const heading = document.createElement('th');
                    heading.style.textAlign = 'center';
                    heading.innerText = coursework.coursework_title;
                    headingsContainer.appendChild(heading);
                });

                // Add Actions column
                const actionsHeading = document.createElement('th');
                actionsHeading.style.textAlign = 'center';
                actionsHeading.style.width = '30%';
                actionsHeading.innerText = 'Actions';
                headingsContainer.appendChild(actionsHeading);

                // Populate table rows with results
                let i = 1;
                data.results.data.forEach(result => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td style="text-align: center;">${i++}</td>
                        <td style="text-align: left;">${result.student.force_number ?? 'N/A'}</td>
                        <td>${result.student.first_name} ${result.student.middle_name ?? ''} ${result.student.last_name}</td>
                        ${data.courseworks.map(coursework => {
                            // Map coursework title to result fields
                            const fieldName = coursework.coursework_title.toLowerCase().replace(/ /g, '_');
                            return `
                                <td style="text-align: center;">
                                    ${result[fieldName] ?? '-'}
                                </td>`;
                        }).join('')}
                        <td style="text-align: center;">
                            <div class="btn-group">
                                <a class="btn btn-info btn-sm" href="${basePath}/coursework_results/${result.id}"><i class="fa fa-eye"></i> Show</a>
                                <a class="btn btn-primary btn-sm" href="${basePath}/coursework_results/${result.id}/edit"><i class="fa fa-edit"></i> Edit</a>
                                <form method="POST" action="${basePath}/coursework_results/${result.id}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                </form>
                            </div>
                        </td>`;
                    resultsContainer.appendChild(row);
                });

                // Build and display pagination links
                const pagination = document.createElement('nav');
                pagination.innerHTML = `
                    <ul class="pagination justify-content-end">
                        ${data.results.links.map(link => `
                            <li class="page-item ${link.active ? 'active' : ''}">
                                <a class="page-link" href="#" data-page="${link.url ? new URL(link.url).searchParams.get('page') : ''}">
                                    ${link.label}
                                </a>
                            </li>
                        `).join('')}
                    </ul>`;
                paginationContainer.appendChild(pagination);

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
                resultsContainer.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-danger">Failed to load results. Please try again later.</td>
                    </tr>`;
            })
            .finally(() => {
                if (loader) loader.style.display = 'none'; // Hide loader
            });
    }
});

</script>
@endsection