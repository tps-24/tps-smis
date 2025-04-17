<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rejected Leave Request</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .section { margin-bottom: 15px; }
        .logo { width: 80px; height: auto; margin-bottom: 10px; }
        .signature-section {
            margin-top: 50px;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            width: 200px;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Logo + Header -->
    <div class="header">
        <img src="{{ public_path('logo.png') }}" class="logo" alt="Logo">
        <h2>Shule ya Polisi Moshi</h2>
        <h4>Kibali cha ruhusa kilichokataliwa</h4>
    </div>

    <!-- Request Details -->
    <div class="section">
        <strong>Jina la Mwanafunzi:</strong> {{ $leaveRequest->student->first_name }} {{ $leaveRequest->student->last_name }}
    </div>

    <div class="section">
        <strong>Sababu ya Ruhusa:</strong> {{ $leaveRequest->reason }}
    </div>

    <div class="section">
        <strong>Sababu ya Kukataliwa:</strong> {{ $leaveRequest->rejection_reason }}
    </div>

    <div class="section">
        <strong>Tarehe ya Kukataliwa:</strong> {{ \Carbon\Carbon::parse($leaveRequest->rejected_at)->format('d M, Y h:i A') }}
    </div>

    <!-- Signature Area -->
    <div class="signature-section">
    <p>Imetolewa na:</p>
    <img src="{{ public_path('signatures/oc.png') }}" width="150" alt="Signature">
    <div>Afisa Mkuu wa Mafunzo(CI- TPS MOSHI)</div>
</div>
 <!-- Footer -->
 <div class="date-footer">
        Printed: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}
    </div>
</body>
</html>
