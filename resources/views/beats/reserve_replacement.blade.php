@extends('layouts.main')

@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                    <li class="breadcrumb-item"><a href="/tps-smis/beats">Beats</a></li>
                    <li class="breadcrumb-item active"><a href="#">Reserves</a></li>
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
    <h2>{{ $company->description }} Beats Reserves Replacement for {{ $date }}</h2>
    <h4>Reserve : {{ $reserve->first_name }} {{ $reserve->last_name }} PLT-{{ $reserve->platoon }}</h4>
    @php
        $i = 0;
    @endphp
    <div class="card-body">
        <div class="table-outer">
            <div class="table-responsive">
                <table class="table table-striped truncate m-0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Names</th>
                            <th>Platoon</th>
                            <th width="280px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <in>

                                <td>{{ ++$i }}.</td>
                                <td>
                                    {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}
                                    </input>
                                </td>

                                <td>{{ $student->platoon }}</td>
                                <td><a href="{{ route('beats.replace-reserve',['reserveId'=>$reserve->id, 'studentId'=>$student->id, 'date'=>$date,'beatReserveId'=>$beatReserveId]) }}" class="btn btn-sm btn-primary">Replace</a></td>
                                @if($student->beat_status != 1)

                                @endif
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection