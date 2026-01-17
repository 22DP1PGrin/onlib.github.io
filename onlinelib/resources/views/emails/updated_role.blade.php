<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>я</title>
</head>
<body style="font-family: Tahoma, Helvetica, sans-serif; ">
<p>Sveiki {{ $nickname }},</p>

<p>Jūsu loma ir atjaunināta uz {{ $role === "user" ? "parasto lietotāju" : ($role === "admin" ? "administratoru" : $role) }} lomu!</p>

<p>Ar cieņu</p>
<p>Onlib komanda.</p>
</body>
</html>
