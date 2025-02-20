@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/tps-rms" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="">Beats</a></li>
<<<<<<< HEAD
        <li class="breadcrumb-item active"><a href="">Guards and Patrols</a></li>
=======
        <li class="breadcrumb-item active"><a href="">Area</a></li>
>>>>>>> 7d61e4df868b37df109c9a8e92bdee3250c6fbd9
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
<<<<<<< HEAD
  <!-- @session('success')
    <div class="alert alert-success" role="alert">
    {{ $value }}
    </div>
  @endsession -->

    @if(session('success'))
        <div style="color: green">{{ session('success') }}</div>
    @endif

<div class="container">


<h2>Beats for {{ $date }}</h2>
    
    <form action="{{ route('beats.byDate') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ $date }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>



    <ul class="nav nav-tabs" id="companyTabs" role="tablist">
        @foreach($companies as $company)
            <li class="nav-item" role="presentation">
                <button class="nav-link @if($loop->first) active @endif" id="tab-{{ $company->id }}" data-bs-toggle="tab" data-bs-target="#company-{{ $company->id }}" type="button" role="tab" aria-controls="company-{{ $company->id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ $company->description}} 
                </button>
            </li>
        @endforeach
    </ul>
    <div class="tab-content" id="companyTabContent">
        @foreach($companies as $company)
            <div class="tab-pane fade @if($loop->first) show active @endif" id="company-{{ $company->id }}" role="tabpanel" aria-labelledby="tab-{{ $company->id }}">
                <h3>Guard Areas</h3>
                @foreach($company->guardAreas as $area)
                    <div class="card my-3">
                        <div class="card-header">
                            {{ $area->name }}
                        </div>
                        <div class="card-body">
                            @foreach($area->beats as $beat)
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <strong>Beat Date:</strong> {{ $beat->date }}
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Start Time:</strong> {{ $beat->start_at }}
                                    </div>
                                    <div class="col-md-2">
                                        <strong>End Time:</strong> {{ $beat->end_at }}
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('beats.show', ['beat' => $beat->id]) }}" class="btn btn-primary">View Students</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <h3>Patrol Areas</h3>
                @foreach($company->patrolAreas as $area)
                    <div class="card my-3">
                        <div class="card-header">
                            {{ $area->start_area }} -  {{ $area->end_area }}
                        </div>
                        <div class="card-body">
                            @foreach($area->beats as $beat)
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <strong>Beat Date:</strong> {{ $beat->date }}
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Start Time:</strong> {{ $beat->start_at }}
                                    </div>
                                    <div class="col-md-2">
                                        <strong>End Time:</strong> {{ $beat->end_at }}
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('beats.show', ['beat' => $beat->id]) }}" class="btn btn-primary">View Students</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
        
    @if($companies->isEmpty())
        <p>No beats found for the selected date.</p>
    @endif
    </div>
</div>
=======
@session('success')
    <div class="alert alert-success" role="alert">
    {{ $value }}
    </div>
  @endsession
<!-- <div class="pull-right">
  <a class="btn btn-success mb-2" href="{{ url('students/create') }}"
    style="float:right !important; margin-right:-12px"><i class="fa fa-plus"></i> Generate Beats</a>
</div> -->
@if ($areas->isEmpty())

  <h5>No beats available.</h5>

@else
  <div class="table-responsive">
    <table class="table table-striped truncate m-0">
    <thead>
      <tr>
      <th>No</th>
      <th>Name</th>
      <th>Assigned</th>
      <th width="280px">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php  $i = 0;?>
      @foreach ($areas as $area)
      <tr>
      <td>{{++$i}}</td>
      <td>{{$area->name}}</td>
      <td>
      @if ($area->beats->isNotEmpty())
      Yes
    @else
      No
    @endif
      </td>
      <td>
      <button class="btn  btn-primary btn-sm" data-bs-toggle="modal"
      data-bs-target="#MoreAbsent{{$area->id}}">Edit</button>
      <div class="modal fade" id="MoreAbsent{{$area->id}}" tabindex="-1"
      aria-labelledby="statusModalLabelMore{{$area->id}}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="statusModalLabelMore">
        Edit {{$area->name}} area
        </h5>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{url('/beats/update/'.$area->id)}}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
        <div class="row">
          <div class="col-4">
          <label class="form-label" for="abc">Name </label>
          </div>
          <div class="col-8">
          <input type="text" value="{{$area->name}}" class="form-control" id="name" name="name"
          placeholder="Enter area name" value="{{old('name')}}">
          </div>
        </div>
        @error('name')
          <div class="error">{{ $message }}</div>
        @enderror
        <div class="row mt-2">
          <div class="col-4">
          <label class="form-label" for="abc">Company </label>
          </div>
          <div class="col-8">
            <select class="form-select" id="abc4" name="company" required aria-label="Default select example">
            <option selected disabled value="">select company</option>
              @foreach ($companies as $company)
              <option @if ($area->company_id == $company->id) selected @endif value="{{$company->id}}">{{$company->name}}</option>
              @endforeach
            </select>
          </div>
        @error('company')
          <div class="error">{{ $message }}</div>
        @enderror
        
        <div class="row mt-2 mb-2">
          <div class="col-4">
          <label class="form-label" for="abc">Guards </label>
          </div>
          <div class="col-8">
          <input type="number" min="2" value="{{$area->number_of_guards}}" class="form-control" id="number_of_guards" name="number_of_guards"
           value="{{old('number_of_guards')}}">
          </div>
        </div>
        @error('number_of_guards')
          <div class="error">{{ $message }}</div>
        @enderror
        <div class="modal-footer">
        <button type="submit" class="btn btn-sm btn-primary">Save</button>
        </div>
        </form>
        </div>
      </div>
    </div>
    </div>
      </div>
      @if ($area->beats->isNotEmpty())

      <a href="{{url('/beats/show_guards/' . $area->id)}}"><button class="btn btn-sm btn-info">View</button></a>
      <a href="{{ url('/beats/list-guards/' . $area->id) }}"><button
      class="btn btn-sm btn-primary">Approve</button></a>
    @endif
      </td>
      </tr>
    @endforeach
    </tbody>
    </table>
  </div>
@endif

>>>>>>> 7d61e4df868b37df109c9a8e92bdee3250c6fbd9
@endsection