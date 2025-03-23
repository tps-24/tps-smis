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
        <div class="card">
            <div class="card-header">
                <div class="container">
                    <h2>Grouped Bar Graph of Student Attendances</h2>
                    <div class="btn-group mb-3" role="group" aria-label="Filter options">
                        <button type="button" class="btn btn-primary" onclick="showDaily()">Daily</button>
                        <button type="button" class="btn btn-secondary" onclick="showWeekly()">Weekly</button>
                        <button type="button" class="btn btn-success" onclick="showMonthly()">Monthly</button>
                    </div>
                    <div class="chart-container" style="margin-top: 20px; padding: 20px; background-color: #f8f9fa; border-radius: 10px;">
                        <canvas id="groupedBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-12 col-12">
        <div class="card mb-4 card-height-420">
            <div class="card-header">
                <h5 class="card-title">Recent Announcements</h5>
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
                                <p class="mb-1 opacity-50">Blaah Blaah</p>
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
                    <a href="javascript:void(0)" class="btn btn-dark">View All <i class="bi bi-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Row ends -->

<!-- Row starts -->
<div class="row gx-4" style="margin-top:10px">
    <div class="col-xxl-12">
        <div class="card" style="height: 150px !important">
            <div class="card-body">
            </div>
        </div>
    </div>
</div>
<!-- Row ends -->

<!-- Chart.js -->
<script src="/tps-smis/resources/assets/js/chart.js"></script>
<script>
    function getDateRange(days) {
        const today = new Date();
        let dates = [];
        for (let i = 0; i < days; i++) {
            const date = new Date();
            date.setDate(today.getDate() - i);
            dates.push(date.toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' }));
        }
        return dates.reverse();
    }

    function getWeekRange(weeks) {
        const today = new Date();
        let weeksArray = [];
        for (let i = 0; i < weeks; i++) {
            const weekStart = new Date(today);
            weekStart.setDate(today.getDate() - (7 * i));
            weeksArray.push(`Week ${weekStart.getWeek()}`);
        }
        return weeksArray.reverse();
    }

    function getMonthRange(months) {
        const today = new Date();
        let monthsArray = [];
        for (let i = 0; i < months; i++) {
            const month = new Date(today);
            month.setMonth(today.getMonth() - i);
            monthsArray.push(month.toLocaleString('default', { month: 'long', year: 'numeric' }));
        }
        return monthsArray.reverse();
    }

    Date.prototype.getWeek = function () {
        const firstDayOfYear = new Date(this.getFullYear(), 0, 1);
        const pastDaysOfYear = (this - firstDayOfYear) / 86400000;
        return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
    };

    const dailyLabels = getDateRange(7);
    const weeklyLabels = getWeekRange(5);
    const monthlyLabels = getMonthRange(3);

    const dailyData = {
        labels: dailyLabels,
        datasets: [
            { label: 'Absents', data: [12, 9, 15, 11, 18, 14, 10], backgroundColor: 'rgba(255, 99, 132, 0.5)' },
            { label: 'Sick', data: [5, 6, 4, 7, 5, 6, 7], backgroundColor: 'rgba(54, 162, 235, 0.5)' },
            { label: 'Locked up', data: [2, 3, 1, 4, 3, 2, 1], backgroundColor: 'rgba(75, 192, 192, 0.5)' },
            { label: 'Trend Line', data: [8, 7, 10, 8, 12, 9, 8], type: 'line', fill: false, borderColor: 'rgba(0,0,0,0.7)', tension: 0.1 }
        ]
    };

    const weeklyData = {
        labels: weeklyLabels,
        datasets: [
            { label: 'Absents', data: [10, 20, 14, 18, 15], backgroundColor: 'rgba(255, 99, 132, 0.5)' },
            { label: 'Sick', data: [6, 7, 5, 6, 7], backgroundColor: 'rgba(54, 162, 235, 0.5)' },
            { label: 'Locked up', data: [2, 4, 3, 3, 4], backgroundColor: 'rgba(75, 192, 192, 0.5)' },
            { label: 'Trend Line', data: [8, 10, 8, 9, 9], type: 'line', fill: false, borderColor: 'rgba(0,0,0,0.7)', tension: 0.1 }
        ]
    };

    const monthlyData = {
        labels: monthlyLabels,
        datasets: [
            { label: 'Absents', data: [22, 25, 20], backgroundColor: 'rgba(255, 99, 132, 0.5)' },
            { label: 'Sick', data: [8, 7, 6], backgroundColor: 'rgba(54, 162, 235, 0.5)' },
            { label: 'Locked up', data: [5, 4, 5], backgroundColor: 'rgba(75, 192, 192, 0.5)' },
            { label: 'Trend Line', data: [21, 23, 20], type: 'line', fill: false, borderColor: 'rgba(0,0,0,0.7)', tension: 0.1 }
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
                    x: { stacked: false },
                    y: { stacked: false }
                }
            }
        });

        function showDaily() {
            chart.data = dailyData;
            chart.update();
        }

        function showWeekly() {
            chart.data = weeklyData;
            chart.update();
        }

        function showMonthly() {
            chart.data = monthlyData;
            chart.update();
        }
    </script>

@endsection