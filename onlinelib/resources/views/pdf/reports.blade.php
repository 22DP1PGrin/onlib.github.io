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
            text-align: center;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            font-size: 12px;
        }

        th {
            background: #f2f2f2;
        }

        .total {
            text-align: right;
            font-weight: bold;
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
        <th>Uz ko sūdzība</th>
        <th>Nosaukums / Lietotājvārds</th>
        <th>Iemesls</th>
        <th>Ziņotājs</th>
        <th>Izveidots</th>
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
