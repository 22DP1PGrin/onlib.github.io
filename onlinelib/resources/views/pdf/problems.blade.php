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
        <th>Lietotājvārds</th>
        <th>E-pasts</th>
        <th>Tēma</th>
    </tr>
    </thead>
    <tbody>
    @foreach($forms as $form)
        <tr>
            <td>{{ $form->nickname }}</td>
            <td>{{ $form->email }}</td>
            <td>{{ $form->subject }}</td>

        </tr>
    @endforeach
    <tr>
        <td colspan="2" class="total">Kopējais pieteikumu skaits:</td>
        <td>{{ $totalForms }}</td>
    </tr>
    </tbody>
</table>

</body>
</html>
