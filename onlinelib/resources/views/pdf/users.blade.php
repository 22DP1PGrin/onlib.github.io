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
        <th>Loma</th>
        <th>Izveidots</th>
        <th>Statuss</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->nickname }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role_translated }}</td>
            <td>{{ $user->created_at }}</td>
            <td>{{ $user->block_status }}</td>

        </tr>
    @endforeach
    <tr>
        <td colspan="4" class="total">Kopējais lietotāju skaits:</td>
        <td>{{ $totalUsers }}</td>
    </tr>
    </tbody>
</table>

</body>
</html>
