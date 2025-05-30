@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active"><a href="#">Attendances</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->
@endsection
@section('content')

<div class="d-flex justify-content-end" style="width:20%; margin-left: auto;">
        <form class="d-flex gap-2 w-100" action="{{ route('reports.generateAttendanceReport') }}" method="get">
            <select class="form-control w-100" name="company_id" id="company_id" required style="height: 40px;" required>
                <option value="" selected disabled> company</option>
                @foreach ($companies as $company)
                <option value="{{ $company->id }}">{{ $company->description }}</option>
                @endforeach
            </select>
            <button title="Download report" type="submit" class="btn btn-success"
                style="min-width: 120px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-download me-1"></i> Report
            </button>
        </form>
</div>


<div class="btn-group mb-3" role="group" aria-label="Filter options">
    <button type="button" class="btn btn-primary" onclick="showDaily()">Daily</button>
    <button type="button" class="btn btn-secondary" onclick="showWeekly()">Weekly</button>
    <button type="button" class="btn btn-success" onclick="showMonthly()">Monthly</button>
</div>
<div class="chart-container" style=" padding: 0 10% 0 10%">
    <canvas id="groupedBarChart"></canvas>
</div>


<h3>Most Absent Students</h3><br>
<table class="table table-responsive table-sm">
    <thead>
        <tr>
            <th>S/N</th>
            <th>Names</th>
            <th>Platoon</th>
            <th>Counts</th>
        </tr>
    </thead>
    <tbody>
        @php
        $i = 0;
        @endphp

        @foreach ($mostAbsentStudent as $absent)
        <tr class="txt-danger" style="color:red">
            <td style="{{ $absent['count'] > 2 ? 'color:red' : '' }}">{{++$i}}.</td>
            <td style="{{ $absent['count'] > 2 ? 'color:red' : '' }}">{{ $absent['student']->force_number?? '' }}
                {{ $absent['student']->first_name }} {{ $absent['student']->last_name }}</td>
            <td style="{{ $absent['count'] > 2 ? 'color:red' : '' }}">{{ $absent['student']->company->name }} -
                {{ $absent['student']->platoon }}</td>
            <td style="{{ $absent['count'] > 2 ? 'color:red' : '' }}">{{ $absent['count']}}</td>
        </tr>
        @endforeach

    </tbody>
</table>



<!-- Include Chart.js -->
<script src="/tps-smis/resources/assets/js/chart.js"></script>
<script>
var data = @json($graphData);
daily = data.dailyData;
weekly = data.weeklyData;
monthly = data.monthlyData;
leaves_monthly_count = data.monthly;
const dailyData = {
    labels: daily.dates, // X-axis labels
    datasets: [{
            label: 'Absents',
            data: daily.absents,
            backgroundColor: '#1E4093'
        },
        {
            label: 'Sick',
            data: daily.sick,
            backgroundColor: 'rgba(255, 0, 0, 0.7)'
        },
        {
            label: 'Leaves',
            data: data.daily,
            backgroundColor: 'rgba(12, 165, 106, 0.7)'
        },
        {
            label: 'Locked up',
            data: daily.lockUps,
            backgroundColor: 'orange'
        },
        {
            label: 'Absents Trends',
            data: daily.absents,
            type: 'line',
            fill: false,
            borderColor: 'rgba(2, 11, 131, 0.7)',
            tension: 0.1
        }, // Ensure correct dataset
        {
            label: 'Sick Trends',
            data: daily.sick,
            type: 'line',
            fill: false,
            borderColor: 'rgba(187, 91, 91, 0.7)',
            tension: 0.1,
            hidden: false
        },
        {
            label: 'Leaves Trend',
            data: data.daily,
            type: 'line',
            fill: false,
            borderColor: 'rgba(2, 131, 82, 0.7)',
            tension: 0.1
        }, // Fix reference
        {
            label: 'Lock Up Trends',
            data: daily.lockUps,
            type: 'line',
            fill: false,
            borderColor: 'rgba(152, 94, 18, 0.7)',
            tension: 0.1,
            hidden: false
        }
    ]
};

const weeklyData = {
    labels: weekly.weeks,
    datasets: [{
            label: 'Absents',
            data: weekly.absents,
            backgroundColor: '#1E4093'
        },
        {
            label: 'Sick',
            data: weekly.sick,
            backgroundColor: 'rgba(255, 0, 0, 0.7)'
        },
        {
            label: 'Leaves',
            data: data.weekly,
            backgroundColor: 'rgba(12, 165, 106, 0.7)'
        },
        {
            label: 'Locked up',
            data: weekly.lockUps,
            backgroundColor: 'orange'
        },
        {
            label: 'Absents Trends',
            data: weekly.absents,
            type: 'line',
            fill: false,
            borderColor: 'rgba(2, 11, 131, 0.7)',
            tension: 0.1
        },
        {
            label: 'Sick Trends',
            data: weekly.sick,
            type: 'line',
            fill: false,
            borderColor: 'rgba(187, 91, 91, 0.7)',
            tension: 0.1,
            hidden: false
        },
        {
            label: 'Leaves Trend',
            data: data.daily,
            type: 'line',
            fill: false,
            borderColor: 'rgba(2, 131, 82, 0.7)',
            tension: 0.1
        },
        {
            label: 'Lock Up Trends',
            data: weekly.lockUps,
            type: 'line',
            fill: false,
            borderColor: 'rgba(152, 94, 18, 0.7)',
            tension: 0.1,
            hidden: false
        }
    ]
};

const monthlyData = {
    labels: monthly.months,
    datasets: [{
            label: 'Absents',
            data: monthly.absents,
            backgroundColor: '#1E4093'
        },
        {
            label: 'Sick',
            data: monthly.sick,
            backgroundColor: 'rgba(255, 0, 0, 0.7)'
        },
        {
            label: 'Leaves',
            data: leaves_monthly_count,
            backgroundColor: 'rgba(12, 165, 106, 0.7)'
        },
        {
            label: 'Locked up',
            data: monthly.lockUps,
            backgroundColor: 'orange'
        },
        {
            label: 'Absents Trends',
            data: monthly.absents,
            type: 'line',
            fill: false,
            borderColor: 'rgba(2, 11, 131, 0.7)',
            tension: 0.1
        },
        {
            label: 'Sick Trends',
            data: monthly.sick,
            type: 'line',
            fill: false,
            borderColor: 'rgba(187, 91, 91, 0.7)',
            tension: 0.1,
            hidden: false
        },
        {
            label: 'Leaves Trend',
            data: leaves_monthly_count,
            type: 'line',
            fill: false,
            borderColor: 'rgba(2, 131, 82, 0.7)',
            tension: 0.1
        },
        {
            label: 'Lock Up Trends',
            data: monthly.lockUps,
            type: 'line',
            fill: false,
            borderColor: 'rgba(152, 94, 18, 0.7)',
            tension: 0.1,
            hidden: false
        }
    ]
};

// Initialize Chart
const ctx = document.getElementById('groupedBarChart').getContext('2d');
let chart = new Chart(ctx, {
    type: 'bar',
    data: dailyData, // Default to daily data
    options: {
        responsive: true,
        scales: {
            x: {
                stacked: false,
                title: {
                    display: true,
                    text: 'Dates' // Default label for the X-axis
                }
            },
            y: {
                stacked: false,
                title: {
                    display: true,
                    text: 'Counts' // Default label for the Y-axis
                },
                ticks: {
                    stepSize: 1,
                    beginAtZero: true,
                    callback: function(value) {
                        return value.toFixed(0);
                    }
                },
                // Dynamically set the max value of the y-axis to be higher than the highest bar
                suggestedMax: Math.max(...daily.absents, ...daily.sick, ...daily.lockUps) *
                1.5, // 20% more than the highest value
            }
        },
        layout: {
            padding: {
                top: 30, // Adds space at the top of the chart area
                bottom: 10,
                left: 10,
                right: 10
            }
        }
    }
});


function updateAxisLabels(dataType) {
    switch (dataType) {
        case 'daily':
            chart.data = dailyData;
            chart.options.scales.x.title.text = 'Dates'; // X-axis label for daily data
            chart.options.scales.y.title.text = 'Counts'; // Y-axis label for daily data
            break;
        case 'weekly':
            chart.data = weeklyData;
            chart.options.scales.x.title.text = 'Weeks'; // X-axis label for weekly data
            chart.options.scales.y.title.text = 'Counts'; // Y-axis label for weekly data
            break;
        case 'monthly':
            chart.data = monthlyData;
            chart.options.scales.x.title.text = 'Months'; // X-axis label for monthly data
            chart.options.scales.y.title.text = 'Counts'; // Y-axis label for monthly data
            break;
        default:
            break;
    }

    chart.update(); // Update the chart to reflect the new labels and data
}

function showDaily() {
    chart.data = dailyData;
    updateAxisLabels('daily');
}

function showWeekly() {
    chart.data = weeklyData;
    updateAxisLabels('weekly');
}

function showMonthly() {
    chart.data = monthlyData;
    updateAxisLabels('monthly');
}
</script>
@endsection
