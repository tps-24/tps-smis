@extends('layouts.main')

</table>
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-right">
                    <a class="btn btn-success mb-2" href="{{url('students/create')}}"><i class="bi bi-file-earmark-plus-fill"></i></a>
        </div>
    </div>
</div>

@session('success')
    <div class="alert alert-success" role="alert"> 
        {{ $value }}
    </div>
@endsession
<div class="card-body">
                    <div class="table-outer">
                      <div class="table-responsive">
                      <table class="table table-striped truncate m-0">
                          <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Force Number</th>
                                <th>Company</th>
                                <th>Platoon</th>
                                <th>Phone</th>
                                <th>Home Region</th>
                                <th width="280px">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php $i =0;?>
                          @foreach ($students as $key => $student)

                                <tr>
                                    <td>{{++$i}}</td>
                                    <td>{{$student->first_name}} {{$student->last_name}}</td>
                                    <td>{{$student->force_number}}</td>
                                    <td>{{$student->company}}</td>
                                    <td>{{$student->platoon}}</td>
                                    <td>{{$student->phone}}</td>
                                    <td>{{$student->home_region}}</td>
                                    <td>
                                    <a class="btn btn-info btn-sm" href=<?php echo("students/".$student->id."/show")?>><i class="fa-solid fa-list"></i> Show</a>
                                    <a class="btn btn-primary btn-sm" href="{{url('students/'.$student->id.'/edit')}}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                    <form method="POST" action="{{url('students/'.$student->id.'/delete')}}" style="display:inline">
                                            @csrf
                                            @method('POST')

                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
{!! $students->links('pagination::bootstrap-5') !!}
<p class="text-center text-primary"><small>TPS-RMS</small></p>

@endsection