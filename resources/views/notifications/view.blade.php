@extends('layouts.main')
@section('scrumb')
  <!-- Scrumb starts -->
  <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page"><a href="#">Notification View</a></li>
      </ol>
    </nav>
    </div>
  </nav>
  <!-- Scrumb ends -->

@endsection

@section('content')
  @if($category == 1)
    @php
@endphp

@if($notification)
    <div class="mb-4 d-flex gap-2">
        @if ($notification->expires_at > \Carbon\Carbon::now())
            <img style="width: 50px; margin-top: -10px;" 
                 src="{{ asset('resources/assets/images/new_blinking.gif') }}" 
                 alt="new gif">
        @endif
        <h4 class="text-{{ $notification->type }}">{{ $notification->title }}</h4>
    </div>

    <p class="ms-3">{{ $notification->message }}</p>

    @if($notification->document_path)
        <a style="text-decoration: underline; color:blue; font-style:italic"
           href="{{ route('download.file', ['documentPath' => $announcement->id]) }}">
            <small>Download Attachment</small>
        </a>
    @endif

    <p>
        <small>
            Announced by: 
            <i>{{ $notification->poster?->staff?->rank }} {{ $notification->poster?->name }}</i>
        </small>
    </p>

    <small>
        Posted At: {{ $notification->created_at?->format('d-m-Y H:i') ?? 'N/A' }}
    </small><br>

    @if ($notification->expires_at < \Carbon\Carbon::now())
        <small>
            Expired At: {{ $notification->expires_at?->format('d-m-Y H:i') ?? 'N/A' }}
        </small>
    @else
        <small>
            Expires At: {{ $notification->expires_at?->format('d-m-Y H:i') ?? 'N/A' }}
        </small>
    @endif

    @can('notification-create')
        @if($notification->created_at->gt(\Carbon\Carbon::now()->subHours(2)))
            <div class="d-flex justify-content-end mt-3">
                <div class="btn-group">
                    <a href="{{ route('announcements.edit', $notification->id) }}" class="me-2">
                        <button class="btn btn-sm btn-primary">Edit</button>
                    </a>
                    <form id="deleteForm{{ $notification->id }}" 
                          action="{{ route('announcements.destroy', $notification->id) }}" 
                          method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="confirmDelete('deleteForm{{ $notification->id }}','notification')" 
                                type="button" 
                                class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </div>
            @include('layouts.sweet_alerts.confirm_delete')
        @endif
    @endcan
@endif

  @elseif($category == 2)

    <div class="notification-card">
    <h3 class="text-{{ $notification->type ?? 'primary' }}">
    {{ $notification->title }}
    </h3><br><br>

    <p>
    <strong>Name:</strong> {{ $notification->student->force_number ?? '' }} {{ $notification->student->rank ?? '' }}
    {{ $notification->student->first_name }} {{ $notification->student->last_name }}
    </p>

    <p>
    <strong>Company:</strong>
    {{ $notification->student->company->name }} - {{ $notification->student->platoon }}
    </p>

    <p>
    <strong>Description:</strong><br>
    {{ $notification->description }}
    </p>

    <p>
    <strong>Locked at:</strong>
    {{ \Carbon\Carbon::parse($notification->arrested_at)->format('h:i A, d F Y') }}
    </p>

    @if ($notification->released_at)
    <p>
    <strong>Released at:</strong>
    {{ \Carbon\Carbon::parse($notification->released_at)->format('h:i A, d F Y') }}
    </p>
    @endif
    </div>

  @endif
@endsection