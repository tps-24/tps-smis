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
      $notification = \App\Models\Announcement::find($notification->data['id']);
    @endphp
    <div class="mb-4 d-flex">
      @if ($notification->expires_at > \Carbon\Carbon::now())
      <img style="width: 50px; margin-top: -10px;" src="{{ asset('resources/assets/images/new_blinking.gif') }}"
      alt="new gif">
    @endif
      <h4 class="text-{{ $notification->type }}">{{ $notification->title }}</h4>
    </div>
    <p> &nbsp &nbsp &nbsp{{ $notification->message }}</p>
    @if($notification->document_path)
    <a style="text-decoration: underline; color:blue; font-style:italic"
      href="{{route('download.file', ['documentPath' => $notification->id]) }}"><small>Download
      Attachment</small></a>
    @endif
    <p><small>Announced by: <i>{{ $notification->poster?->staff?->rank }} {{ $notification->poster->name }} </i></small>
    </p>
    <small>Posted At:
      {{ $notification->created_at ? $notification->created_at->format('d-m-Y H:i') : 'N/A' }}</small><br>

    @if ($notification->expires_at < \Carbon\Carbon::now())
    <small> Expired At: {{ $notification->expires_at ? $notification->expires_at->format('d-m-Y H:i') : 'N/A' }}</small>
    @else
    <small>Expires At: {{ $notification->expires_at ? $notification->expires_at->format('d-m-Y H:i') : 'N/A' }}</small>
    @endif
    </div>
    @can('announcement-create')
    @if($notification->created_at->gt(\Carbon\Carbon::now()->subHours(2)))
    <div class="btn-group">
    <a style="margin-right: 10px;" href="{{ route('announcements.edit', $notification->id) }}"><button
      class="btn btn-sm btn-primary">Edit</button></a>
    <form id="deleteForm{{ $notification->id }}" action="{{ route('announcements.destroy', $notification->id) }}"
      method="POST" style="display:inline;">
      @csrf
      @method('DELETE')
      <button onclick="confirmDelete('deleteForm{{ $notification->id }}','notification')" type="button"
      class="btn btn-sm btn-danger">Delete</button>
    </form>
    </div>
    @include('layouts.sweet_alerts.confirm_delete')
    @endif
    @endcan
    </li>

    </div>
  @elseif($category == 2)
    @php
    $student = \App\Models\Student::find($notification->data['student_id']);
    @endphp
    <div class="notification-card">
    <h3 class="text-{{ $notification->type ?? 'primary' }}">
    {{ $notification->title }}
    </h3><br><br>

    <p>
    <strong>Name:</strong> {{ $student->force_number ?? '' }} {{ $student->rank ?? '' }}
    {{ $student->first_name }} {{ $student->last_name }}
    </p>

    <p>
    <strong>Company:</strong>
    {{ $student->company->name }} - {{ $student->platoon }}
    </p>

    <p>
    <strong>Description:</strong><br>
    {{ $notification->data['description'] }}
    </p>

    <p>
    <strong>Locked at:</strong>
    {{ \Carbon\Carbon::parse($notification->data['arrested_at'])->format('h:i A, d F Y') }}
    </p>

    @if ($notification->data['released_at'])
    <p>
    <strong>Released at:</strong>
    {{ \Carbon\Carbon::parse($notification->data['released_at'])->format('h:i A, d F Y') }}
    </p>
    @endif
    </div>

  @endif
@endsection