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
        <th>Nosaukums</th>
        <th>Tips</th>
        <th>Vecuma ierobežojums</th>
        <th>Autors</th>
        <th>Izveidots</th>
        <th>Statuss</th>
    </tr>
    </thead>
    <tbody>
    @foreach($books as $book)
        <tr>
            <td>{{ $book['name'] }}</td>
            <td>{{ $book['type'] }}</td>
            <td>{{ $book['age_limit'] }}</td>
            <td>{{ $book['author'] }}</td>
            <td>{{ $book['created_at'] }}</td>
            <td>{{ $book['block_status'] }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="5" class="total">Klasisko grāmatu skaits:</td>
        <td>{{ $classicBooksCount }}</td>
    </tr>

    <tr>
        <td colspan="5" class="total">Lietotāju stāstu skaits:</td>
        <td>{{ $userBooksCount }}</td>
    </tr>

    <tr>
        <td colspan="5" class="total">Kopējais skaits:</td>
        <td>{{ $totalCount }}</td>
    </tr>

    </tbody>
</table>

</body>
</html>
