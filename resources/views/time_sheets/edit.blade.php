@extends('layouts.main')
@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                    <li class="breadcrumb-item"><a href="">Time Sheet</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Edit</a></li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->

@endsection
@php
    use Carbon\Carbon;
@endphp
@section('content')
    @session('success')
        <div class="alert alert-success alert-dismissible " role="alert">
            {{ $value }}
        </div>
    @endsession
    <form action="{{ route('timesheets.update' ,$timeSheet->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row gx-4">
            <div class="col-sm-6 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                    <div class="m-0">
                            <label class="form-label" for="abc">Time(hours)</label>
                            <input type="number" class="form-control" id="hours" name="hours" required
                              min="1"  value="{{old('hours', $timeSheet->hours)}}">
                        </div>
                        @error('hours')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
           

            <div class="col-sm-6 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label class="form-label" for="abc">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required
                                value="{{ old('date', Carbon::parse($timeSheet->date)->format('Y-m-d')) }}" >
                        </div>
                        @error('date')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="m-0">
                            <label class="form-label" for="abc">Task </label>
                            <textarea class="form-control" id="task" name="task"
                                placeholder="Describe your task here....">{{ old('task', $timeSheet->task) }}</textarea>

                        </div>
                        @error('task')
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
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection