<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Enable Google2fa</title>
</head>

<body>
    <center>
        <h3>Scan QR on Google Authenticator</h3>
        <img src="{{ $img }}" alt="QR to Scan"><br>
        Save this Secret for Account Recovery<br>
        <h4>{{ $secret }}</h4><br>
        <form action="{{ route('enable2fa') }}" method="post">
            @csrf
            <input name="gotp" type="text" maxlength="6" placeholder="Enter OTP here"><br>
            <button type="submit">Activate</button>
        </form>
    </center>
</body>

</html>
