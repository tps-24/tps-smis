@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-rms/attendences/">Attendences</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Create</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
<h5>Please check absent students.</h5>
<form action="
    @if(isset($attendence))
        {{url('attendences/' . $attendence->id . '/update')}}
    @else
        {{url('attendences/'.$attendenceType->id.'/'. $platoon->id . '/store')}}
    @endif

" method="POST">
    @csrf
    @method('POST')
    <div class="row">
        @foreach($platoon->students as $student)
            <div class="col-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="student_ids[]" value="{{$student->id}}" id="">
                    <label class="form-check-label" for="flexCheckDefault">
                        {{$student->first_name}} {{$student->middle_name}} {{$student->last_name}}
                    </label>
                </div>
            </div>

        @endforeach
    </div>

   
    <div class="d-flex gap-2 justify-content-end">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
@endsection