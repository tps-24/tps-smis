<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ $date }}-{{ $company->name }}</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .watermark {
            position: fixed;
            top: 45%;
            left: 50%;
            width: 200px;
            height: 200px;
            opacity: 0.2;
            transform: translate(-50%, -50%);
            z-index: -1;
        }
        .page-break {
            page-break-after: always;
        }
        body {
            padding: 20px; /* You can adjust the padding value as needed */
        }
    </style>
</head>

<body>
    <div class="watermark">
        <img src="{{ asset('resources/assets/images/logo.png') }}" alt="Watermark">
    </div>
    <center>
        <h4>TANZANIA POLICE SCHOOL - MOSHI</h4>
        <div class="container" style="margin-top:-10px;">
            <div class="header">
                <div style="text-align: center;">
                    <img src="{{ asset('resources/assets/images/logo.png') }}" style="height:60 !important; width:50"
                        alt="Police Logo">
                </div>

                @php
                    use App\Models\Student;
                    $date = Carbon\Carbon::parse("$date")
                @endphp
                <h4> {{ strtoupper($company->name) }} STATE {{ $date->format('d/m/Y')}}</h4>
            </div>
            <div class="table-container">
                <table class="page-break">
                    <thead>
                        <tr>
                            <th>Platoon</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Sentry</th>
                            <th>Safari</th>
                            <th>Off</th>
                            <th>Messy</th>
                            <th>Sick</th>
                            <th>ME</th>
                            <th>KE</th>
                            <th>Jumla</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $absent_students = [];
                            $total_present = 0;
                            $total_absent = 0;
                            $total_sentry = 0;
                            $total_safari = 0;
                            $total_off = 0;
                            $total_messy = 0;
                            $total_sick = 0;
                            $total_male = 0;
                            $total_female = 0;
                            $grand_total = 0;
                        @endphp
                        @foreach ($company->platoons as $platoon)
                                                @php
                                                    $attendance = $platoon->attendences()->whereDate('created_at', $date)->get();
                                                    if (count($attendance) > 0) {
                                                        $total_present += $attendance[0]->present;
                                                        $total_absent += $attendance[0]->absent;
                                                        $total_sentry += $attendance[0]->sentry;
                                                        $total_messy += $attendance[0]->messy;
                                                        $total_off += $attendance[0]->off;
                                                        $total_sick += $attendance[0]->sick;
                                                        $total_male += $attendance[0]->male;
                                                        $total_female += $attendance[0]->female;
                                                        $grand_total += $attendance[0]->total;
                                                        $absent_ids = explode(",", $attendance[0]->absent_student_ids);
                                                        for ($i = 0; $i < count($absent_ids); ++$i) {
                                                            array_push($absent_students, Student::find($absent_ids[$i]));
                                                        }
                                                    }      
                                                @endphp
                                                <tr>
                                                    <td>{{ $platoon->name }}</td>
                                                    <td>{{ $attendance[0]->present ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->absent ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->sentry ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->safari ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->off ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->messy ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->sick ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->male ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->female ?? '-' }}</td>
                                                    <td>{{ $attendance[0]->total ?? '-' }}</td>
                                                </tr>
                        @endforeach
                        @php

                        @endphp
                        <tr style="font-weight: bold;">
                            <td>TOTAL</td>
                            <td>{{ $total_present }}</td>
                            <td>{{ $total_absent }}</td>
                            <td>{{ $total_sentry }}</td>
                            <td>{{ $total_safari }}</td>
                            <td>{{ $total_off }}</td>
                            <td>{{ $total_messy }}</td>
                            <td>{{ $total_sick }}</td>
                            <td>{{ $total_male }}</td>
                            <td>{{ $total_female }}</td>
                            <td>{{ $grand_total }}</td>

                        </tr>
                    </tbody>
                </table>



            </div>
        </div>
    </center>
    <div class="table-container" style="width: 50%;">
        <center> <h4>Absent Students</h4></center>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Names</th>
                    <th>Platoon</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < count($absent_students); $i++)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $absent_students[$i]->first_name }} {{ $absent_students[$i]->middle_name }} {{ $absent_students[$i]->last_name }}</td>
                        <td>{{ $absent_students[$i]->platoon }}</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</body>

</html>