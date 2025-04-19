<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leave Request Form</title>
    <style>
        @page {
            margin: 50px;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            position: relative;
         border: 2px double #000; 
            padding: 30px;
            /* background-image: url('{{ public_path('logo.png') }}'); */
            background-size: 300px 300px;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.95;


            
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            width: 80px;
            height: 80px;
        }
        .header h2 {
            margin: 10px 0 5px;
        }
        .header p {
            margin: 0;
            font-size: 13px;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .details th, .details td {
            padding: 8px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }
        .signatures {
            margin-top: 50px;
        }
        .signatures table {
            width: 100%;
            text-align: center;
            margin-top: 20px;
        }
        .signatures img {
            width: 120px;
            height: auto;
        }
        .stamp {
            position: absolute;
            bottom: 100px;
            right: 100px;
            opacity: 0.3;
        }
        .stamp img {
            width: 150px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
        }
        .watermark {
            position: fixed;
            top: 45%;
            left: 45%;
            width: 45%;
            height: 45%;
            opacity: 0.1;
            transform: translate(-50%, -50%);
            z-index: -1;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('logo.png') }}" alt="Tanzania Police Logo">
        <h2>Tanzania Police School - TPS MOSHI</h2>
        <p><strong>Leave Request Form</strong></p>
        <p><strong>Generated on:</strong> {{ now()->format('d M Y') }}</p>
    </div>

    <div class="details">
    <img src="{{ public_path('logo.png') }}" class="watermark" alt="Watermark">
        <table>
            <tr>
                <th>Student Name:</th>
                <td>{{ $leaveRequest->student->first_name }} {{ $leaveRequest->student->middle_name }}  {{ $leaveRequest->student->last_name }}</td>
            </tr>
            <tr>
                <th>Phone Number:</th>
                <td>{{ $leaveRequest->phone_number }}</td>
            </tr>
            <tr>
                <th>Company:</th>
                <td>{{ $leaveRequest->company->name }}  Platoon:{{ $leaveRequest->platoon }}</td>
            </tr>
            <!-- <tr>
                <th>Platoon:</th>
                <td>{{ $leaveRequest->platoon }}</td>
            </tr> -->
            <tr>
                <th>Location:</th>
                <td>{{ $leaveRequest->location }}</td>
            </tr>
            <tr>
                <th>Reason for Leave:</th>
                <td>{{ $leaveRequest->reason }}</td>
            </tr>
            <tr>
                <th>Start Date:</th>
                <td>{{ $leaveRequest->start_date ?? '-' }} </td>
            </tr>
            <tr>
                <th>End Date:</th>
                <td>{{ $leaveRequest->end_date ?? '-' }}</td>
            </tr>
           
        </table>
    </div>

    <!-- Signature Area -->
    <div class="signature-section">

    <!-- <p>Imetolewa na:</p> -->
    <img src="{{ public_path('signatures/oc.png') }}" width="150" alt="Signature">
    <div>Chief Instructor TPS - MOSHI</div>
    <br>Date: {{ now()->format('d M Y') }}
</div>
       
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Tanzania Police Force. All rights reserved.</p>
    </div>

</body>
</html>


