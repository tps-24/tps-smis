@extends('layouts.main')
@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="home">Home</a></li>
                    <li class="breadcrumb-item active"><a href="#">Announcements</a>
                    </li>
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
    <form action="{{ route('announcements.store') }}" method="POST">
        @csrf

        <div class="row gx-4">
            <div class="col-sm-4 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label class="form-label" for="abc">Title </label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Announcement title" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label for="type">Type</label>
                            <select class="form-control" name="type" id="type" required>
                                <option selected value="" disabled>select type</option>
                                <option value="info">Info</option>
                                <option value="success">Success</option>
                                <option value="danger">Danger</option>
                                <option value="warning">Warning</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label for="expires_at">Expires At </label>
                            <input class="form-control" type="datetime-local" name="expires_at" id="expires_at" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label class="form-label" for="abc">Message </label>
                            <textarea class="form-control" name="message" id="message" required placeholder="type here......"></textarea>
                        </div>
                    </div>
                </div>
        <div class="d-flex gap-2 justify-content-end">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
@endsection