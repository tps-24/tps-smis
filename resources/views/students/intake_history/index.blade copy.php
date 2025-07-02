@extends('layouts.main')

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
<div class="card-header">
  <h5 class="card-title">Intake Management Summary</h5>
  <p class="card-text">This page provides a summary of the intake history of students.</p>
</div>

<div class="card-body" style="margin-right: -25px;">
  <div class="row">
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
</div>

<!-- Result Section -->
<div id="studentTableContainer" class="mt-4" style="display: none;">
  <h4 id="studentTableTitle" class="mb-3"></h4>

  <div class="table-responsive">
    <table class="table table-striped table-bordered text-center align-middle">
      <thead class="table-dark">
        <tr>
          <th>Name</th>
          <th>Force No.</th>
          <th>Region</th>
          <th>Status</th>
          <th>Verified</th>
        </tr>
      </thead>
      <tbody id="studentTableBody"></tbody>
    </table>
  </div>

  <div id="studentPagination" class="mt-3"></div>
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

        students.forEach(student => {
          body.innerHTML += `
            <tr>
              <td>${student.first_name}</td>
              <td>${student.force_number ?? '-'}</td>
              <td>${student.entry_region}</td>
              <td>${student.status}</td>
              <td>${student.is_verified ? 'Yes' : 'No'}</td>
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
  const pagination = document.getElementById('studentPagination');
  const current = data.students.current_page;
  const last = data.students.last_page;

  let html = `<nav><ul class="pagination justify-content-center flex-wrap">`;

  // ← Previous
  html += `
    <li class="page-item ${current === 1 ? 'disabled' : ''}">
      <button class="page-link" onclick="showStudents('${type}', ${current - 1})">«</button>
    </li>`;

  const pages = [];

  // Always show first page
  pages.push(1);

  // Add dots if current is far from start
  if (current > 4) pages.push('...');

  // Add pages around current
  for (let i = current - 1; i <= current + 1; i++) {
    if (i > 1 && i < last) pages.push(i);
  }

  // Add dots if current is far from end
  if (current < last - 3) pages.push('...');

  // Always show last page
  if (last > 1) pages.push(last);

  // Render page buttons
  pages.forEach(p => {
    if (p === '...') {
      html += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
    } else {
      html += `
        <li class="page-item ${p === current ? 'active' : ''}">
          <button class="page-link" onclick="showStudents('${type}', ${p})">${p}</button>
        </li>`;
    }
  });

  // → Next
  html += `
    <li class="page-item ${current === last ? 'disabled' : ''}">
      <button class="page-link" onclick="showStudents('${type}', ${current + 1})">»</button>
    </li>`;

  html += `</ul></nav>`;
  pagination.innerHTML = html;
}



  document.addEventListener('DOMContentLoaded', () => {
    showStudents(currentFilterType);
  });
</script>

@endsection
