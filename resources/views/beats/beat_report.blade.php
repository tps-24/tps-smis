@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-rms" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="">Beats</a></li>
                <li class="breadcrumb-item active"><a href="">Beat Report</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->
@endsection
@section('content')

@if(session('success'))
<div style="color: green">{{ session('success') }}</div>
@endif



<div class="container">
    <h1>Beat Report</h1>

    <!-- Filter form -->
    <form action="{{ route('report.generate') }}" method="get" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="form-control"
                    value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4">
                <label for="date_filter">Date Filter:</label>
                <select name="date_filter" id="date_filter" class="form-control">
                    <option value="">None</option>
                    <option value="weekly" {{ request('date_filter') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ request('date_filter') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Filter</button>
        <a href="{{ route('report.download', ['start_date' => request('start_date'), 'end_date' => request('end_date'), 'date_filter' => request('date_filter')]) }}"
            class="btn btn-secondary mt-3">Download PDF</a>
    </form>

    <!-- Report data in tabs -->
    <ul class="nav nav-tabs" id="companyTabs" role="tablist">
        @foreach ($report['companies'] as $index => $company)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $index == 0 ? 'active' : '' }}" id="tab-{{ $company['company_id'] }}"
                data-bs-toggle="tab" data-bs-target="#content-{{ $company['company_id'] }}" type="button" role="tab"
                aria-controls="content-{{ $company['company_id'] }}"
                aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                {{ $company['company_name'] }}
            </button>
        </li>
        @endforeach
    </ul>
    <div class="tab-content" id="companyTabsContent">
        @foreach ($report['companies'] as $index => $company)
        <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="content-{{ $company['company_id'] }}"
            role="tabpanel" aria-labelledby="tab-{{ $company['company_id'] }}">
            <ul>
                <li>Total Students: {{ $company['total_students'] }}</li>
                <li>Total Eligible Students: {{ $company['total_eligible'] }}</li>
                <li>Total Ineligible Students: {{ $company['total_ineligible'] }}</li>
                <li>Percentage Eligible: {{ $company['percent_eligible'] }}%</li>
                <li>Percentage Ineligible: {{ $company['percent_ineligible'] }}%</li>
            </ul>

            <h2>Guard Areas Per Company</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Company ID</th>
                        <th>Total Guard Areas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report['guard_areas'] as $area)
                    @if ($area->company_id == $company['company_id'])
                    <tr>
                        <td>{{ $area->company_id }}</td>
                        <td>{{ $area->total_guard_areas }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>

            <h2>Patrol Areas Per Company</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Company ID</th>
                        <th>Total Patrol Areas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report['patrol_areas'] as $area)
                    @if ($area->company_id == $company['company_id'])
                    <tr>
                        <td>{{ $area->company_id }}</td>
                        <td>{{ $area->total_patrol_areas }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>

            <h2>Students Required Per Day</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Company ID</th>
                        <th>Total Required Per Day</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report['students_required_per_day'] as $item)
                    @if ($item->company_id == $company['company_id'])
                    <tr>
                        <td>{{ $item->company_id }}</td>
                        <td>{{ $item->total_required_per_day }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>

            <h2>Current Round Status</h2>
            <ul>
                @foreach ($report['current_round_status'] as $status)
                @if ($status->company_id == $company['company_id'])
                <li>Company ID: {{ $status->company_id }}</li>
                <li>Current Round: {{ $status->current_round }}</li>
                @endif
                @endforeach
            </ul>

            <h2>Round Attendance</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Company ID</th>
                        <th>Attained Current Round</th>
                        <th>Exceeded Current Round</th>
                        <th>Not Attained Current Round</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report['round_attendance'] as $attendance)
                    @if ($attendance->company_id == $company['company_id'])
                    <tr>
                        <td>{{ $attendance->company_id }}</td>
                        <td>{{ $attendance->attained_current_round }}</td>
                        <td>{{ $attendance->exceeded_current_round }}</td>
                        <td>{{ $attendance->not_attained_current_round }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>

            <h2>Ineligible Students Based on Vitengos</h2>
            @foreach ($report['vitengo_categories'][$company['company_id']] ?? [] as $vitengo => $students)
            <h3>{{ $vitengo }}</h3>
            @php
                $i = 0;
            @endphp
            <table class="table table-striped truncate m-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Names</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $student }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endforeach

            <h2>Ineligible Students Based on Emergency</h2>
            @foreach ($report['emergency_categories'][$company['company_id']] ?? [] as $emergency)
            <p>{{ $emergency['name'] }} - Reason: {{ $emergency['reason'] }}</p>
            @endforeach

            <h2>Ineligible Students with String Reasons</h2>
            @foreach ($report['string_reasons'][$company['company_id']] ?? [] as $reason)
            <p>{{ $reason['name'] }} - Reason: {{ $reason['reason'] }}</p>
            @endforeach
        </div>
        @endforeach
    </div>
</div>

@endsection