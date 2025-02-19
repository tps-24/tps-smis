@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Notification View</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->

@endsection

@section('content')
{{ $ids }}
<div class="row">

<div class="col-12">
      <!-- Notifications Container Start -->
      <h5 class="m-0 text-primary py-2"> Title: {{ $notification->title }}</h5>
        <p>
            {{ $notification->message }}
        </p>
      <!-- Notifications Container End -->
    </div>
  
</div>
</div>
@endsection