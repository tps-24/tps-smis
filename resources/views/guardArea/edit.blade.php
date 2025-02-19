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
<div class="row gx-4">
    <div class="col-sm-8 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-left">
                            <h2>Add New Guard Area</h2>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('guard-areas.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    </div>
                @endif


    <form method="POST" action="{{ route('guard-areas.update', $guardArea) }}">
        @csrf
        @method('PUT')
        <label for="beat_exception_ids">Beat Exception IDs</label>
        <select name="beat_exception_ids[]" id="beat_exception_ids" multiple>
            @foreach ($beatExceptions as $beatException)
                <option value="{{ $beatException->id }}" @if(in_array($beatException->id, json_decode($guardArea->beat_exception_ids, true) ?? [])) selected @endif>
                    {{ $beatException->name }}
                </option>
            @endforeach
        </select>

        <label for="beat_time_exception_ids">Beat Time Exception IDs</label>
        <select name="beat_time_exception_ids[]" id="beat_time_exception_ids" multiple>
            @foreach ($beatTimeExceptions as $beatTimeException)
                <option value="{{ $beatTimeException->id }}" @if(in_array($beatTimeException->id, json_decode($guardArea->beat_time_exception_ids, true) ?? [])) selected @endif>
                    {{ $beatTimeException->name }}
                </option>
            @endforeach
        </select>

        <button type="submit">Save</button>
    </form>








            </div>
        </div>
     
    </div>
  
    <div class="col-sm-4 col-12">
        <div class="card mb-8">
            <div class="card-body">
            </div>
        </div>
    </div>
</div>
@endsection