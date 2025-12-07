<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>я</title>
</head>
<body style="font-family: Tahoma, Helvetica, sans-serif; ">
<p>Sveiki {{ $nickname }},</p>

<p>Lūdzu, apstipriniet savu e-pastu, noklikšķinot uz tālāk redzamās saites:</p>

<p>
    <a href="{{ $verificationUrl }}"
       style="color:rgba(106, 51, 0, 0.8); text-align: center;">
        Apstiprināt e-pastu
    </a>
</p>

<p>Ja neesat izveidojis šo kontu, varat droši ignorēt šo e-pastu.</p>

<p>Ar cieņu</p>
<p>Onlib komanda.</p>
</body>
</html>
