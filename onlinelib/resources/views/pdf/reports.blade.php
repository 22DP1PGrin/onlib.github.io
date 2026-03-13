<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans,
            sans-serif;
        }

        .date {
            text-align: right;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            text-align: center;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            font-size: 12px;
        }

        td {
            word-break: break-word;
            overflow-wrap: break-word;
        }

        th {
            background: #f2f2f2;
        }

        .total {
            text-align: right;
            font-weight: bold;
        }

        .col-type
        {
            width: 12%;
        }

        .col-target {
            width: 40%;
        }

        .col-reason {
            width: 18%;
        }

        .col-reporter {
            width: 15%;
        }

        .col-date {
            width: 15%;
        }

    </style>
</head>
<body>

<div class="date">
    Pēdējo reizi atjaunināts: {{ $lastUpdated }}
</div>

<table>
    <thead>
    <tr>
        <th class="col-type">Uz ko sūdzība</th>
        <th class="col-target">Nosaukums / Lietotājvārds / Komentāra saturs</th>
        <th class="col-reason">Iemesls</th>
        <th class="col-reporter">Ziņotājs</th>
        <th class="col-date">Izveidots</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reports as $report)
        <tr>
            <td>{{ $report['type'] }}</td>
            <td>{{ $report['target'] }}</td>
            <td>{{ $report['reason'] }}</td>
            <td>{{ $report['reporter'] }}</td>
            <td>{{ $report['created_at'] }}</td>
        </tr>
    @endforeach
    <tr>
            <td colspan="4" class="total">Kopējais sūdzību skaits:</td>
        <td>{{ $totalReports }}</td>
    </tr>
    </tbody>
</table>

</body>
</html>
