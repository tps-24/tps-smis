@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Coursework Results</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Add Results</a></li>
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
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Assessment Types for this Course</h5>
            </div>
            <div class="card-body">

            <!-- Row starts -->
            <div class="row gx-4">
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="inlineCheckbox1">Coursework (CW)</label>
                        <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                            <label class="form-check-label" for="inlineCheckbox1">Tests</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2"
                            checked="">
                            <label class="form-check-label" for="inlineCheckbox2">Assignments</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
                            <label class="form-check-label" for="inlineCheckbox3">Practicals</label>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-sm-6 col-12">
                    <div class="card-body">
                        <label class="form-label">Upload Result (CW)</label>
                        <textarea type="text" class="form-control" placeholder="Enter message" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <!-- Row ends -->

            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-outline-secondary">
                    Cancel
                    </button>
                    <button type="button" class="btn btn-primary">
                    Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Row ends -->
@endsection