@extends('layouts.main')

@section('style')
<style>
    .breadcrumb {
        display: flex;
        width: 100%;
    }
    .breadcrumb-item {
        display: flex;
        align-items: center;
    }
    #date {
        position: absolute;
        bottom: 10px; /* Adjust as needed */
        right: 15px;  /* Adjust as needed */
    }
</style>
@endsection
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Logged in as <b> {{auth()->user()->name;}} </b></a></li>
        <li class="breadcrumb-item right-align"><a href="#" id="date">{{ now()->format('l jS \\o\\f F, Y') }}</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->


<!-- Date at the bottom right -->
<!-- <div id="date">{{ now()->format('l jS \\o\\f F, Y') }} -->
 
@endsection
@section('content')
<!-- Row starts -->
<div class="row gx-4">
  
@if ($pending_message)
    <div class="alert alert-warning">
        {{ $pending_message }}
    </div>
@endif
</div>
<!-- Row ends -->
@endsection