<!DOCTYPE html>
<html>
<head>
    <title>Patientenbericht</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
        }
        .header img {
            width: 50px;
            height: 50px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .center {
            text-align: center;
            flex: 1;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .section-content p {
            margin: 2px 0;
            line-height: 1.4;
        }
        .section-content ul {
            margin: 0;
            padding-left: 20px;
            list-style-type: disc;
        }
        .section-content ul li {
            margin: 2px 0;
        }
        .divider {
            border-top: 1px solid #ddd;
            margin: 10px 0;
        }
    </style>
</head>
<body>
<div class="header">
    <div style="display: flex; align-items: center;">
        <img src="https://www.culinaryschools.org/clipart/agriculture/cartoon-sheep.png" alt="Application Logo" class="h-11 w-auto">
        <h1>Legasthenie & Co.</h1>
    </div>
    <div class="center">
        <p><strong>Klient:</strong> {{ $patient->first_name }} {{ $patient->last_name }}</p>
        <p><strong>Klientennummer:</strong> {{ $patient->patient_number }}</p>
    </div>
</div>

<div class="section">
    <div class="section-title">Persönliche Daten</div>
    <div class="divider"></div>
    <div class="section-content">
        <p><strong>Geburtsdatum:</strong> {{ $patient->birth_date }}</p>
        <p><strong>Geschlecht:</strong> {{ $patient->gender }}</p>
        <p><strong>Adresse:</strong> {{ $patient->address }}</p>
        <p><strong>Name der Mutter:</strong> {{ $patient->mothers_name }}</p>
        <p><strong>Name des Vaters:</strong> {{ $patient->fathers_name }}</p>
        <p><strong>Telefonnummer:</strong> {{ $patient->phone_number }}</p>
        <p><strong>Email der Eltern:</strong> {{ $patient->parents_email }}</p>
        <p><strong>Beruf der Eltern:</strong> {{ $patient->parents_work }}</p>
    </div>
</div>

<div class="section">
    <div class="section-title">Schule</div>
    <div class="divider"></div>
    <div class="section-content">
        <p><strong>Schultyp:</strong> {{ $patient->school_type }}</p>
        <p><strong>Schulstufe:</strong> {{ $patient->grade }}</p>
        <p><strong>PLZ der Einrichtung:</strong> {{ $patient->school_postcode }}</p>
    </div>
</div>

<div class="section">
    <div class="section-title">Therapie</div>
    <div class="divider"></div>
    <div class="section-content">
        <p><strong>In Behandlung seit:</strong> {{ $patient->therapy_for }}</p>
        <p><strong>Anzahl der Sitzungen:</strong> {{ $patient->sessions_count }}</p>
    </div>
</div>

<div class="section">
    <div class="section-title">Diagnosen</div>
    <div class="divider"></div>
    <div class="section-content">
        @if($patient->diagnoses && $patient->diagnoses->count() > 0)
            <ul>
                @foreach($patient->diagnoses as $diagnosis)
                    <li>{{ $diagnosis->name }}</li>
                @endforeach
            </ul>
        @else
            <p>Keine Diagnosen vorhanden.</p>
        @endif
    </div>
</div>

<div class="section">
    <div class="section-title">Information</div>
    <div class="divider"></div>
    <div class="section-content">
        <p>{{ $patient->information }}</p>
    </div>
</div>
</body>
</html>
