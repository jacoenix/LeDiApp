<!DOCTYPE html>
<html>
<head>
    <title>Sitzung</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .header img {
            width: 50px;
            height: 50px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
        }
        .content {
            margin-top: 20px;
        }
        .patient-info {
            text-align: right;
            margin-bottom: 20px;
        }
        .session-info {
            margin-bottom: 20px;
        }
        .session-info p {
            margin: 5px 0;
        }
        .session-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="header">
    <div>
        <img src="https://www.culinaryschools.org/clipart/agriculture/cartoon-sheep.png" alt="Application Logo" class="h-11 w-auto">
    </div>
    <div>
        <h1>Legasthenie & Co.</h1>
    </div>
</div>
<div class="content">
    <div class="patient-info">
        <p><strong>Patient:</strong> {{ $patient->first_name }} {{ $patient->last_name }}</p>
        <p><strong>Patientennummer:</strong> {{ $patient->patient_number }}</p>
    </div>
    <div class="session-info">
        <p><strong>Datum:</strong> {{ $session->session_date }}</p>
        <p><strong>Sitzungsdauer:</strong> {{ $session->start_time }} - {{ $session->end_time }}</p>
    </div>
    <br>
    <div class="session-title">
        {{ $session->title }}
    </div>
    <p><strong>Inhalt:</strong></p>
    <p>{{ $session->description }}</p>
</div>
</body>
</html>
