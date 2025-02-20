@extends('layouts.main')

@section('content')
<div class="container">
    

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Preview</th>
                <th>Title</th>
                <th>Category</th>
                <th>Download</th>
            </tr>
        </thead>
        <tbody>
            @foreach($downloads as $download)
                <tr>
                    <!-- Preview File -->
                    <td>
                        @php
                            $extension = pathinfo($download->file_path, PATHINFO_EXTENSION);
                        @endphp

                        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                            <!-- Image Preview -->
                            <img src="{{ asset('storage/' . $download->file_path) }}" width="50" height="50" alt="Preview">
                        @elseif(in_array($extension, ['pdf']))
                            <!-- PDF Preview -->
                            <iframe src="{{ asset('storage/' . $download->file_path) }}" width="100" height="50"></iframe>
                        @elseif(in_array($extension, ['mp4', 'avi', 'mov']))
                            <!-- Video Preview -->
                            <video width="100" height="50" controls>
                                <source src="{{ asset('storage/' . $download->file_path) }}" type="video/{{ $extension }}">
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <!-- Default Icon for Other Files -->
                            <i class="fas fa-file"></i>
                        @endif
                    </td>

                    <td>{{ $download->title }}</td>
                    <td>{{ $download->category }}</td>
                    <td>
                        <a href="{{ route('downloads.file', basename($download->file_path)) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
