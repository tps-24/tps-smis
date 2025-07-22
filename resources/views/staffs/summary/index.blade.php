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

<!-- Filter Form -->
<div class="d-flex justify-content-center my-3">
  <form id="staffFilterForm" class="d-flex flex-nowrap gap-2 align-items-center col-12 col-md-8 col-lg-6">
    <div class="input-group">
      <span class="input-group-text">Name</span>
      <input type="text" class="form-control" name="staff_name" placeholder="Enter staff name">
    </div>
    <input type="hidden" name="status" id="filterStatus" value="">
    <button type="submit" class="btn btn-primary">Filter</button>
  </form>
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
          <th>Status</th>
          <th>View</th>
        </tr>
      </thead>
      <tbody id="studentTableBody"></tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="d-flex justify-content-end mt-3" id="pagination-container"></div>
</div>
@endsection

@section('scripts')
<script>
  let currentFilterType = 'total';

  const labels = {
    total: "Total Staff",
    active: "Active Staff",
    leave: "Staff on Leave",
    study: "Staff on Study",
    dismissed: "Dismissed Staff"
  };

  function showStaffs(type = 'total', page = 1) {
    currentFilterType = type;
    document.getElementById('filterStatus').value = type;

    const form = document.getElementById('staffFilterForm');
    const formData = new FormData(form);
    formData.append('type', type);

    const params = new URLSearchParams(formData).toString();
    const url = `{{ url('staff/filter') }}?${params}&page=${page}`;

    fetch(url)
      .then(response => response.json())
      .then(data => {
        const staffs = data.staffs.data;
        const container = document.getElementById('studentTableContainer');
        const title = document.getElementById('studentTableTitle');
        const body = document.getElementById('studentTableBody');

        title.textContent = labels[type] ?? "Staff";
        body.innerHTML = '';

        let startIndex = (data.staffs.current_page - 1) * data.staffs.per_page;

        staffs.forEach((staff, index) => {
          const serialNumber = startIndex + index + 1;
          body.innerHTML += `
            <tr>
              <td>${serialNumber}</td>
              <td>${staff.forceNumber ?? '-'}</td>
              <td>${staff.rank ?? '-'} ${staff.firstName} ${staff.lastName}</td>
              <td>${staff.designation ?? '-'}</td>
              <td>${staff.status ?? '-'}</td>
              <td>
                <a href="/tps-smis/staffs/${staff.id}" class="btn btn-sm btn-outline-primary">View Profile</a>
              </td>
            </tr>
          `;
        });

        renderPagination(data, type);
        container.style.display = 'block';
      })
      .catch(err => alert("Failed to load staff data: " + err));
  }

  function renderPagination(data, type) {
    const paginationContainer = document.getElementById('pagination-container');
    paginationContainer.innerHTML = `
      <nav>
        <ul class="pagination justify-content-end flex-wrap mb-0">
          ${data.staffs.links.map(link => {
            const page = link.url ? new URL(link.url).searchParams.get('page') : null;
            const label = link.label.replace(/&laquo;/g, '«').replace(/&raquo;/g, '»');

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
        if (page) showStaffs(currentFilterType, parseInt(page));
      });
    });
  }

  document.getElementById('staffFilterForm').addEventListener('submit', function (e) {
    e.preventDefault();
    showStaffs(currentFilterType);
  });

  document.addEventListener('DOMContentLoaded', () => {
    showStaffs(currentFilterType);
  });
</script>
@endsection
