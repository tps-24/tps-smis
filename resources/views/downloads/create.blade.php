@extends('layouts.main')

@section('content')
<div class="container">
    <h2>Upload a New File</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('downloads.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title">File Title:</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="category">Category:</label>
            <select name="category" class="form-control" required>
                <option value="Notice">Notice</option>
                <option value="Report">Report</option>
                <option value="Assignment">Assignment</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="file">Upload File:</label>
            <input type="file" name="file" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Upload File</button>
    </form>
</div>
@endsection
