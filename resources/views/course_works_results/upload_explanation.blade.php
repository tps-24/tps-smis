@extends('layouts.main')
@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Course Work Results</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Upload explanations</a></li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex gap-2 float-end" style="">


        <form method="POST"  action="{{ route('coursework.upload', $courseId) }}" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="d-flex gap-2  justify-content-end">
                <!-- Semester Select -->
                <select style="width: 30%" name="semesterId" id="semesters" class="form-control" required>
                    <option value="" selected disabled>Select semester</option>
                    @foreach ($semesters as $semester)
                        <option value="{{ $semester->id }}">{{ $semester->semester_name }}</option>
                    @endforeach
                </select>

                <!-- Coursework Select -->
                <select style="width: 30%" name="courseworkId" id="courseworks" class="form-control" required>
                    <option value="" selected disabled>Select coursework</option>
                </select>

                <div class="d-flex gap-2">
                    <!-- File Upload -->
                    <input   type="file" name="import_file" class="form-control mb-2" required style="width:100%">
                    <button style="width: 100%" type="submit" class="btn btn-primary" style="height: 40px;">
                        <i class="bi bi-upload"></i>&nbsp Upload Coursework
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Fetch courseworks when semester is selected
        document.getElementById('semesters').addEventListener('change', function () {
            var semesterId = this.value;
            var courseworkSelect = document.getElementById('courseworks');
            courseworkSelect.innerHTML = '<option value="">Select coursework</option>'; // Clear previous options

            if (semesterId) {
                fetch(`/tps-smis/courseworks/${semesterId}`)
                    .then(response => response.json())
                    .then(courseworks => {
                        courseworks.forEach(coursework => {
                            var option = document.createElement('option');
                            option.value = coursework.id;
                            option.text = coursework.coursework_title;
                            courseworkSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching courseworks:', error));
            }
        });
    </script>
         <a href="{{ route('courseworkResultDownloadSample') }}">
            <button  class="btn btn-s btn-success">
                <i class="bi bi-download"></i> &nbspSample
            </button>
        </a>
<div class="mt-3">
    <p>Please download the sample uploading excel file and review your coursework before submitting. If you encounter any issues, feel free to contact support.</p>
</div>
@endsection
