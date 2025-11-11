@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Mpango Kazi</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Assign Staff</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
@endsection

@section('content')
@include('layouts.sweet_alerts.index')


<h4 class="mb-4">Mpango Kazi: Assign Staff to Task <span class="text-primary">{{ $task->title }}</span></h4>

<!-- 🌍 Region & District Selection -->
<form method="GET" action="{{ route('tasks.assign', $task->id) }}" class="row g-3 mb-4">
  <div class="col-md-4">
    <label><strong>Region</strong></label>
    <select name="region_id" id="region-select" class="form-control" required>
      <option value="">-- Select Region --</option>
      @foreach($regions as $region)
        <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>
          {{ $region->name }}
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label><strong>District</strong></label>
    <select name="district_id" id="district-select" class="form-control" required>
      <option value="">-- Select District --</option>
      @foreach($districts as $district)
        <option value="{{ $district->id }}"
          data-region="{{ $district->region_id }}"
          {{ request('district_id') == $district->id ? 'selected' : '' }}>
          {{ $district->name }}
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4 d-flex align-items-end">
    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-arrow-down"></i> Proceed to Filter Staff</button>
  </div>
</form>

@if(request('region_id') && request('district_id'))
<!-- 🔍 Staff Filtering -->
<form method="GET" action="{{ route('tasks.assign', $task->id) }}" class="row g-3 mb-4">
  <input type="hidden" name="region_id" value="{{ request('region_id') }}">
  <input type="hidden" name="district_id" value="{{ request('district_id') }}">

  <div class="col-md-3">
    <label>Designation</label>
    <select name="designation" class="form-control">
      <option value="">All</option>
      @foreach($designations as $d)
        <option value="{{ $d }}" {{ request('designation') == $d ? 'selected' : '' }}>{{ $d }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <label>Rank</label>
    <select name="rank" class="form-control">
      <option value="">All</option>
      @foreach($ranks as $r)
        <option value="{{ $r }}" {{ request('rank') == $r ? 'selected' : '' }}>{{ $r }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label>Search by Name</label>
    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="e.g. John">
  </div>
  <div class="col-md-2 d-flex align-items-end">
    <button type="submit" class="btn btn-secondary btn-sm"><i class="fa fa-filter"></i> Filter</button>
  </div>
</form>

<!-- ✅ Staff Assignment Form -->
<form method="POST" action="{{ route('tasks.assign.store', $task->id) }}">
  @csrf
  <input type="hidden" name="region_id" value="{{ request('region_id') }}">
  <input type="hidden" name="district_id" value="{{ request('district_id') }}">
  <input type="hidden" name="assigned_by" value="{{ auth()->id() }}">

  <div class="text-center mt-3 mb-3">
    <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-paper-plane"></i> Assign Selected Staff</button>
  </div>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th><input type="checkbox" id="select-all"></th>
        <th>Name</th>
        <th>Designation</th>
        <th>Rank</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($staff as $member)
        <tr>
          <td><input type="checkbox" name="staff_ids[]" value="{{ $member->id }}"></td>
          <td>{{ $member->name }}</td>
          <td>{{ $member->designation }}</td>
          <td>{{ $member->rank }}</td>
          <td><span class="badge bg-{{ $member->status === 'active' ? 'success' : 'danger' }}">{{ ucfirst($member->status) }}</span></td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-center text-muted">No staff found.</td></tr>
      @endforelse
    </tbody>
  </table>

</form>
@endif
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const regionSelect = document.getElementById('region-select');
  const districtSelect = document.getElementById('district-select');

  function filterDistricts() {
    const selectedRegion = regionSelect.value;
    Array.from(districtSelect.options).forEach(option => {
      const belongsTo = option.getAttribute('data-region');
      option.style.display = (!selectedRegion || belongsTo === selectedRegion) ? 'block' : 'none';
    });

    // Reset district if hidden
    if (districtSelect.selectedOptions.length && districtSelect.selectedOptions[0].style.display === 'none') {
      districtSelect.value = '';
    }
  }

  regionSelect.addEventListener('change', filterDistricts);
  filterDistricts(); // initial load
});


document.addEventListener('DOMContentLoaded', function () {
  const selectAll = document.getElementById('select-all');
  const checkboxes = document.querySelectorAll('input[name="staff_ids[]"]');

  if (selectAll) {
    selectAll.addEventListener('change', function () {
      checkboxes.forEach(cb => cb.checked = selectAll.checked);
    });
  }

  // Optional: uncheck "Select All" if any box is manually unchecked
  checkboxes.forEach(cb => {
    cb.addEventListener('change', function () {
      if (!cb.checked) selectAll.checked = false;
    });
  });
});
</script>
@endsection