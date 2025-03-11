@extends('layouts.main')

@section('content')
<!-- Row starts -->
<div class="row gx-4" id="dashboardContent">
    @include('dashboard.partials.dashboard_content', compact('denttotalCount', 'dentpresentCount', 'totalStudentsInBeats', 'patientsCount', 'staffsCount', 'beatStudentPercentage'))
</div>
<!-- Row ends -->

<!-- Row starts -->
<div class="row gx-4">
    <div class="col-xxl-9 col-sm-12 col-12">
        <div class="card mb-4 card-height-420">
            <div class="card-header">
                <h5 class="card-title">Grouped Bar Graph</h5>
            </div>
            <div class="card-body">

            <div class="graph-body auto-align-graph">
                

<!-- Include Bootstrap and Chart.js -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h3>Students Status</h3> <br>
            <select class="form-select" id="timeRange" onchange="updateChart()">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="custom">Custom</option>
            </select>
            <div class="mt-3" id="customDateRange" style="display: none;">
                <input type="date" id="startDate" class="form-control mb-2" onchange="updateChart()">
                <input type="date" id="endDate" class="form-control" onchange="updateChart()">
            </div>
            <canvas id="studentsStatusChart"></canvas>
        </div>
    </div>
</div>


<script>
    let chart;
    const ctx = document.getElementById('studentsStatusChart').getContext('2d');

    function updateChart() {
        const timeRange = document.getElementById('timeRange').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        // AJAX request to get data from the server
        fetch(`/dashboard/data?timeRange=${timeRange}&startDate=${startDate}&endDate=${endDate}`)
            .then(response => response.json())
            .then(data => {
                if (chart) {
                    chart.destroy();
                }
                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                label: 'Absents',
                                data: data.absents,
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Sick',
                                data: data.sick,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Locked Up',
                                data: data.lockedUp,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
    }



            
                <div id="sales"></div>
            </div>

            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6 col-12">
    <div class="card mb-4 card-height-420">
        <div class="card-header">
        <h5 class="card-title">Events per Coy</h5>
        </div>
        <div class="card-body">

        <div class="d-flex flex-column justify-content-between h-100">

            <!-- Transactions starts -->
            <div class="d-flex flex-column gap-3">
            <div class="d-flex pb-3 border-bottom w-100">
                <div class="icon-box lg bg-primary-subtle rounded-5 me-3">
                <i class="bi bi-twittr fs-3 text-primary"></i>
                </div>
                <div class="d-flex flex-column">
                <p class="mb-1 opacity-50">Blaah Blaah </p>
                <h3 class="m-0 lh-1 fw-semibold">159</h3>
                </div>
            </div>
            <div class="d-flex pb-3 border-bottom w-100">
                <div class="icon-box lg bg-info-subtle rounded-5 me-3">
                <i class="bi bi-xbx fs-3 text-info"></i>
                </div>
                <div class="d-flex flex-column">
                <p class="mb-1 opacity-50">Blaah Blaah</p>
                <h3 class="m-0 lh-1 fw-semibold">36</h3>
                </div>
            </div>
            <div class="d-flex pb-3 border-bottom w-100">
                <div class="icon-box lg bg-danger-subtle rounded-5 me-3">
                <i class="bi bi-youtbe fs-3 text-danger"></i>
                </div>
                <div class="d-flex flex-column">
                <p class="mb-1 opacity-50">Blaah Blaah</p>
                <h3 class="m-0 lh-1 fw-semibold">23</h3>
                </div>
            </div>
            </div>
            <!-- Transactions ends -->

            <a href="javascript:void(0)" class="btn btn-dark">View All <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>

        </div>
    </div>
    </div>  
</div>
<!-- Row ends -->

<!-- Row starts -->
<div class="row gx-4" >
    <div class="col-xxl-12">
    <div class="card" style="height: 150px !important">
        <div class="card-body">
        
        </div>
    </div>
    </div>
</div>
<!-- Row ends -->
 
<script>
    document.getElementById('sessionProgramme').addEventListener('change', function() {
        var selectedProgrammeId = this.value;
        var url = "{{ route('dashboard.content') }}?session_programme_id=" + selectedProgrammeId;

        fetch(url)
            .then(response => response.text())
            .then(html => {
                document.getElementById('dashboardContent').innerHTML = html;
            })
            .catch(error => console.error('Error loading data:', error));
    });
</script>
@endsection