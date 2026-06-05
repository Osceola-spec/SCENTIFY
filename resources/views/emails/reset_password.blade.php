<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Scentify - Pemulihan Kata Sandi</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #050507; padding: 20px; color: #ffffff; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background: #1c1917; padding: 40px 30px; border-radius: 16px; border: 1px solid #3f3f46;">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #f59e0b; margin: 0; font-size: 28px; letter-spacing: 2px; font-family: Georgia, serif;">SCENTIFY</h1>
            <p style="color: #a1a1aa; font-size: 12px; margin: 5px 0 0 0; letter-spacing: 1px;">✨ ELEGANCE IN EVERY DROP ✨</p>
        </div>

        <hr style="border: 0; border-top: 1px solid #3f3f46; margin-bottom: 30px;">

        <h2 style="color: #ffffff; font-size: 22px; margin-top: 0; font-family: Georgia, serif; font-weight: normal;">Pemulihan Kata Sandi</h2>
        
        <p style="font-size: 15px; line-height: 1.6; color: #e4e4e7; margin-bottom: 10px;">
            Halo <strong style="color: #f59e0b;">{{ $user->first_name ?? 'Pelanggan Scentify' }}</strong>,
        </p>
        <p style="font-size: 15px; line-height: 1.6; color: #d4d4d8; margin-bottom: 30px;">
            Kami menerima permintaan untuk mengatur ulang kata sandi akun Scentify Anda. Jika ini memang Anda, silakan klik tombol eksklusif di bawah ini untuk melanjutkan:
        </p>
        
        <div style="text-align: center; margin: 40px 0;">
            <a href="{{ $url }}" style="display: inline-block; background-color: #f59e0b; color: #050507; text-decoration: none; padding: 16px 36px; font-weight: bold; border-radius: 12px; font-size: 14px; text-transform: uppercase; letter-spacing: 1.5px;">
                Atur Ulang Kata Sandi
            </a>
        </div>

        <div style="background-color: #27272a; padding: 20px; border-radius: 8px; border-left: 3px solid #f59e0b; margin-top: 30px;">
            <p style="font-size: 13px; line-height: 1.6; color: #a1a1aa; margin: 0;">
                Tautan pemulihan ini hanya berlaku selama <strong>60 menit</strong>. Jika Anda tidak pernah meminta pengaturan ulang kata sandi, Anda dapat mengabaikan pesan ini dengan aman.
            </p>
        </div>

        <hr style="border: 0; border-top: 1px solid #3f3f46; margin: 40px 0 20px 0;">
        <p style="font-size: 11px; color: #71717a; text-align: center; margin: 0; line-height: 1.5;">
            Email ini dikirim secara otomatis oleh sistem keamanan Scentify.<br>
            Harap tidak membalas email ini.<br>
            &copy; {{ date('Y') }} Scentify. Seluruh hak cipta dilindungi.
        </p>
    </div>
</body>
</html>
