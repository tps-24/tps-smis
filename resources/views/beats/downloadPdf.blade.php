<!DOCTYPE html>
<html lang="en">

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

<body>
    <?php 
            use Carbon\Carbon;
        ?>
    <center class="d-flex align-items-center justify-content-center">
        <div class="text-center">
            <h4><b>TANZANIA POLICE SCHOOL-MOSHI</b></h4>
            <img src="{{ public_path('logo.png') }}" style="height:50px; width:50px" alt="Logo"></br>
            <span>GUARDS AND PATROL</span></br>
        </div>
        <span>
            @if ($beatType_id == 1)
                GUARDS
            @else
                PATROL
            @endif 
        </br>
        </span>
            <span>{{$company->name}} Company</span></br>
            <span>Printed at : {{Carbon::now()}}</span>
    </center>

    <div  style="margin: 0 5%; 0 5% ">
        @for ($i = 0; $i < count($beatType_id == 1? $company->areas : $company->patrol_areas); ++$i)
                <?php
            $j = 0;
            if($beatType_id == 1){
                $beats = $company->areas[$i]->beats()->orderBy('start_at')
                            ->where('beatType_id', $beatType_id)    
                            ->whereDate('date', Carbon::tomorrow())
                            ->get();

            }else{
                $beats = $company->patrol_areas[$i]->beats()->orderBy('start_at')
                        ->where('beatType_id', $beatType_id)
                        ->whereDate('date', Carbon::tomorrow())                   
                        ->get();           
            }
            $cols_beats = collect($beats)->groupBy(function ($data) {
                return  $data->start_at->format('H:i:s');
             });

                        ?>
                @if (count($cols_beats) > 0)
                <span style="font-size: 102%;"><b>Area: 
                @if($beatType_id == 1)
                    {{$company->areas[$i]->name}}
                @else
                    {{$company->patrol_areas[$i]->start->name }} to {{$company->patrol_areas[$i]->end->name }}
                @endif
                    ,   {{Carbon::parse($beats[0]->date)->format('d-m-Y');}}
                </b></span>
                @endif
                <div class="">
                    @foreach($cols_beats as $beats)
                    <span>Time: {{$beats[0]->start_at ->format('H:i') }} - {{$beats[0]->end_at ->format('H:i') }}</span>
                        <table class="mb-3" style="margin-bottom: 50px;">
                        <thead>
                            <tr>
                                <th></th>
                                <th style="width: 300px;">Names</th>
                                <th style="width: 80px;">Rank</th>
                                <th style="width: 30px;">Platoon</th>
                            </tr>
                        </thead>
                        <tbody>
                        @for ($h = 0; $h < count($beats); ++$h)
                        <tr>
                            <td>{{++$j}}</td>
                        <td>{{$beats[$h]->student->first_name}} {{$beats[$h]->student->middle_name}} {{$beats[$h]->student->last_name}}</td>
                        <td>{{$beats[$h]->student->rank}}</td> 
                        <td>{{$beats[$h]->student->platoon}}</td>       
                        </tr>
                        
                        @endfor
                        <?php $j = 0; ?>
                        </tbody>
                        </table>
                    @endforeach
                    </div>
        @endfor

    </div>

</body>

</html>