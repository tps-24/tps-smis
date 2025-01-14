@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-rms/students/">Attendences</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Create</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
<form action="{{url('attendences/'.$platoon.'/store')}}" method="POST">
    @csrf
    @method('POST')
    <div class="row gx-4">
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Present </label>
                <input  style="width: 40%" type="number" class="form-control" id="present" name="present" required
                    placeholder="present">
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Sentry </label>
                <input value="0" style="width: 40%" type="number" class="form-control" id="sentry" name="sentry"
                    placeholder="Sentry">
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Absent </label>
                <input value="0" style="width: 40%" type="number" class="form-control" id="absent" name="absent"
                    placeholder="absent">
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Excuse Duty </label>
                <input value="0" style="width: 40%" type="number" class="form-control" id="excuse_duty" name="excuse_duty"
                    placeholder="Excuse Duty">
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Kazini </label>
                <input value="0" style="width: 40%" type="number" class="form-control" id="kazini" name="kazini"
                    placeholder="Kazini">
            </div>
        </div>

        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">ADM </label>
                <input value="0" style="width: 40%" type="number" class="form-control" id="adm" name="adm" placeholder="adm">
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Safari </label>
                <input value="0" style="width: 40%" type="number" class="form-control" id="safari" name="safari"
                    placeholder="Safari">
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Off </label>
                <input value="0" style="width: 40%" type="number" class="form-control" id="off" name="off" placeholder="Off">
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Mess </label>
                <input value="0" style="width: 40%" type="number" class="form-control" id="mess" name="mess" placeholder="Mess">
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Sick </label>
                <input value="0" style="width: 40%" type="number" class="form-control" id="sick" name="sick" placeholder="Sick">
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Male </label>
                <input  style="width: 40%" type="number" class="form-control" id="male" name="male" placeholder="Male">
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Female </label>
                <input style="width: 40%" type="number" class="form-control" id="female" name="female"
                    placeholder="Female">
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-end">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>
@endsection