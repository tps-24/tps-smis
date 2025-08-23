@extends('layouts.main')

@section('style')

@endsection
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb" style="margin-right: 25px;">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/tps-smis/" id="homee">Home</a></li>
                <li class="breadcrumb-item"><a href="/tps-smis/students/">Students</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Posts</a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->
 @endsection
@section('content')
@include('layouts.sweet_alerts.index')
<div class="card-body">
    @can('post-create')
        <div class="text-end">
            <a href="{{ route('students-post.create') }}" class="btn btn-success btn-sm">Upload Post</a>
        </div>        
    @endcan

    @if ($posts->isEmpty())
    <h3>No posts available for current session.</h3>
    @else
    <div>
        <h2>Students Posts</h2>
    </div>
        <div class="d-flex justify-content-center">
    <form class="d-flex" action="{{ route('students-post.search') }}" method="GET">
        @csrf
        <div class="d-flex">
            <!-- Name Search -->
            <input type="text" 
                   value="{{ request('name') }}" 
                   class="form-control me-2" 
                   name="name"
                   placeholder="name(option)">
            
            <!-- Company Dropdown -->
            <select onchange="this.form.submit()" class="form-select me-2" name="company_id">
                <option value="" selected disabled>Select Company</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" 
                        {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
            
            <!-- Platoon Dropdown -->
            <select onchange="this.form.submit()" class="form-select me-2" name="platoon">
                <option value="" selected disabled>Select Platoon</option>
                @for ($i = 1; $i < 15; $i++)
                    <option value="{{ $i }}" {{ request('platoon') == $i ? 'selected' : '' }}> 
                        {{ $i }}
                    </option>
                @endfor
            </select>
        </div>
    </form>
</div>

    <div class="table-outer">
        <div class="table-responsive">
            <table class="table table-striped truncate m-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Phone</th>
                        <th>Post</th>
                        <th>Action</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $key => $post)
                    <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td>{{ $post->student->force_number }} {{ $post->student->rank }}  {{ $post->student->first_name }} {{ $post->student->last_name }}</td>
                    <td>{{ $post->student->company->name }} - {{ $post->student->platoon }}</td>
                    <td>{{ $post->student->phone }}</td>
                    <td>{{ $post->region }}  {{ $post->district? '- '. $post->district: '' }}  {{ $post->unit? '- '. $post->unit: ''  }} {{ $post->office? '- '. $post->office: ''  }}</td>
                    <td class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#postModal{{ $post->id }}">
                            More
                        </button>
                        <a class="btn btn-sm btn-primary" href="{{ route('students.show', $post->student_id) }}">Profile</a>
                    </td>
                    <div class="modal fade" id="postModal{{ $post->id }}" tabindex="-1" aria-labelledby="postModalLabel{{ $post->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="postModalLabel{{ $post->id }}">Post Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Student :</strong> {{ $post->student->force_number }} {{ $post->student->rank }}  {{ $post->student->first_name }} {{ $post->student->last_name }}</p>
                                <p><strong>Region:</strong> {{ ucfirst($post->region) }}</p>
                                <p><strong>District:</strong> {{ $post->district }}</p>
                                <p><strong>Unit:</strong> {{ $post->unit }}</p>
                                <p><strong>Office:</strong> {{ $post->office }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                            </div>
                        </div>
                    </div>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
{!! $posts->appends(request()->query())->links('pagination::bootstrap-5') !!}
@endsection