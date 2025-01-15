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
<form action="
    @if(isset($attendence))
    {{url('attendences/'.$attendence->id.'/update')}}
    @else
        {{url('attendences/'.$platoon.'/store')}}
    @endif

" method="POST">
    @csrf
    @method('POST')
    <div class="row gx-4">
    <div class="col-sm-4 col-12">
    <div class="m-0">
            <label for="">Type </label>
            <select style="height:50%; width:40%;" class="form-select" name="type" id="abc4" required aria-label="Default select example">
                 @foreach ($attendenceTypes as $attendenceType)
                    <option 
                    @if(isset($attendence) && $attendenceType->id == $attendence->attendenceType_id) 
                        selected
                    @endif
                    value="{{$attendenceType->id}}">{{$attendenceType->name}}</option>
                 @endforeach
            </select>
            </div>
            </div>
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Present </label>
                <input @if(isset($attendence))
                        value="{{$attendence->present}}"
                      @endif style="width: 40%" type="number" class="form-control" id="present" name="present" required
                    placeholder="present">
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Sentry </label>
                <input @if(isset($attendence))
                        value="{{$attendence->sentry}}"
                      @else
                        value="0" 
                     @endif style="width: 40%" type="number" class="form-control" id="sentry" name="sentry"
                    placeholder="Sentry">
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Absent </label>
                <input @if(isset($attendence))
                        value="{{$attendence->absent}}"
                      @else
                        value="0" 
                     @endif  style="width: 40%" type="number" class="form-control" id="absent" name="absent"
                    placeholder="absent">
            </div>
        </div>

        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">ADM </label>
                <input @if(isset($attendence))
                        value="{{$attendence->adm}}"
                      @else
                        value="0" 
                     @endif style="width: 40%" type="number" class="form-control" id="adm" name="adm" placeholder="adm">
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Safari </label>
                <input @if(isset($attendence))
                        value="{{$attendence->safari}}"
                      @else
                        value="0" 
                     @endif value="0" style="width: 40%" type="number" class="form-control" id="safari" name="safari"
                    placeholder="Safari">
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Off </label>
                <input @if(isset($attendence))
                        value="{{$attendence->off}}"
                      @else
                        value="0" 
                     @endif value="0" style="width: 40%" type="number" class="form-control" id="off" name="off" placeholder="Off">
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Mess </label>
                <input @if(isset($attendence))
                        value="{{$attendence->mess}}"
                      @else
                        value="0" 
                     @endif value="0" style="width: 40%" type="number" class="form-control" id="mess" name="mess" placeholder="Mess">
            </div>
        </div>
        
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Male </label>
                <input @if(isset($attendence))
                        value="{{$attendence->male}}"
                      @else
                        value="0" 
                     @endif  style="width: 40%" type="number" class="form-control" id="male" name="male" placeholder="Male">
            </div>
        </div>
        <div class="col-sm-4 col-12">
            <div class="m-0">
                <label class="form-label" for="abc">Female </label>
                <input @if(isset($attendence))
                        value="{{$attendence->female}}"
                      @else
                        value="0" 
                     @endif style="width: 40%" type="number" class="form-control" id="female" name="female"
                    placeholder="Female">
            </div>
        </div>
        @if(isset($attendence))
        <div class="d-flex gap-2 justify-content-end">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        @else
        <div class="d-flex gap-2 justify-content-end">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        @endif

    </div>
</form>
@endsection