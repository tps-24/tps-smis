@extends('layouts.main')

@section('style')
<!-- style starts -->
    <!-- Steps Wizard CSS -->
    <link rel="stylesheet" href="/tps-smis/resources/assets/vendor/wizard/wizard.css" />

<style>
    @media only screen and (min-width: 576px) {
        #pfno {
            margin-left:12.5% !important;
            background-color:red;
        }
    }

    @media only screen and (max-width: 600px) {
        .abcd{
            font-size:15px !important;
        }
    }
</style>
<!-- style ends -->
@endsection
@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Staffs</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Register Staff</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->
@endsection
@section('content')
<!-- Row starts -->
<div class="row gx-4">
    <div class="col-sm-12 col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-left">
                            <!-- <h2>Create New Programme</h2> -->
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-primary btn-sm mb-2 backbtn" href="{{ route('staffs.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    </div>
                @endif
            <form method="POST" action="{{ route('staffs.store') }}">
                @csrf

                    <!-- Wizard #2 -->
                    <div id="smartwizard2">
                      <ul class="nav">
                        <li class="nav-item abcd">
                          <a class="nav-link" href="#step-2a">
                            <div class="num">1</div>
                            Personal Details
                          </a>
                        </li>
                        <li class="nav-item abcd">
                          <a class="nav-link" href="#step-2b">
                            <span class="num">2</span>
                            Proffessional Qualifications
                          </a>
                        </li>
                        <li class="nav-item abcd">
                          <a class="nav-link" href="#step-2c">
                            <span class="num">3</span>
                            Next of Kin Details
                          </a>
                        </li>
                        <!-- <li class="nav-item abcd">
                          <a class="nav-link " href="#step-2d">
                            <span class="num">4</span>
                            Preview & Submit
                          </a>
                        </li> -->
                      </ul>

                      <div class="tab-content">
                        <div id="step-2a" class="tab-pane" role="tabpanel" aria-labelledby="step-2a">
                            <!-- Row starts -->
                            <div class="row gx-4">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="row" style="margin-bottom:-1%">
                                            <div class="col-sm-3 col-12" id="pfn0" >
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                        <label class="form-label abcd" for="abc">PF Number/Force Number</label>
                                                        <input type="text" class="form-control" id="forceNumber" name="forceNumber" placeholder="Enter PF Number/Force Number" value="{{old('forceNumber')}}" required>
                                                    </div>
                                                    @error('forceNumber')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>                                            
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label" for="abc4">Rank</label>
                                                    <select class="form-select" id="rank" name="rank" aria-label="Default select example">
                                                        <option selected disabled>Choose Rank</option>
                                                        <option value="PC" {{ old('rank', 'default_value') == 'PC' ? 'selected' : '' }}>Police Constable (PC)</option>
                                                        <option value="CPL" {{ old('rank', 'default_value') == 'CPL' ? 'selected' : '' }}>Corporal (CPL)</option>
                                                        <option value="SGT" {{ old('rank', 'default_value') == 'SGT' ? 'selected' : '' }}>Sergeant (SGT)</option>
                                                        <option value="S/SGT" {{ old('rank', 'default_value') == 'S/SGT' ? 'selected' : '' }}>Staff Sergeant (S/SGT)</option>
                                                        <option value="SM" {{ old('rank', 'default_value') == 'SM' ? 'selected' : '' }}>Sergeant Major (SM)</option>
                                                        <option value="A/INSP" {{ old('rank', 'default_value') == 'A/INSP' ? 'selected' : '' }}>Assistant Inspector of Police (A/INSP)</option>
                                                        <option value="INSP" {{ old('rank', 'default_value') == 'INSP' ? 'selected' : '' }}>Inspector of Police (INSP)</option>
                                                        <option value="ASP" {{ old('rank', 'default_value') == 'ASP' ? 'selected' : '' }}>Assistant Superitendent of Police (ASP)</option>
                                                        <option value="SP" {{ old('rank', 'default_value') == 'SP' ? 'selected' : '' }}>Superitendent of Police (SP)</option>
                                                        <option value="SSP" {{ old('rank', 'default_value') == 'SSP' ? 'selected' : '' }}>Senior Superitendent of Police (SSP)</option>
                                                        <option value="ACP" {{ old('rank', 'default_value') == 'ACP' ? 'selected' : '' }}>Assistant Commissioner of Police (ACP)</option>
                                                        <option value="SACP" {{ old('rank', 'default_value') == 'SACP' ? 'selected' : '' }}>Senior Assistant Commissioner of Police (SACP)</option>
                                                        <option value="DCP" {{ old('rank', 'default_value') == 'DCP' ? 'selected' : '' }}>Deputy Commissioner of Police (DCP)</option>
                                                        <option value="CP" {{ old('rank', 'default_value') == 'CP' ? 'selected' : '' }}>Commissioner of Police (CP)</option>
                                                        <option value="IGP" {{ old('rank', 'default_value') == 'IGP' ? 'selected' : '' }}>Inspector General of Police (IGP)</option>
                                                    </select>
                                                    </div>
                                                    @error('rank')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">National Identification Number</label>
                                                    <input type="text" class="form-control" id="nin" name="nin" placeholder="Enter NIDA Number" value="{{old('nin')}}">
                                                    </div>
                                                    @error('nin')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <div class="m-0">
                                                                <label class="form-label abcd" for="abc">Company </label>
                                                                <select class="form-control" name="company_id" id="" >
                                                                    <option value="" selected disaled>company</option>
                                                                    @foreach ($companies as $company)
                                                                        <option value="{{$company->id}}">{{$company->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="row" style="margin-bottom:-1%">
                                            <div class="col-sm-3 col-12" style="margin-bottom:-1%">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">First Name</label>
                                                    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter First Name" value="{{old('firstName')}}" required>
                                                    </div>
                                                    @error('firstName')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-12" style="margin-bottom:-1%">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">Middlename</label>
                                                    <input type="text" class="form-control" id="middleName"  name="middleName" placeholder="Enter Middle Name" value="{{old('middleName')}}" required>
                                                    </div>
                                                    @error('middleName')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-12" style="margin-bottom:-1%">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">Lastname (Surname)</label>
                                                    <input type="text" class="form-control" id="lastName"  name="lastName" placeholder="Enter Last Name" value="{{old('lastName')}}" required>
                                                    </div>
                                                    @error('lastName')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-12" style="margin-bottom:-1%">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label" for="abc4">Gender</label>
                                                    <select class="form-select" id="gender" name="gender" aria-label="Default select example">
                                                        <option selected disabled>Choose gender</option>
                                                        <option value="Male" {{ old('gender', 'default_value') == 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ old('gender', 'default_value') == 'Female' ? 'selected' : '' }}>Female</option>
                                                    </select>
                                                    </div>
                                                    @error('gender')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">Date of Birth</label>
                                                    <input type="date" class="form-control" id="DoB" name="DoB" placeholder="Enter Date of Birth" max="2007-07-15"  value="{{old('DoB')}}">
                                                    </div>
                                                    @error('DoB')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>                                                                                        
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label" for="abc4">Marital Status</label>
                                                    <select class="form-select" id="maritalStatus" name="maritalStatus" aria-label="Default select example">
                                                        <option selected disabled>Choose Marital Status</option>
                                                        <option value="Single" {{ old('maritalStatus', 'default_value') == 'Single' ? 'selected' : '' }}>Single</option>
                                                        <option value="Married" {{ old('maritalStatus', 'default_value') == 'Married' ? 'selected' : '' }}>Married</option>
                                                        <option value="Divorsed" {{ old('maritalStatus', 'default_value') == 'Divorsed' ? 'selected' : '' }}>Divorsed</option>
                                                        <option value="Complicated" {{ old('maritalStatus', 'default_value') == 'Complicated' ? 'selected' : '' }}>Complicated</option>
                                                    </select>
                                                    </div>
                                                    @error('maritalStatus')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">Religion</label>
                                                    <input type="text" class="form-control" id="religion"  name="religion" placeholder="Enter religion" value="{{old('religion')}}">
                                                    </div>
                                                    @error('religion')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">Tribe</label>
                                                    <input type="text" class="form-control" id="tribe" name="tribe" placeholder="Enter tribe" value="{{old('tribe')}}">
                                                    </div>
                                                    @error('tribe')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-12" style="margin-top:-1%">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">Phone Number</label>
                                                    <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Enter phone number" value="{{old('phoneNumber')}}">
                                                    </div>
                                                    @error('phoneNumber')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-12" style="margin-top:-1%">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">Email Address</label>
                                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address"  value="{{old('email')}}" required>
                                                    </div>
                                                    @error('email')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-12" style="margin-top:-1%">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">Current Address</label>
                                                    <input type="text" class="form-control" id="currentAddress" name="currentAddress" placeholder="Enter current address" value="{{old('currentAddress')}}">
                                                    </div>
                                                    @error('currentAddress')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-12" style="margin-top:-1%">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">Permanent Address</label>
                                                    <input type="text" class="form-control" id="permanentAddress" name="permanentAddress" placeholder="Enter permanent address" value="{{old('permanentAddress')}}">
                                                    </div>
                                                    @error('permanentAddress')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Row ends -->
                        </div>
                        <div id="step-2b" class="tab-pane" role="tabpanel" aria-labelledby="step-2b">
                            <!-- Row starts -->
                            <div class="row gx-4">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="row" style="margin-bottom:-1%">                                   
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label" for="abc4">Department</label>
                                                    <select class="form-select" id="department_id" name="department_id" aria-label="Default select example">
                                                        <option selected disabled>Choose Department</option>                                 
                                                            @foreach ($departments as $value => $dep)
                                                                <option value="{{ $dep->id }}">
                                                                    {{ $dep->departmentName }}
                                                                </option>
                                                            @endforeach
                                                    </select>
                                                    </div>
                                                    @error('department_id')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">Designation</label>
                                                    <input type="text" class="form-control" id="designation" name="designation" placeholder="Enter designation" value="{{old('designation')}}">
                                                    </div>
                                                    @error('designation')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>                                  
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label" for="abc4">Role</label>
                                                    <select multiple class="form-control select 2" id="exampleFormControlSelect2" name="roles[]" aria-label="Default select example">
                                                        <option selected disabled>Choose role</option>  
                                                            @foreach ($roles as $value => $label)
                                                                <option value="{{ $value }}">
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                    </select>
                                                    </div>
                                                    @error('roles[]')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>                                                                                        
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label" for="abc4">Education Level</label>
                                                    <select class="form-select" id="educationLevel" name="educationLevel" aria-label="Default select example">
                                                        <option selected disabled>Choose Education Level</option>
                                                        <option value="std7" {{ old('educationLevel', 'default_value') == 'std7' ? 'selected' : '' }}>Darasa la Saba</option>
                                                        <option value="4m4" {{ old('educationLevel', 'default_value') == '4m4' ? 'selected' : '' }}>Form Four</option>
                                                        <option value="4m6" {{ old('educationLevel', 'default_value') == '4m6' ? 'selected' : '' }}>Form Six</option>
                                                        <option value="Certificate" {{ old('educationLevel', 'default_value') == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                                                        <option value="Diploma" {{ old('educationLevel', 'default_value') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                                        <option value="Degree" {{ old('educationLevel', 'default_value') == 'Degree' ? 'selected' : '' }}>Bachelor Degree</option>
                                                        <option value="Masters" {{ old('educationLevel', 'default_value') == 'Masters' ? 'selected' : '' }}>Masters</option>
                                                        <option value="PhD" {{ old('educationLevel', 'default_value') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                                    </select>
                                                    </div>
                                                    @error('educationLevel')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">                                                                                        
                                            <div class="col-lg-3 col-sm-6 col-12" style="margin-left:12.5%;">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label" for="abc4">Contract Type</label>
                                                    <select class="form-select" id="contractType" name="contractType" aria-label="Default select example">
                                                        <option selected disabled>Choose Contract Type</option>
                                                        <option value="Permanent" {{ old('contractType', 'default_value') == 'Permanent' ? 'selected' : '' }}>Permanent Contract</option>
                                                        <option value="Temporary" {{ old('contractType', 'default_value') == 'Temporary' ? 'selected' : '' }}>Temporary Contract</option>
                                                        <option value="Fixed-Term" {{ old('contractType', 'default_value') == 'Fixed-Term' ? 'selected' : '' }}>Fixed-Term Contract</option>
                                                        <option value="Probationary" {{ old('contractType', 'default_value') == 'Probationary' ? 'selected' : '' }}>Probationary Contract</option>
                                                    </select>
                                                    </div>
                                                    @error('contractType')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">Date of Joining</label>
                                                    <input type="date" class="form-control" id="joiningDate" name="joiningDate" value="{{old('joiningDate')}}">
                                                    </div>
                                                    @error('joiningDate')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">Location</label>
                                                    <input type="text" class="form-control" id="location" name="location" placeholder="Enter Location" value="{{old('location')}}">
                                                    </div>
                                                    @error('location')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Row ends -->
                        </div>
                        <div id="step-2c" class="tab-pane" role="tabpanel" aria-labelledby="step-2c">
                            <!-- Row starts -->
                            <div class="row gx-4">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="row" style="margin-bottom:-1%">
                                            <div class="col-sm-3 col-12">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">Full Names</label>
                                                    <input type="text" class="form-control" id="nextofkinFullname" name="nextofkinFullname" placeholder="Enter Next of Kin Fullname" value="{{old('nextofkinFullname')}}">
                                                    </div>
                                                    @error('nextofkinFullname')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">Relationship</label>
                                                    <input type="text" class="form-control" id="nextofkinRelationship" name="nextofkinRelationship" placeholder="Enter Next of Kin Relationship" value="{{old('nextofkinRelationship')}}">
                                                    </div>
                                                    @error('nextofkinRelationship')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">Phone Number</label>
                                                    <input type="text" class="form-control" id="nextofkinPhoneNumber" name="nextofkinPhoneNumber" placeholder="Enter Next of Kin Phone Number" value="{{old('nextofkinPhoneNumber')}}">
                                                    </div>
                                                    @error('nextofkinPhoneNumber')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="m-0">
                                                    <label class="form-label abcd" for="abc">Physical Address</label>
                                                    <input type="text" class="form-control" id="nextofkinPhysicalAddress" name="nextofkinPhysicalAddress" placeholder="Enter Next of Kin Physical Address" value="{{old('nextofkinPhysicalAddress')}}">
                                                    </div>
                                                    @error('nextofkinPhysicalAddress')
                                                        <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Row ends -->
                        </div>
                        <!-- <div id="step-2d" class="tab-pane" role="tabpanel" aria-labelledby="step-2d"> -->
                            <!-- Row starts -->
                            <!-- <div class="row gx-4">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="row abcd" style="margin-bottom:-1%">
                                            

                                            <h3>Preview & Submit</h3>
                                            <div id="preview-personal-details">Blah blah</div>
                                            <div id="preview-professional-qualifications"></div>
                                            <div id="preview-next-of-kin-details"></div>


                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <!-- Row ends -->
                        <!-- </div> -->
                        
                    </div>


              <!-- Include optional progress bar HTML -->
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                      
                    <input type="number" name="created_by" value="{{ Auth::user()->id }}" class="form-control" hidden>          
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center" id="btnSubmit" style="margin-bottom:0px;">
                        <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
                    </div>

                    </div>

                  </div>
                </div>
              </div>
            </div>
            <!-- Row ends -->

            </form>
            </div>
        </div>
     
    </div>
</div>
@endsection
@section('scripts')
<!-- scripts starts -->
    <!-- Steps wizard JS -->
    <script src="/tps-smis/resources/assets/vendor/wizard/wizard.min.js"></script>
    <script src="/tps-smis/resources/assets/vendor/wizard/wizard-custom.js"></script>
    
<!-- scripts ends -->
@endsection