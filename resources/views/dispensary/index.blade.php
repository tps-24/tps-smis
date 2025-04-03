@extends('layouts.main')

@section('content')
<div class="col-sm-12">

    <!-- Filter Form -->
    <form method="GET" action="{{ route('dispensary.page') }}" class="d-flex mb-3">
        <select name="company_id" class="form-select me-2">
            <option value="">Select Company</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                    {{ $company->name }}
                </option>
            @endforeach
        </select>

        <select name="platoon" class="form-select me-2">
            <option value="">Select Platoon</option>
            @for ($i = 1; $i <= 15; $i++)
                <option value="{{ $i }}" {{ request('platoon') == $i ? 'selected' : '' }}>
                    {{ $i }}
                </option>
            @endfor
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Statistics Section -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">Statistics</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Daily Count -->
                <div class="col-md-4">
                    <h6>Daily Count</h6>
                    <p>{{ $dailyCount }} Patients</p>
                </div>

                <!-- Weekly Count -->
                <div class="col-md-4">
                    <h6>Weekly Count</h6>
                    <p>{{ $weeklyCount }} Patients</p>
                </div>

                <!-- Monthly Count -->
                <div class="col-md-4">
                    <h6>Monthly Count</h6>
                    <p>{{ $monthlyCount }} Patients</p>
                </div>
            </div>
        </div>
    </div>


    
    <!-- Pie Chart Section -->
    <div class="card">
    <div class="card-header">
        <h5 class="card-title">Patient Distribution for {{ now()->year }}</h5>
    </div>
    <div class="card-body d-flex justify-content-center">
        <canvas id="patientChart" style="max-width: 300px; max-height: 300px;"></canvas>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var ctx = document.getElementById('patientChart').getContext('2d');

        var patientData = {!! json_encode($patientDistribution) !!};
        var isCompanySelected = {!! json_encode($isCompanySelected ?? false) !!}; // Ensure it's always defined

        var labels = Object.keys(patientData);
        var data = Object.values(patientData);

        // Colors for companies
        var companyColors = {
            "HQ": "green",
            "A": "red",
            "B": "white",
            "C": "yellow"
        };

        // Assign labels and colors
        var chartLabels = isCompanySelected 
            ? labels.map(platoon => `Platoon ${platoon}`)  // Show platoon stats if a company is selected
            : labels.map(companyId => { 
                return (companyId == 1 ? "HQ" : (companyId == 2 ? "A" : (companyId == 3 ? "B" : "C")));
              });

        var backgroundColors = isCompanySelected 
            ? ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#8E44AD', '#E74C3C', '#3498DB', '#F1C40F', '#2ECC71', '#D35400'] // Random colors for platoons
            : labels.map(companyId => companyColors[(companyId == 1 ? "HQ" : (companyId == 2 ? "A" : (companyId == 3 ? "B" : "C")))]);

        if (labels.length > 0) {
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Patients',
                        data: data,
                        backgroundColor: backgroundColors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                font: { size: 10 }
                            }
                        }
                    }
                }
            });
        } else {
            ctx.font = "16px Arial";
            ctx.fillText("No data available", 100, 100);
        }
    });
</script>

@endsection


