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
    @session('success')
        <div class="alert alert-success alert-dismissible " role="alert">
            {{ $value }}
        </div>
    @endsession
    <div class="d-flex justify-content-between">
        <a href="{{ route('courseworkResultDownloadSample') }}"><button style="height: 30px;" class="btn btn-sm btn-success"><i
                    class="bi bi-download"></i>Download sample</button></a>

        <form method="POST" action="{{route('coursework.upload', $courseId)}}" style="display:inline" enctype="multipart/form-data"
            style="float:right;">
            @csrf
            @method('POST')
            <div class="d-flex gap-2">
            <select style="width: 20%; height:30px;" name="semesterId" id="semesters" class="form-control" required>
                <option value="" selected disabled> semister</option>
                @foreach ($semesters as $semester)
                    <option value="{{ $semester->id }}">{{ $semester->semester_name }}</option>
                @endforeach
            </select>
            <select style="width: 20%; height:30px;" name="courseworkId" id="courseworks" class="form-control" required>
                <option value="" selected disabled> coursework</option>
            </select>
        
            <div class="d-flex gap-2" style="float:right;">
                <input style="height: 30px; width: 60%" required type="file" name="import_file" class="form-control mb-2">
                <button style="height: 30px;" title="Upload by CSV/excel file" type="submit" class="btn btn-primary btn-sm"> <i class="bi bi-upload"></i>Upload
                        Coursework</i></button>
            </div>
            </div>
        </form>
    </div>
    <script>
    document.getElementById('semesters').addEventListener('change', function () {
        var semesterId = this.value;
        var courseworkSelect = document.getElementById('courseworks');
        courseworkSelect.innerHTML = '<option value="" disabled>coursework</option>'; // Clear previous options
        var link = '/tps-smis/courseworks/' + semesterId;
        if (semesterId) {
            fetch(link)
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
    <div>
        Maelekezo
    </div>
@endsection