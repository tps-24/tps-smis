@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item "><a href="/tps-smis/beats">Beats</a></li>
                <li class="breadcrumb-item active"><a href="#">Guards </a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')


<div class="row gx-4">
    <div class="col-sm-12 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <!-- Custom tabs start -->

<div class="container">
    <h2>Beat Details</h2>
    <p><strong>Date:</strong> {{ $beat->date }}</p>
    <p><strong>Start Time:</strong> {{ $beat->start_at }}</p>
    <p><strong>End Time:</strong> {{ $beat->end_at }}</p>

    <h3>Assigned Students</h3>
    <ul>
        @foreach($students as $student)
            <li>{{ $student->first_name }} {{ $student->last_name }} (PLT {{ $student->platoon }})</li>
        @endforeach
    </ul>
</div>

            </div>
        </div>
    </div>
</div>
@endsection