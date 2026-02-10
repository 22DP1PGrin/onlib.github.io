<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Stāsta bloķēšana.</title>
</head>
<body style="font-family: Tahoma, Helvetica, sans-serif; ">
<p>Sveiki {{ $nickname }},</p>

<p>Informējam, ka Jūsu stāsts "{{ $bookName }}" ir bloķēts, pamatojoties uz vietnes lietošanas noteikumiem un drošības apsvērumiem.</p>

<p>Bloķēšanas iemesls: {{ $subject_pr }}</p>
<p>Administratora paskaidrojums: {{ $problem }}</p>

    <p>Ja nepiekrītat pieņemtajam lēmumam, Jums ir tiesības iesniegt apelāciju vai sazināties ar tehnisko atbalstu, lai saņemtu papildu informāciju.
    Lūdzu, ņemiet vērā, ka, kamēr stāsts ir bloķēts, tas nebūs pieejams citiem lietotājiem.</p>

<p>Ar cieņu</p>
<p>Onlib komanda.</p>
</body>
</html>
