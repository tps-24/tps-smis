@extends('layouts.main')

@section('style')
  <style>
    .bscrumb {
      background-color: #f8f9fa;
      margin-right: 25px;
      border-bottom: 1px solid #dee2e6;
    }
    .card-header {
      /* background-color: #007bff; */
      /* color: white; */
    }
    .card-body {
      padding: 20px;
    }

  </style>
@endsection

@section('scrumb')
<nav class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Intake History</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Intake Management Summary</a></li>
      </ol>
    </nav>
  </div>
  </nav>
@endsection

@section('content')
<div class="card mb-4" style="margin-right: 0px;">
<div class="card-header">
  <h5 class="card-title">Intake Management Summary</h5>
  <p class="card-text">This page provides a summary of the intake history of students.</p>
</div>

<div class="card-body" style="margin-right: 0px;">
  <div class="row" style="margin-right: -10px;">
    @php
      $cardTypes = [
        ['key' => 'totalEnrolled', 'label' => 'Enrolled Students', 'color' => 'primary'],
        ['key' => 'currentStudents', 'label' => 'Current Students', 'color' => 'info'],
        ['key' => 'dismissed', 'label' => 'Dismissed Students', 'color' => 'danger'],
        ['key' => 'verified', 'label' => 'Verified Students', 'color' => 'success'],
      ];
    @endphp

    @foreach ($cardTypes as $type)
    <div class="col-md-3">
      <button class="card bg-{{ $type['color'] }} text-white w-100" onclick="showStudents('{{ $type['key'] }}')">
        <div class="card-body text-center">
          <h5>{{ $type['label'] }}</h5>
          <p class="fs-4">{{ $stats[$type['key']]->count() ?? 0 }}</p>
        </div>
      </button>
    </div>
    @endforeach
  </div>

  <!-- Result Section -->
   
  <div class="row gx-4" style="margin-right: -25px;">
    <div class="col-sm-12">
      <div class="card-body">
        <center>Filtering</center> 
      </div>
    </div>
    <div class="col-sm-4">
      <div class="card-body" style="background-color:blue; margin-right: 0px;">
        <span>Blah</span>
      </div>
    </div>
    <div class="col-sm-8" style="margin-right:-2000px">
      <div class="card-body" style="padding-right: -20px !important; ">
      <div id="studentTableContainer" class="mt-1" style="display: none;">
        <h4 id="studentTableTitle" class="mb-3"></h4>

        <div class="table-responsive">
          <table class="table table-striped table-bordered text-center align-middle">
            <thead class="table-dark">
              <tr>
                <th>SNo</th>
                <th>Force No.</th>
                <th>Name</th>
                <th>Region</th>
                <th>Status</th>
                <th>View</th>
              </tr>
            </thead>
            <tbody id="studentTableBody"></tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-3" id="pagination-container">
            <!-- Pagination links will render here -->
        </div>
    </div>
    </div>
  </div>
</div>

</div>
</div>

@endsection

@section('scripts')
<script>
  let currentFilterType = 'totalEnrolled';

  const labels = {
    totalEnrolled: "Total Enrolled Students",
    currentStudents: "Current Students",
    dismissed: "Dismissed Students",
    verified: "Verified Students"
  };

  function showStudents(type, page = 1) {
    currentFilterType = type;

    const sessionId = document.getElementById('programmeSession')?.value || '';
    const baseUrl = "{{ url('students/filter') }}";

    fetch(`${baseUrl}?type=${type}&page=${page}&session_id=${sessionId}`)
      .then(response => response.json())
      .then(data => {
        const students = data.students.data;
        const container = document.getElementById('studentTableContainer');
        const title = document.getElementById('studentTableTitle');
        const body = document.getElementById('studentTableBody');

        title.textContent = labels[type] ?? "Students";
        body.innerHTML = '';

        let startIndex = (data.students.current_page - 1) * data.students.per_page;
        students.forEach((student, index) => {
          const serialNumber = startIndex + index + 1;

        let statusBadge = '';
        if (student.status === 'approved') {
          statusBadge = `<span class="badge bg-success">✅ Verified</span>`;
        } else if (student.status === 'pending') {
          statusBadge = `<span class="badge bg-warning text-dark">⏳ Pending</span>`;
        } else {
          statusBadge = `<span class="badge bg-secondary">❔ Unknown</span>`;
        }

        body.innerHTML += `
          <tr>
            <td>${serialNumber}</td>
            <td>${student.force_number ?? '-'}</td>
            <td>${student.first_name} ${student.middle_name} ${student.last_name}</td>
            <td>${student.entry_region}</td>
            <td>${statusBadge}</td>
            <td>
              <a href="{{ url('students') }}/${student.id}" class="btn btn-sm btn-outline-primary">View Profile</a>
            </td>
          </tr>`;

        });

        renderPagination(data, type);
        container.style.display = 'block';
      })
      .catch(() => {
        alert("Could not load student data.");
      });
  }

  function renderPagination(data, type) {
    const paginationContainer = document.getElementById('pagination-container');
    if (!paginationContainer) {
      console.error('Pagination container not found.');
      return;
    }

    paginationContainer.innerHTML = `
      <nav aria-label="Student pagination">
        <ul class="pagination justify-content-end flex-wrap mb-0">
          ${data.students.links.map(link => {
            const page = link.url ? new URL(link.url, window.location.origin).searchParams.get('page') : null;
            const label = link.label
              .replace(/&laquo;/g, '«')
              .replace(/&raquo;/g, '»');

            return `
              <li class="page-item ${link.active ? 'active' : ''} ${!link.url ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${page}">${label}</a>
              </li>
            `;
          }).join('')}
        </ul>
      </nav>
    `;

    paginationContainer.querySelectorAll('.page-link').forEach(link => {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        const page = this.getAttribute('data-page');
        if (page) showStudents(type, parseInt(page));
      });
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    showStudents(currentFilterType);
  });
</script>

@endsection
