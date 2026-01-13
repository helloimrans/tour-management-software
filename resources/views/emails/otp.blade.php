<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OTP for Password Reset</title>
</head>


<body>
    <p>Dear Valued Listener,</p>

    <p>You requested to reset your password. Please use the OTP below to complete the process:</p>
    <p><strong>{{ $mailData['otp'] }}</strong></p>
    <p>This OTP is valid for 3 minutes.</p>
    <p>For your security, please DO NOT share this OTP (One-Time Password) with ANYONE.</p>

    <p>Thank you for being a loyal listener!</p>
</body>

</html>

