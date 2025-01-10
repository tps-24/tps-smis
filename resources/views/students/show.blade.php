@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">

        <div class="pull-right">
            <a class="btn btn-primary" href=""> Back</a>
        </div>
    </div>
</div>


<div class="row gx-2">
    <div class="col-sm-4 col-6">
        <div class=" mb-2">
            <div class="form-group">
                <strong>Force Number:</strong>
                {{ $student->force_number }}
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-12">
        <div class=" mb-2">
            <div class="form-group">
                <strong>Names:</strong>
                {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-12">
        <div class=" mb-2">

            <div class="form-group">
                <strong>Company:</strong>
                {{ $student->company }}
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-12">
        <div class=" mb-2">
            <div class="form-group">
                <strong>Platoon:</strong>
                {{ $student->platoon }}
            </div>
        </div>
    </div>

    <div class="col-sm-4 col-12">
        <div class=" mb-2">
            <div class="form-group">
                <strong>Gender</strong>
                @if($student->gender === 'M')
                Male
            @elseif($student->gender === 'F')
                Female
            @endif
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-12">
        <div class=" mb-2">
            <div class="form-group">
                <strong>Education_level:</strong>
                {{ $student->education_level }}
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-12">
        <div class=" mb-2">
            <div class="form-group">
                <strong>Home Region:</strong>
                {{ $student->home_region }}
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-12">
        <div class=" mb-2">
            <div class="form-group">
                <strong>Height:</strong>
                {{ $student->height }}
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-12">
        <div class=" mb-2">
            <div class="form-group">
                <strong>Weight:</strong>
                {{ $student->weight }}
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-12">
        <div class=" mb-2">
            <div class="form-group">
                <strong>Blood Group:</strong>
                {{ $student->blood_group }}
            </div>
        </div>
    </div>
    <div class="col-sm-4 col-12">
        <div class=" mb-2">
            <div class="form-group">
                <strong>Nida:</strong>
                {{ $student->nin }}
            </div>
        </div>
    </div>

</div>
@endsection