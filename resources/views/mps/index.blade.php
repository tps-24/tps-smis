@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-rms/smis/">Lock Up</a></li>
                @if(isset($scrumbName))
                    <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$scrumbName}} Coy</a></li>
                @endif
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div style="display: flex; justify-content: flex-end; margin-right: 2px;">
    <a href="{{url('/mps/create')}}"><button class="btn btn-sm btn-success">Add Student</button></a>
</div>
@if(isset($mpsStudents))
    @if ($mpsStudents->isNotEmpty())
        <div class="table-outer">
            <div class="table-responsive">
                <table class="table table-striped m-0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Days</th>
                            <th>Arested at</th>
                            <th>Released at</th>
                            <th>Imprisoned By</th>
                            <th>Actions</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php        $i = 0; ?>
                        @foreach ($mpsStudents as $student)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$student->student->first_name ?? ''}} {{$student->student->last_name ?? ''}}</td>
                                <td>{{$student->days}}</td>
                                <td>{{$student->arrested_at}}</td>
                                <td>
                                    @if (!$student->released_at)
                                        Not Released
                                    @else
                                        {{$student->released_at}}
                                    @endif
                                </td>
                                <td>{{$student->staff->name ?? ''}}</td>
                                <td>
                                    <button class="btn  btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#statusModal{{ $student->id ?? ''}}">
                                        More
                                    </button>
                                </td>
                                <td>

                                    @if(!$student->released_at)
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#statusModalRelease">
                                            Edit
                                        </button>
                                        <button class="btn  btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#Release">Release</button>
                                    @endif
                                    <div class="modal fade" id="Release" tabindex="-1" aria-labelledby="statusModalLabelRelease"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="statusModalLabelRelease">
                                                        Release Confirmation
                                                    </h5>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">

                                                    <p>You are about to release {{$student->student->first_name ?? ''}}
                                                        {{$student->student->last_name ?? ''}}
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{url('mps/release/' . $student->id)}}" method="POST">
                                                        @csrf
                                                        <button class="btn btn-sm btn-primary">Release</button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="statusModal{{  $student->id ?? '' }}" tabindex="-1"
                                        aria-labelledby="statusModalLabel{{  $student->id ?? '' }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="statusModalLabel{{  $student->id ?? ''}}">
                                                        More Details
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h5>Name: {{ $student->student->first_name }} {{ $student->student->last_name }}</h5>
                                                    <h5>Company: {{ $student->student->company->name ?? ''}} - {{ $student->student->platoon ?? ''}}</h5>
                                                   <h5>Description</h5> <p>{{$student->description}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Edit MPS information -->
                                    <div class="modal fade" id="statusModalRelease" tabindex="-1"
                                        aria-labelledby="statusModalLabelRelease" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="statusModalLabelRelease">Edit
                                                        Student Details for {{ $student->student->first_name }}
                                                        {{ $student->student->last_name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Form to update patient status -->
                                                    <form action="{{url('/mps/store/' . $student->id)}}" method="POST">
                                                        @csrf
                                                        @method('POST')

                                                        <div class="mb-3">
                                                            <label for="excuseType" class="form-label">Arrested At</label>
                                                            <input value="{{$student->arrested_at}}" class="form-control"
                                                                type="datetime-local" required name="arrested_at">
                                                        </div>
                                                        @error('arrested_at')
                                                            <div class="error">{{ $message }}</div>
                                                        @enderror

                                                        <div class="mb-3">
                                                            <label for="excuseType" class="form-label">Days</label>
                                                            <input value="{{$student->days}}" class="form-control" value="1" min="1"
                                                                type="number" required name="days">
                                                        </div>
                                                        @error('days')
                                                            <div class="error">{{ $message }}</div>
                                                        @enderror

                                                        <div class="mb-3">
                                                            <label for="description" class="form-label">Description</label>
                                                            <textarea class="form-control" id="" name="description" rows="3"
                                                                required>{{$student->description}}</textarea>
                                                        </div>
                                                        @error('description')
                                                            <div class="error">{{ $message }}</div>
                                                        @enderror

                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
     No records founds.
    @endif
@endif
@endsection