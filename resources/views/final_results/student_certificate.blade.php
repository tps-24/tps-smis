@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-smis/students/">Students</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Print Certificates</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection

@section('content')

<div class="row">
    @session('success')
    <div class="alert alert-success" role="alert">
        {{ $value }}
    </div>
    @endsession

    <ul class="nav nav-tabs" id="companyTabs" role="tablist">
        @foreach($companies as $company)
        <li class="nav-item" role="presentation">
            <button class="nav-link @if($loop->first) active @endif " id="tab-{{ $company->id }}" data-bs-toggle="tab"
                data-bs-target="#company-{{ $company->id }}" type="button" role="tab"
                aria-controls="company-{{ $company->id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                {{ $company->description }}
            </button>
        </li>
        @endforeach
    </ul>
    <div class="tab-content" id="companyTabContent">
        @foreach($companies as $company)
        <div class="tab-pane fade @if($loop->first) show active @endif" id="company-{{ $company->id }}" role="tabpanel"
            aria-labelledby="tab-{{ $company->id }}">
            <div class="card my-3">
                <div class="col-6 " style="float: right;">
                    <form action="{{route('students.search_certificate', $company->id)}}" method="GET">
                        @csrf
                        @method("GET")
                        <div class="row">
                            <div class="col-4">
                                <label for="">Filter by Platoon</label>
                            </div>
                            <div class="col-6">
                                <!-- Platoon Dropdown -->
                                <select onchange="this.form.submit()" class="form-select me-2" name="platoon" required>
                                    <option value="" selected disabled>Select Platoon</option>
                                    @for ($i = 1; $i < 15; $i++) <option value="{{ $i }}"
                                        {{ request('platoon') == $i ? 'selected' : '' }}> {{ $i }}</option>
                                        @endfor
                                </select>
                            </div>

                        </div>
                    </form>
                </div>
                <form action="{{ route('final.generateTranscript') }}" method="POST" class="form-inline mb-4">

                    <div class="card-header">
                        <i>Choose student(s) to print certificate</i>
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="float:right">Print Transcript(s)</button>

                    </div>
                    <div class="card-body">
                        <div class="table-outer">
                            <div class="table-responsive">
                                <!-- Make changes to your table -->
                                <table class="table table-striped truncate m-0">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                            </th>
                                            <th>No</th>
                                            <th>Force Number</th>
                                            <th>Name</th>
                                            <th>Company</th>
                                            <th>Platoon</th>
                                            <th>Phone</th>
                                            <th>Home Region</th>
                                            <th width="280px">Certificate Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0; ?>
                                        @foreach($company->students as $student)
                                        <tr>
                                            <td>
                                                <input class="form-check-input student-checkbox" type="checkbox"
                                                    name="selected_students[]" value="{{ $student->id }}">
                                            </td>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $student->force_number ?? '' }}</td>
                                            <td>{{ $student->first_name }} {{ $student->middle_name }}
                                                {{ $student->last_name }}</td>
                                            <td>{{ $student->company->name ?? '' }}</td>
                                            <td>{{ $student->platoon }}</td>
                                            <td>{{ $student->phone }}</td>
                                            <td>{{ $student->home_region }}</td>
                                            <td>
                                                @if($student->transcript_printed == 1)
                                                <a class="btn btn-success btn-sm" href="#">Printed</a>
                                                @else
                                                <a class="btn btn-warning btn-sm" href="#">Not Printed</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- {!! $students->links('pagination::bootstrap-5') !!} -->

    @if($students->isEmpty())
    <p>No beats found for the selected date.</p>
    @endif

    @endsection


    @section('scripts')

    <script>
    document.getElementById('selectAll').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    </script>



    @endsection