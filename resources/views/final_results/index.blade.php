@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Semester Exam Results </a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Semester Final Results Lists</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->
@endsection

@section('content')
@include('layouts.sweet_alerts.index')
@session('success')
<div class="alert alert-success alert-dismissible " role="alert">
    {{ $value }}
</div>
@endsession
<div class="row gx-4">
    <div class="col-sm-3">
        <div class="card mb-3">
            <div class="card-header">
                <!-- Semester Tabs -->
                <ul class="nav nav-tabs" id="semesterTabs" role="tablist">
                    @foreach ($semesters as $key => $semester)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $key == 0 ? 'active bg-success text-white' : '' }}"
                            id="tab-{{ $semester->id }}" data-bs-toggle="tab"
                            data-bs-target="#semester-{{ $semester->id }}" type="button" role="tab"
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
                                <a href="#" class="course-link" data-course-id="{{ $course->id }}"
                                    data-semester-id="{{ $semester->id }}">
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

    <style>
    /* Default Tabs */
    .nav-link {
        color: black;
        /* Default text color */
    }

    /* Active Semester Tab */
    .nav-link.active {
        color: #28a745;
        /* Green text for the active semester tab */
    }

    /* Default Course Links */
    .course-link {
        text-decoration: none;
        color: black;
        /* Default text color for course links */
    }

    /* Selected Course Link */
    .course-link.selected {
        color: darkblue;
        /* Light blue text for the selected course */
        font-weight: bold;
        /* Optional: Make it stand out more */
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Highlight the active semester tab
        document.querySelectorAll('#semesterTabs .nav-link').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('#semesterTabs .nav-link').forEach(link => link
                    .classList.remove('active', 'bg-success', 'text-white'));
                this.classList.add('active', 'bg-success', 'text-white');
            });
        });

        // Highlight the selected course
        document.querySelectorAll('.course-link').forEach(course => {
            course.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default link behavior
                document.querySelectorAll('.course-link').forEach(link => link.classList.remove(
                    'selected'));
                this.classList.add('selected');
            });
        });
    });
    </script>

    <!-- Left section ends-->


    <!-- Right section starts-->
    <div class="col-sm-9">
        <div class="card mb-3">
            <div class="card-header">
                <div class="pull-right">
                    <span style="font-size:30px !important">Semester Final Results</span>
                    <!-- <h6>Here display the course choosen</h6> -->
                    <button id="ca_configuration_btn" class="btn btn-success mb-2"
                        style="float:right !important; margin-right:1%;" onclick="confirmReturn()" disabled>
                        <i class="fa fa-plus"></i> Return results
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-outer">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-info">
                                <tr id="coursework-headings">
                                    <!-- Dynamic headings will load here -->
                                </tr>
                            </thead>
                            <tbody id="coursework-results">
                                <!-- Dynamic results or "No results found" message will load here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-end mt-3" id="pagination-container">
                        <!-- Styled Bootstrap pagination links will dynamically load here -->
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
<!-- Load SweetAlert2 -->

<script>
let selectedCourseId = null;
let selectedSemesterId = null;
let csrfToken = null;
document.addEventListener('DOMContentLoaded', function() {
    csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // Start with "Return results" button disabled (just in case)
    const caConfigButton = document.getElementById('ca_configuration_btn');
    caConfigButton.disabled = true;

    // Attach click listeners to all course links
    document.querySelectorAll('.course-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            selectedCourseId = link.getAttribute('data-course-id');
            selectedSemesterId = link.getAttribute('data-semester-id');
            const courseId = this.getAttribute('data-course-id');
            const semesterId = this.getAttribute('data-semester-id');

            // Enable and update the "Return results" button/link
            const caConfigLink = document.getElementById('ca_configuration_link');
            caConfigButton.disabled = false;

            // Log selected course
            console.log(`Selected Course ID: ${courseId}, Semester ID: ${semesterId}`);

            // Fetch and render coursework results
            fetchCourseworkResults(semesterId, courseId);
        });
    });

    window.confirmReturn = function() {
        Swal.fire({
            title: 'Return Results',
            text: 'Are you sure you want to return the results?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, return',
            cancelButtonText: 'No, cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Data to send
                const dataToSend = {
                    courseId: selectedCourseId,
                    semesterId: selectedSemesterId
                };
                const url =
                    `final_results/return/semester/${selectedSemesterId}/course/${selectedCourseId}`;
                // Example API endpoint — update as needed
                fetch(url, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken // include your CSRF token here if needed (Laravel)
                        },
                        //body: JSON.stringify(dataToSend)
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(responseData => {
                        fetchCourseworkResults(selectedSemesterId, selectedCourseId);
                        Swal.fire({
                            title: 'Returned!',
                            text: 'The results have been returned.',
                            icon: 'success',
                            timer: 2000, // Auto close after 2 seconds
                            showConfirmButton: true, // Hide the OK button since it auto-closes
                        });
                        console.log('Server response:', responseData);
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Failed to return results. Please try again.',
                            'error');
                        console.error('Error:', error);
                    });
            }
        });
    };

    // Function to fetch and render coursework results
    function fetchCourseworkResults(semesterId, courseId, page = 1) {
        const apiUrl = `/tps-smis/final_results/semester/${semesterId}/course/${courseId}?page=${page}`;
        const headingsContainer = document.getElementById('coursework-headings');
        const resultsContainer = document.getElementById('coursework-results');
        const paginationContainer = document.getElementById('pagination-container');

        if (!headingsContainer || !resultsContainer || !paginationContainer) {
            console.error('Error: Necessary DOM elements are missing');
            return;
        }

        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Fetched Data:', data);

                if (!data.results || !data.results.data || data.results.data.length === 0) {
                    headingsContainer.innerHTML = '';
                    resultsContainer.innerHTML = `
                        <tr>
                            <td colspan="7" class="text-muted text-center">No results found for this course.</td>
                        </tr>
                    `;
                    paginationContainer.innerHTML = '';
                    return;
                }

                // Render headings
                headingsContainer.innerHTML = `
                    <th>#</th>
                    <th>Force Number</th>
                    <th>Student Name</th>
                    <th style="text-align: center;">Score</th>
                    <th style="text-align: center;">Grade</th>
                `;

                resultsContainer.innerHTML = '';

                data.results.data.forEach((result, index) => {
                    const student = result.student;
                    if (!student) return;

                    const fullName =
                        `${student.first_name} ${student.middle_name || ''} ${student.last_name}`
                        .replace(/\s+/g, ' ').trim();
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td style="text-align: center;">${index + 1}</td>
                        <td style="text-align: center;">${student.force_number}</td>
                        <td>${fullName}</td>
                        <td style="text-align: center;">${result.total_score}</td>
                        <td style="text-align: center;">${result.grade}</td>
                    `;

                    resultsContainer.appendChild(row);
                });

                // Render pagination
                paginationContainer.innerHTML = `
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                            ${data.results.links.map(link => {
                                const page = link.url ? new URL(link.url, window.location.origin).searchParams.get('page') : null;
                                return `
                                    <li class="page-item ${link.active ? 'active' : ''} ${link.url ? '' : 'disabled'}">
                                        <a class="page-link" href="#" ${page ? `data-page="${page}"` : ''}>
                                            ${link.label}
                                        </a>
                                    </li>
                                `;
                            }).join('')}
                        </ul>
                    </nav>
                `;

                // Attach pagination click handlers
                document.querySelectorAll('.page-link').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const page = this.getAttribute('data-page');
                        if (page) {
                            fetchCourseworkResults(semesterId, courseId, page);
                        }
                    });
                });
            })
            .catch(error => {
                console.error('Error fetching results:', error);
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
