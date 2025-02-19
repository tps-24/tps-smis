@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Course</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Course Lists</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
 
@endsection
@section('content')
<!-- Row starts -->
<div class="row gx-4">
  <div class="col-sm-12">
    <div class="card mb-3">
      <div class="card-header">
        
      </div>
      <div class="pull-right" >
          <a class="btn btn-success mb-2" href="{{ route('patrol-areas.create') }}" style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Create New Patrol Area</a>
      </div>
      




      <!-- resources/views/patrol_areas/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patrol Areas</title>
</head>
<body>
    <h1>Patrol Areas</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Start Area</th>
                <th>End Area</th>
                <th>Company ID</th>
                <th>Campus ID</th>
                <th>Added By</th>
                <th>Number of Guards</th>
                <th>Beat Exception IDs</th>
                <th>Beat Time Exception IDs</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($patrolAreas as $patrolArea)
                <tr>
                    <td>{{ $patrolArea->id }}</td>
                    <td>{{ $patrolArea->start_area }}</td>
                    <td>{{ $patrolArea->end_area }}</td>
                    <td>{{ $patrolArea->company_id }}</td>
                    <td>{{ $patrolArea->campus_id }}</td>
                    <td>{{ $patrolArea->added_by }}</td>
                    <td>{{ $patrolArea->number_of_guards }}</td>
                    <td>{{ $patrolArea->beat_exception_ids }}</td>
                    <td>{{ $patrolArea->beat_time_exception_ids }}</td>
                    <td>
                        <a href="{{ route('patrol-areas.show', $patrolArea->id) }}">View</a>
                        <a href="{{ route('patrol-areas.edit', $patrolArea->id) }}">Edit</a>
                        <form action="{{ route('patrol-areas.destroy', $patrolArea) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

    </div>
  </div>
</div>
<!-- Row ends -->
@endsection