<!-- resources/views/beats/pdf.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Duty Roster for {{ $company->name }} on {{ $date }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>RATIBA YA MALINDO {{ strtoupper($company->name) }}</h1>
            <h2>TAREHE {{ $date }}</h2>
        </div>

       
        @php
            $timeSlots = [
                '06:00 - 12:00',
                '12:00 - 18:00',
                '18:00 - 00:00',
                '00:00 - 06:00'
            ];
        @endphp

 <h2>Guard Areas</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ENEO LA LINDO</th> 
                    @foreach($timeSlots as $slot) 
                    <th>{{ $slot }}</th>
                    <th>PLT</th> 
                    @endforeach
                </tr>
            </thead>
            <tbody> 
                
                @foreach($company->guardAreas as $area)
                <td>{{ $area->name }}</td>
                        <tr>
                            @foreach($timeSlots as $slot)
                            
                        dump($slot);
                                    @php
                                        $beat = $area->beats->firstWhere('start_at', $slot);
                                        
                                        $students = collect(); // Default empty collection

                                        $studentIds = is_array($beat->student_ids) ? $beat->student_ids : json_decode($beat->student_ids, true);
                                        $students = \App\Models\Student::whereIn('id', $studentIds)->get();
                                    @endphp
                                    @foreach($students as $student)
                                    <tr>
                                        <td>
                                            {{ $student->first_name }} {{ $student->last_name }} (PLT {{ $student->platoon }}) (Gender: {{ $student->gender }})
                                        </td>
                                        <td>{{ $student->platoon }}</td>
                                    </tr>
                                    @endforeach
                            @endforeach
                        </tr>
                @endforeach
            </tbody>
        </table>



        <div class="page-break"></div>

        <h2>Patrol Areas</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ENEO LA LINDO</th>
                    @foreach($timeSlots as $slot)
                        <th>{{ $slot }}</th>
                        <th>PLT</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($company->patrolAreas as $area)
                    <tr>
                        <td>{{ $area->start_area }} - {{ $area->end_area }}</td>
                        @foreach($timeSlots as $slot)
                            @php
                                $beat = $area->beats->firstWhere('start_at', '=', $slot);
                                $studentNames = '';
                                $platoonNumbers = '';
                                if ($beat) {
                                    $studentIds = is_array($beat->student_ids) ? $beat->student_ids : json_decode($beat->student_ids, true);
                                    $students = \App\Models\Student::whereIn('id', $studentIds)->get();
                                    foreach ($students as $student) {
                                        $studentNames .= htmlspecialchars($student->first_name . ' ' . $student->last_name . ' (Gender: ' . $student->gender . ')') . ', ';
                                        $platoonNumbers .= htmlspecialchars('PLT ' . $student->platoon) . ', ';
                                    }
                                    // Remove trailing commas and spaces
                                    $studentNames = rtrim($studentNames, ', ');
                                    $platoonNumbers = rtrim($platoonNumbers, ', ');
                                }
                            @endphp
                            <td>{{ $studentNames }}</td>
                            <td>{{ $platoonNumbers }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
