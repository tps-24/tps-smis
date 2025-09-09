@extends('layouts.main')

@section('content')
<div class="card mb-3">
  
<div class="container">
    <h2>All Weapons</h2>
    
 <a href="{{ route('weapons.create') }}" class="btn btn-primary mb-3">Add New Weapon</a>
    
    {{-- Filter Form --}}
    <form method="GET" action="{{ route('weapons.index') }}" class="row mb-3">
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">-- All Categories --</option>
                <option value="Explosive" {{ request('category') == 'Explosive' ? 'selected' : '' }}>Explosive</option>
                <option value="Ammunition" {{ request('category') == 'Ammunition' ? 'selected' : '' }}>Ammunition</option>
                <option value="Firearm" {{ request('category') == 'Firearm' ? 'selected' : '' }}>Firearm</option>
                {{-- Add more categories if needed --}}
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search by Serial/Model" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('weapons.index') }}" class="btn btn-secondary">Reset</a>
        </div>
        <div class="col-md-3 text-end">
            <!-- Statistics Button -->
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#statisticsModal">
                📊 Statistics
            </button>
        </div>
    </form>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Weapons Table --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Serial Number</th>
                <th>Model</th>
                <th>Weapon Type</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($weapons as $weapon)
                <tr>
                    <td>{{ $weapon->serial_number }}</td>
                   <td>{{ $weapon->model->name ?? 'N/A' }}</td>
                   <td>{{ $weapon->model->type->name ?? 'N/A' }}</td>
                   <td>{{ $weapon->model->category->name ?? 'N/A' }}</td>

                    <td>
                        <a href="{{ route('weapons.edit', $weapon) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('weapons.show', $weapon) }}" class="btn btn-info btn-sm">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No weapons found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    {{ $weapons->links() }}
</div>

<!-- Statistics Modal -->
<div class="modal fade" id="statisticsModal" tabindex="-1" aria-labelledby="statisticsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="statisticsModalLabel">📊 Weapon Statistics (Filtered Results)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Total Weapons Found:</strong> {{ $totalWeapons }}</p>
        <hr>
        <h6>By Category:</h6>
        <ul>
          @forelse($categoryCounts as $category => $count)
              <li>{{ $category }}: {{ $count }}</li>
          @empty
              <li>No data available for the current filter.</li>
          @endforelse
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
