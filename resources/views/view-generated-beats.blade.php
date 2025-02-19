@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/tps-rms" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="">Beats</a></li>
        <li class="breadcrumb-item active"><a href="">Guards and Patrols</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
  @session('success')
    <div class="alert alert-success" role="alert">
    {{ $value }}
    </div>
  @endsession

  <div class="container">
    <h1 class="mb-4">Generated Beats for Guards and Patrols</h1>

    <!-- Display Success or Error Message -->
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <!-- Date Filter Form -->
    <form action="{{ route('view.generated.beats') }}" method="GET" class="mb-4">
        <div class="form-group">
            <label for="date">Select Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ old('date', \Carbon\Carbon::today()->toDateString()) }}">
        </div>
        <button type="submit" class="btn btn-primary mt-3">Filter</button>
    </form>

    <!-- Display Guards Beats -->
    <h3 class="mt-5">Guards Beats</h3>
    @if($guardBeats->isEmpty())
        <p>No Guard beats found for this date.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Guard Area</th>
                    <th>Time Range</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($guardBeats as $beat)
                    <tr>
                        <td>{{ $beat->students->first()->first_name }} {{ $beat->students->first()->last_name }}</td>
                        <td>{{ $beat->guardArea->start_area }} - {{ $beat->guardArea->end_area }}</td>
                        <td>{{ $beat->start_at }} - {{ $beat->end_at }}</td>
                        <td>{{ $beat->date }}</td>
                        <td>{{ $beat->status ? 'Active' : 'Inactive' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Display Patrol Beats -->
    <h3 class="mt-5">Patrol Beats</h3>
    @if($patrolBeats->isEmpty())
        <p>No Patrol beats found for this date.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Patrol Area</th>
                    <th>Time Range</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patrolBeats as $beat)
                    <tr>
                        <td>{{ $beat->students->first()->first_name }} {{ $beat->students->first()->last_name }}</td>
                        <td>{{ $beat->patrolArea->start_area }} - {{ $beat->patrolArea->end_area }}</td>
                        <td>{{ $beat->start_at }} - {{ $beat->end_at }}</td>
                        <td>{{ $beat->date }}</td>
                        <td>{{ $beat->status ? 'Active' : 'Inactive' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection