@extends('layouts.main')

@section('scrumb')
    <!-- Scrumb starts -->
    <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/tps-smis/" id="home">Home</a></li>
                    <li class="breadcrumb-item active"><a href="#">Announcements</a>
                    </li>
                </ol>
            </nav>
        </div>
    </nav>
    <!-- Scrumb ends -->

@endsection
@section('content')
@include('layouts.sweet_alerts.index')


    @can('announcement-create')  
    <div style="display: flex; justify-content: end;">
        <a href="{{ route('announcements.create') }}"><button class="btn btn-sm btn-success">New</button></a>
    </div>
    @endcan()
    <div class="card">
        <div class="card-body">

            @if ($announcements->isEmpty())
                <h2>No announcements.</h2>
            @else
                <ul class="list-group">
                    @foreach ($announcements as $announcement)
                        <li class="list-group-item d-flex justify-content-between align-items-center mt-2">
                            <div>
                                <div class="mb-4">
                                    <h4 class="text-{{ $announcement->type }}">{{ $announcement->title }}</h4>
                                </div>
                                <p> &nbsp &nbsp &nbsp{{ $announcement->message }}</p>
                                @if($announcement->document_path)
                                    <a style="text-decoration: underline; color:blue; font-style:italic"
                                        href="{{route('download.file', ['documentPath' => $announcement->id]) }}"><small>Download
                                            Attachment</small></a>
                                @endif
                                <p><small>Announced by: <i>{{ $announcement->poster->name }}</i></small></p>
                                <small>Posted At:
                                    {{ $announcement->created_at ? $announcement->created_at->format('d-m-Y H:i') : 'N/A' }}</small><br>
                                <small>Expires At:
                                    {{ $announcement->expires_at ? $announcement->expires_at->format('d-m-Y H:i') : 'N/A' }}</small>

                            </div>
                            @if($announcement->created_at->gt(\Carbon\Carbon::now()->subHours(2)))
                                <div class="btn-group">
                                    <a style="margin-right: 10px;" href="{{ route('announcements.edit', $announcement->id) }}"><button
                                            class="btn btn-sm btn-primary">Edit</button></a>
                                    <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <script>



        </script>
@endsection