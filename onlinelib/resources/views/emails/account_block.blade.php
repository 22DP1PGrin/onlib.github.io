<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Konta bloķēšana.</title>
</head>
<body style="font-family: Tahoma, Helvetica, sans-serif; ">
<p>Sveiki {{ $nickname }},</p>

<p>Informējam, ka Jūsu konts ir bloķēts, pamatojoties uz vietnes lietošanas noteikumiem un drošības apsvērumiem.</p>

<p>Bloķēšanas iemesls: {{ $subject_pr }}</p>
<p>Administratora paskaidrojums: {{ $problem }}</p>

<p>Konta bloķēšana ir spēkā līdz <strong>{{ $blockedUntil }}</strong>.
Pēc norādītā laika konts tiks automātiski atbloķēts, ja netiks konstatēti papildu pārkāpumi.
</p>

<p>Ja nepiekrītat pieņemtajam lēmumam, Jums ir tiesības iesniegt apelāciju vai sazināties ar tehnisko atbalstu, lai saņemtu papildu informāciju.
    Lūdzu, ņemiet vērā, ka, kamēr konts ir bloķēts, tā izmantošana nav iespējama.</p>

<p>Ar cieņu</p>
<p>Onlib komanda.</p>
</body>
</html>
