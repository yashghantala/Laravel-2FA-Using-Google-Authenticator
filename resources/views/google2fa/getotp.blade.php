<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>2fa-Auth</title>
</head>

<body>
    <center>
        <form action="{{ route('authenticate') }}" method="POST">
            @csrf
            <input name="gotp" type="text">
            <button type="submit">Authenticate</button>
        </form>
    </center>
</body>

</html>
