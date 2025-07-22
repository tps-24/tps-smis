@extends('layouts.main')

@section('scrumb')
<nav class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('staffs.index') }}">Staff</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Summary</a></li>
      </ol>
    </nav>
  </div>
</nav>
@endsection

@section('content')
@include('layouts.sweet_alerts.index')
<div class="card-header">
  <h5 class="card-title">Staff Summary</h5>
  <p class="card-text">This page provides a summary of the staff summary.</p>
</div>

<div class="card-body" style="margin-right: -25px;">
  <div class="row">
    @php
      $cardTypes = [       
        ['key' => 'active', 'label' => 'Active', 'color' => 'primary'],
        ['key' => 'leave', 'label' => 'Leave', 'color' => 'success'],
        ['key' => 'study', 'label' => 'Study', 'color' => 'info'],
        ['key' => 'dismissed', 'label' => 'Dismissed', 'color' => 'danger'],
      ];
    @endphp

    @foreach ($cardTypes as $type)
    <div class="col-md-3 mb-3">
      <button class="card bg-{{ $type['color'] }} text-white w-100" onclick="showStaffs('{{ $type['key'] }}')">
        <div class="card-body">
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
    <table class="table table-striped table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>SNo</th>
          <th>Force Number</th>
          <th>Name</th>
          <th>Designation</th>
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

@endsection

@section('scripts')
<script>
  let currentFilterType = 'totalEnrolled';

  const labels = {
    totalEnrolled: "Total Staff",
    currentStudents: "Active Staff",
    dismissed: "Dismissed Staff",
    active: "Active Students",
    study: "",
    leave: "",
    verified: "Verified Students"
  };

  function showStaffs(type, page = 1) {
    currentFilterType = type;

    const sessionId = document.getElementById('programmeSession')?.value || '';
    const baseUrl = "{{ url('staff/filter') }}";

    fetch(`${baseUrl}?type=${type}&page=${page}&session_id=${sessionId}`)
      .then(response => response.json())
      .then(data => {
        const staffs = data.staffs.data;
        const container = document.getElementById('studentTableContainer');
        const title = document.getElementById('studentTableTitle');
        const body = document.getElementById('studentTableBody');

        title.textContent = labels[type] ?? "Students";
        body.innerHTML = '';
        
        let startIndex = (data.staffs.current_page - 1) * data.staffs.per_page;
        
        staffs.forEach((staff, index) => {
          const serialNumber = startIndex + index + 1;
          body.innerHTML += `
            <tr>
              <td>${serialNumber}</td>
              <td>${staff.forceNumber ?? '-'}</td>
              <td>${staff.rank ?? '-'} ${staff.firstName} ${staff.lastName}</td>
              <td>${staff.designation?? ''}</td>
              <td>
              <a href="{{ url('staffs') }}/${staff.id}" class="btn btn-sm btn-outline-primary">View Profile</a>
              </td>
            </tr>`;
        });

        renderPagination(data, type);
        container.style.display = 'block';
      })
      .catch((e) => {
        alert("Could not load student data."+e);
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
          ${data.staffs.links.map(link => {
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
        if (page) showStaffs(type, parseInt(page));
      });
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    showStaffs(currentFilterType);
  });
</script>

@endsection
