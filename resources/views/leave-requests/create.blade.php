@extends('layouts.main')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <h2 class="mb-4 text-center">
        <i class="bi bi-calendar-check"></i> Request Leave
    </h2>

    <div class="card shadow p-4">
        <form action="{{ route('leave-requests.store') }}" method="POST">
            @csrf
            
            <!-- Start & End Date in One Row -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">
                        <i class="bi bi-calendar-event"></i> Start Date
                    </label>
                    <input type="date" name="start_date" id="start_date" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">
                        <i class="bi bi-calendar-event-fill"></i> End Date
                    </label>
                    <input type="date" name="end_date" id="end_date" class="form-control" required>
                </div>
            </div>

            <!-- Reason with Character Counter -->
            <div class="mb-3">
                <label for="reason" class="form-label">
                    <i class="bi bi-chat-text"></i> Reason
                </label>
                <textarea name="reason" id="reason" class="form-control" required rows="3"></textarea>
                <small class="text-muted">
                    <span id="charCount">0</span>/500 characters
                </small>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-send"></i> Submit Request
            </button>
        </form>
    </div>
</div>

<!-- JavaScript for Enhancements -->
<script>
    // Prevent selecting past dates
    document.addEventListener("DOMContentLoaded", function() {
        let today = new Date().toISOString().split('T')[0];
        document.getElementById("start_date").setAttribute("min", today);
        document.getElementById("end_date").setAttribute("min", today);
    });

    // Live Character Counter for Reason Field
    document.getElementById("reason").addEventListener("input", function() {
        let count = this.value.length;
        document.getElementById("charCount").textContent = count;
        if (count > 500) {
            this.value = this.value.substring(0, 500);
            document.getElementById("charCount").textContent = "500 (Max)";
        }
    });
</script>

@endsection
