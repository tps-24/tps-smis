@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Guard</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Guard Areas</a></li>
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
          <a class="btn btn-success mb-2" href="{{ route('guard-areas.create') }}" style="float:right !important; margin-right:1%"><i class="fa fa-plus"></i> Add New Guard Area</a>
      </div>
      

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guard Areas</title>
</head>
<body>
    <h1>Guard Areas</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Company ID</th>
                <th>Campus ID</th>
                <th>Added By</th>
                <th>Beat Exception IDs</th>
                <th>Beat Time Exception IDs</th>
                <th>Number of Guards</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($guardAreas as $guardArea)
                <tr>
                    <td>{{ $guardArea->id }}</td>
                    <td>{{ $guardArea->name }}</td>
                    <td>{{ $guardArea->company_id }}</td>
                    <td>{{ $guardArea->campus_id }}</td>
                    <td>{{ $guardArea->added_by }}</td>
                    <td>{{ $guardArea->beat_exception_ids }}</td> 
                    <td>{{ $guardArea->beat_time_exception_ids }}</td>
                    <td>{{ $guardArea->number_of_guards }}</td>
                    <td>
                        <a href="{{ route('guard-areas.show', $guardArea) }}">View</a>
                        <a href="{{ route('guard-areas.edit', $guardArea) }}">Edit</a>
                        <form action="{{ route('guard-areas.destroy', $guardArea) }}" method="POST" style="display:inline;">
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