<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ public_path('css/main.min.css') }}">
        <link rel="stylesheet" href="{{ public_path('css/custom.css') }}">
        <link rel="stylesheet" href="/tps-smis/resources/assets/fonts/bootstrap/bootstrap-icons.min.css" />
        <link rel="stylesheet" href="/tps-smis/resources/assets/css/main.min.css" />
        <link rel="stylesheet" href="/tps-smis/resources/assets/css/custom.css" />
        <title>Document</title>

        <style>
        .page-break {
            page-break-inside: auto;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        table {
            width: 100%;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
            height: 1%;
        }

        table#t01 {
            width: 100%;
            background-color: #f2f2d1;
        }
        </style>
    </head>
</head>

<body>

    <table style="border-collapse: collapse; border: none;">
        <tr>
            <td style="width: 80%; border: none;">
                <center>
                    <h2> {{$staff->forceNumber}} {{$staff->rank}}
                        {{substr($staff->firstName,0,1)}}.{{substr($staff->middleName,0,1)}} {{$staff->lastName}}</h2>
                </center>
            </td>
            @if($staff->photo)
            <td style="width: 20%; border: none;"> <img src="{{ url('storage/app/public/'.$staff->photo) }}"
                    alt="profile_photo" srcset="" width="100" height="100"></td>
            @endif
        </tr>
    </table>

    <div class="">
        <h3>(A) PERSONAL PARTICULARS</h3>
        <table class="table table-sm table-bordered">
        <tbody>
            <tr>
                <th>Surname</th>
                <td>{{$staff->lastName}}</td>
            </tr>
            <tr>
                <th>First Name</th>
                <td>{{$staff->firstName}}</td>
            </tr>
            <tr>
                <th>Middle Name</th>
                <td>{{$staff->middleName}}</td>
            </tr>
            <tr>
                <th>Sex</th>
                <td>{{$staff->gender}}</td>
            </tr>
            <tr>
                <th>Date of Birth</th>
                <td>{{$staff->DoB}}</td>
            </tr>
            <tr>
                <th>Nationality</th>
                <td>{{$staff->nationality}}</td>
            </tr>
            <tr>
                <th>Marital Status</th>
                <td>{{$staff->maritalStatus}}</td>
            </tr>
            <!-- father's details -->
            @php
                    $fatherParticulars = $staff->fatherParticulars == null? null :
                    json_decode($staff->fatherParticulars);
                    @endphp
            <tr>
                <th>Father's Names</th>
                <td>{{$fatherParticulars[0]?? null}}</td>
            </tr>
            <tr>
                <th rowspan="4">Father's place of birth</th>
                <td><strong>Village: </strong>{{$fatherParticulars[1]?? null}}</td>
            </tr>
            <tr>
                <td><strong>Ward: </strong>{{$fatherParticulars[2]?? null}}</td>
            </tr>
            <tr>
                <td><strong>District: </strong>{{$fatherParticulars[3]?? null}}</td>
            </tr>
            <tr>
                <td><strong>Region: </strong>{{$fatherParticulars[4]?? null}}</td>
            </tr>

            <!-- mother's details -->
            @php    
                $motherParticulars = $staff->motherParticulars == null? null :
                json_decode($staff->motherParticulars);//dd($motherParticulars );
            @endphp
            <tr>
                <th>Mother's Names</th>
                <td>{{$motherParticulars[0]?? null}}</td>
            </tr>
            <tr>
                <th rowspan="4">Mother's place of birth</th>
                <td><strong>Village: </strong>{{$motherParticulars[1]?? null}}</td>
            </tr>
            <tr>
                <td><strong>Ward: </strong>{{$motherParticulars[2]?? null}}</td>
            </tr>
            <tr>
                <td><strong>District: </strong>{{$motherParticulars[3]?? null}}</td>
            </tr>
            <tr>
                <td><strong>Region: </strong>{{$motherParticulars[4]?? null}}</td>
            </tr>

            <!-- current parent address -->
            @php
                $parentsAddress = $staff->parentsAddress == null? null :
                json_decode($staff->parentsAddress);
                @endphp
            <tr>
                <th rowspan="4">Parent current address</th>
                <td><strong>Village: </strong>{{$parentsAddress[0]?? null}}</td>
            </tr>
            <tr>
                <td><strong>Ward: </strong>{{$parentsAddress[1]?? null}}</td>
            </tr>
            <tr>
                <td><strong>District: </strong>{{$parentsAddress[2]?? null}}</td>
            </tr>
            <tr>
                <td><strong>Region: </strong>{{$parentsAddress[3]?? null}}</td>
            </tr>

            <tr>
                <th>Place of domicile(District)</th>
                <td>{{ $staff->PoD }}</td>
            </tr>

            <tr>
                <th>Languages</th>
                <td>{{$staff->language}}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{{$staff->currentAddress}}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{$staff->email}}</td>
            </tr>
            <tr>
                <th>Mobile</th>
                <td>{{$staff->phoneNumber}}</td>
            </tr>
        </tbody>
    </table>

        <h3>(B) EDUCATION AND TRAINING</h3>
        <h3>1. Primary Schools</h3>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>Name of school</th>
                    <th>Village</th>
                    <th>District</th>
                    <th>Year of Admission</th>
                    <th>Year of graduation</th>
                </tr>
            </thead>
            <tbody>
                @if ($staff->schools)
                @foreach ($staff->schools as $school)
                @if ($school->education_level_id == 1)
                <tr>
                    <td>{{ $school->name }}</td>
                    <td>{{ $school->village }}</td>
                    <td>{{ $school->district }}</td>
                    <td>{{ $school->admission_year }}</td>
                    <td>{{ $school->graduation_year }}</td>
                </tr>
                @endif
                @endforeach
                @endif
            </tbody>
        </table>

        <h3>2. Secondary Schools</h3>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>Name of school</th>
                    <th>Village</th>
                    <th>District</th>
                    <th>Year of Admission</th>
                    <th>Year of graduation</th>
                </tr>
            </thead>
            <tbody>
                @if ($staff->schools)
                @foreach ($staff->schools as $school)
                @if ($school->education_level_id == 2 || $school->education_level_id == 3)
                <tr>
                    <td>{{$school->education_level->name}}</td>
                    <td>{{ $school->name }}</td>
                    <td>{{ $school->village }}</td>
                    <td>{{ $school->district }}</td>
                    <td>{{ $school->admission_year }}</td>
                    <td>{{ $school->graduation_year }}</td>
                </tr>
                @endif
                @endforeach
                @endif
            </tbody>
        </table>

        <h3>3. Colleges</h3>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>College/University</th>
                    <th>Duration</th>
                    <th>Region/country</th>
                    <th>Award</th>
                </tr>
            </thead>
            <tbody>
                @if ($staff->schools)
                @foreach ($staff->schools as $school)
                @if ($school->education_level_id == 4)
                <tr>
                    <td>{{ $school->name }}</td>
                    <td>{{ $school->duration }}</td>
                    <td>{{ $school->country }}</td>
                    <td>{{ $school->award }}</td>
                </tr>
                @endif
                @endforeach
                @endif
            </tbody>
        </table>

        <h3>(C) OTHER COURSES</h3>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <td></td>
                    <td>Duration</td>
                    <td>Theme and Award</td>
                    <td>College/Organization</td>
                    <td>Venue</td>
                </tr>

            </thead>
            <tbody>
                @if ($staff->schools)
                @foreach ($staff->schools as $school)
                @if ($school->education_level_id == 5)
                <tr>
                    <td></td>
                    <td>{{ $school->duration }}</td>
                    <td>{{ $school->award }}</td>
                    <td>{{ $school->name }}</td>
                    <td>{{ $school->venue }}</td>
                </tr>
                @endif
                @endforeach
                @endif
            </tbody>
        </table>

        <h3>(D) WORK AND EXPERIENCE</h3>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <td>S/NO</td>
                    <td>Year</td>
                    <td>Organization</td>
                    <td>Location</td>
                    <td>Position</td>
                    <td>Duties</td>
                </tr>

            </thead>
            <tbody>
                @php
                $i = 0;
                @endphp
                @if ($staff->work_experiences)
                @foreach ($staff->work_experiences as $work_experience)
                <tr>
                    <td>{{++$i}}</td>
                    <td>{{ substr($work_experience->start_date, 0, 4)}} - {{ substr($work_experience->end_date, 0, 4)}}
                    </td>
                    <td>{{ $work_experience->institution }}</td>
                    <td>{{ $work_experience->address }}</td>
                    <td>{{ $work_experience->position }}</td>
                    <td>
                        @php
                        $duties = $work_experience->duties == null? null :
                        json_decode($work_experience->duties);
                        @endphp
                        <ul>
                            @foreach ($duties as $duty)
                            <li>{{ $duty }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</body>

</html>