<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Kode Verifikasi</title>
  </head>
  <body>
    <p>Halo {{ $user->first_name ?? $user->email }},</p>
    <p>Terima kasih telah mendaftar. Gunakan kode berikut untuk memverifikasi alamat email Anda:</p>
    <h2>{{ $code }}</h2>
    <p>Kode ini berlaku 15 menit.</p>
    <p>Jika Anda tidak melakukan pendaftaran, abaikan email ini.</p>
  </body>
</html>
