@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-rms/students/">Students</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Create Final Step</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
<h1>Next of Kin information</h1>
<form action="{{url('students/create/post-step-three')}}" method="POST">
    @csrf
    @method('POST')

    <div class="row gx-4">
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">Next Kin names</label>
                        <input type="text" class="form-control" id="last_name" name="next_kin_names" required
                            placeholder="Enter next kin names">
                    </div>
                    @error('next_kin_names')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc">Phone</label>
                        <input type="number" class="form-control" id="phone" name="next_kin_phone" required
                            placeholder="Enter phone number">
                    </div>
                    @error('next_kin_phone')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc4">Relationship</label>
                        <input type="text" class="form-control" id="phone" name="next_kin_relationship" required
                            placeholder="Enter relationship">
                    </div>
                    @error('next_kin_relationship')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-sm-4 col-12">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="m-0">
                        <label class="form-label" for="abc4">Next Kin Address</label>
                        <input type="text" class="form-control" id="phone" name="next_kin_address" required
                            placeholder="Enter address">
                    </div>
                    @error('next_kin_address')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col-md-6 text-left">
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-end">
                    <div class="col-md-2 text-left">
                        <button onclick="history.back()" class="btn btn-primary">Previous</button>
                    </div>
                    <button class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection