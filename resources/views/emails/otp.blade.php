<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Verification Code</title>
  </head>
  <body>
    <p>Hello {{ $user->first_name ?? $user->email }},</p>
    <p>Thank you for registering. Use the code below to verify your email address:</p>
    <h2>{{ $code }}</h2>
    <p>This code is valid for 15 minutes.</p>
    <p>If you did not register, please ignore this email.</p>
  </body>
</html>
