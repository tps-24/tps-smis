@extends('layouts.main')
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/tps-rms/" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="/tps-rms/students/">Students</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">List</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->

@endsection
</table>
@section('content')
<div class="row">
  @session('success')
    <div class="alert alert-success" role="alert">
    {{ $value }}
    </div>
  @endsession
  <div class="row">
    <div class="col-6">
      <form method="POST" action="{{url('students/bulkimport')}}" style="display:inline" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <input style="height: 30px; width: 50%" required type="file" name="import_file" class="form-control mb-2">
        <button title="Upload by CSV/excel file" type="submit" class="btn btn-primary btn-sm">Upload
          Students</i></button>
      </form>
    </div>
    <div class="col-3">
      <!-- <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#SearchStudent">search
        student</button> -->
    </div>
    <!-- <div class="col col-lg-2">
      <a class="btn btn-success btn-sm mb-2" href="{{url('students/create')}}">Create Student</a>
    </div> -->
      <div class="col-3 pull-right" >
          <a class="btn btn-success mb-2" href="{{ url('students/create') }}" style="float:right !important; margin-right:-22px"><i class="fa fa-plus"></i> Create New Student</a>
      </div>
  </div>
</div>
<div class="modal fade" id="SearchStudent" tabindex="-1" aria-labelledby="createNewContactLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header flex-column">
        <div class="text-center">
          <h4 class="text-primary">Search Student</h4>
        </div>
      </div>
      <div class="modal-body">
        <form action="">
          <div class="row">
            <div class="col">Search Criteria: </span></div>
            <div class="col">
              <select onchange="changeCriteria()" style="height: 30px;" class="form-select" name="company" id="criteria" required
                aria-label="Default select example">
                <option value="plt">Platoon</option>
                <option value="names">Names</option>
              </select>
            </div>
          </div>
          <div class="row" id="platoonCriteria">
            <div class="col mt-2">
              <label class="form-label " for="abc4">Platoon:</label>
            </div>
            <div class="col mt-2">
              <select style="height: 30px;" class="form-select" name="company" id="abc4" required
                aria-label="Default select example">
                <option>COY</option>
                <option value="HQ">HQ</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
              </select>
            </div>
            <div class="col mt-2" >
              <select style="height: 30px;" class="form-select" name="company" id="abc4" required
                aria-label="Default select example">
                <option>PLT</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
              </select>
            </div>
          </div>
          <div class="row mt-2" id="namesCriteria" style="display: none;">
            <div class="col">
            <label class="form-label" for="abc">Names</label>
            </div>
          <div class="col">
            <input type="text" class="form-control" id="names" name="names"
            required placeholder="Enter students names">
            </div>
          </div>
        <div class="modal-footer mt-2">
        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
          Close
        </button>
        <button type="submit" class="btn btn-primary btn-sm">Search</i></button>
        </div>
        </form>
      </div>

      <!-- <div class="col-6">
        <a class="btn btn-success mb-2" href="{{url('students/create')}}">Create Student</a>
      </div> -->
    </div>
  </div>
</div>

<div class="card-body">
  <div class="table-outer">
    <div class="table-responsive">
      <table class="table table-striped truncate m-0">
        <thead>
          <tr>
            <th>No</th>
            <th>Force Number</th>
            <th>Name</th>
            <th>Company</th>
            <th>Platoon</th>
            <th>Phone</th>
            <th>Home Region</th>
            <th width="280px">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 0;?>
          @foreach ($students as $key => $student)

        <tr>
        <td>{{++$i}}</td>
        <td>{{$student->force_number ?? ''}}</td>
        <td>{{$student->first_name}} {{$student->middle_name}} {{$student->last_name}}</td>
        <td>{{$student->company}}</td>
        <td>{{$student->platoon}}</td>
        <td>{{$student->phone}}</td>
        <td>{{$student->home_region}}</td>
        <td>
          <a class="btn btn-info btn-sm" href="{{ route('students.show', $student->id) }}">
          Show</a>
          <a class="btn btn-primary btn-sm" href="{{ route('students.edit', $student->id) }}">Edit</a>
          <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
          data-bs-target="#createNewContact{{$student->id}}">Delete</button>
          <div class="modal fade" id="createNewContact{{$student->id}}" tabindex="-1"
          aria-labelledby="createNewContactLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header flex-column">
              <div class="text-center">
              <h4 class="text-danger">Delete Student</h4>
              </div>
            </div>
            <div class="modal-body">
              <h5>You are about to delete {{$student->first_name}} {{$student->middle_name}}
              {{$student->last_name}}.
              </h5>
              <p>Please confirm to delete.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
              Cancel
              </button>
              <form method="POST" action="{{url('students/' . $student->id . '/delete')}}"
              style="display:inline">
              @csrf
              @method('POST')
              <button type="submit" class="btn btn-danger btn-sm">Confirm</i></button>
              </form>
            </div>
            </div>
          </div>
          </div>
        </td>
        
        </tr>
      @endforeach
        </tbody>
      </table>
    </div>
  </div>
  
</div>

{!! $students->links('pagination::bootstrap-5') !!}
<script>
  changeCriteria(){
    alert("Hello");
    var criteriaDoc = document.getElementById('criteria').innerHTML;
    if(criteriaDoc.value == "plt"){
      document.getElementById('platoonCriteria').style.display.block;
      document.getElementById('namesCriteria').style.display.none;
    }elseif(criteriaDoc.value == "names"){
      document.getElementById('namesCriteria').style.display.block;
      document.getElementById('platoonCriteria').style.display.none;
    }
  } 
</script>
@endsection
