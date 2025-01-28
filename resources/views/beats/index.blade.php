@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item active"><a href="/beats">Area Beats </a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
{{$beats}}
<div class="pull-right">
  <a class="btn btn-success mb-2" href="{{ url('students/create') }}"
    style="float:right !important; margin-right:-12px"><i class="fa fa-plus"></i> Generate Beats</a>
</div>
@if ($beats->isEmpty())

  <h5>No beats available.</h5>

@else
  <div class="table-responsive">
    <table class="table table-striped truncate m-0">
    <thead>
      <tr>
      <th>No</th>
      <th>Name</th>
      <th>Assigned</th>
      <th width="280px">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php  $i = 0;?>
      <td>

      </td>
      <td>
      <a href="#"><button class="btn btn-sm btn-info">View</button></a>
      <a href="{{ url('beats/create') }}"><button class="btn btn-sm btn-primary">Assign</button></a>
      </td>
      </tr>

    </tbody>
    </table>
  </div>
@endif

@endsection