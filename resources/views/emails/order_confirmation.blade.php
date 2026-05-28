<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Konfirmasi Pembayaran Scentify</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f4f5; padding: 20px; color: #333333; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #e4e4e7;">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #1c1917; margin: 0; font-size: 28px; letter-spacing: 2px;">SCENTIFY</h1>
            <p style="color: #71717a; font-size: 12px; margin: 5px 0 0 0;">✨ Your Premium Fragrance Partner ✨</p>
        </div>

        <hr style="border: 0; border-top: 1px solid #e4e4e7; margin-bottom: 25px;">

        <h2 style="color: #16a34a; font-size: 20px; margin-top: 0;">Pembayaran Berhasil!</h2>
        <p style="font-size: 15px; line-height: 1.6; color: #4b5563;">
            Halo, terima kasih telah berbelanja di Scentify. Pembayaran untuk pesanan Anda telah kami terima dengan sukses. Berikut adalah rincian transaksi Anda:
        </p>
        
        <table style="width: 100%; margin: 20px 0; border-collapse: collapse; font-size: 14px;">
            <tr>
                <td style="padding: 6px 0; font-weight: bold; color: #666666;">Nomor Pesanan:</td>
                <td style="padding: 6px 0; text-align: right; font-weight: bold;">{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; font-weight: bold; color: #666666;">Status Pembayaran:</td>
                <td style="padding: 6px 0; text-align: right; color: #16a34a; font-weight: bold;">LUNAS (PAID)</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; font-weight: bold; color: #666666;">Tanggal:</td>
                <td style="padding: 6px 0; text-align: right; color: #666666;">{{ date('d M Y H:i') }} WIB</td>
            </tr>
        </table>

        <div style="background-color: #fafafa; padding: 15px; border-radius: 6px; border: 1px solid #f4f4f5; margin-bottom: 25px;">
            <h3 style="margin-top: 0; font-size: 14px; color: #1c1917; border-bottom: 1px solid #e4e4e7; padding-bottom: 8px;">Ringkasan Pembayaran</h3>
            <table style="width: 100%; font-size: 14px; line-height: 2;">
                <tr>
                    <td style="color: #666666;">Subtotal:</td>
                    <td style="text-align: right; color: #333333;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="color: #666666;">Pajak (11%):</td>
                    <td style="text-align: right; color: #333333;">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</td>
                </tr>
                <tr style="font-weight: bold; font-size: 15px;">
                    <td style="color: #1c1917; padding-top: 10px; border-top: 1px solid #e4e4e7;">Total Bayar:</td>
                    <td style="text-align: right; color: #1c1917; padding-top: 10px; border-top: 1px solid #e4e4e7;">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 20px; padding: 15px; background: #fffcf0; border-left: 4px solid #eab308; border-radius: 4px; font-size: 13px; line-height: 1.5; color: #4b5563;">
            <strong style="color: #1c1917; display: block; margin-bottom: 5px;">Alamat Pengiriman:</strong>
            {{ $order->shipping_address }}
        </div>

        <p style="font-size: 14px; line-height: 1.6; color: #4b5563; margin-top: 25px;">
            Pesanan Anda akan segera kami kemas dan serahkan ke kurir. Nomor resi pengiriman akan diupdate secara berkala pada halaman riwayat transaksi akun Anda.
        </p>

        <hr style="border: 0; border-top: 1px solid #e4e4e7; margin: 30px 0 20px 0;">
        <p style="font-size: 11px; color: #999999; text-align: center; margin: 0;">
            Email ini dikirimkan otomatis oleh sistem SCENTIFY. Mohon tidak membalas email ini.<br>
            © {{ date('Y') }} SCENTIFY Team. All rights reserved.
        </p>
    </div>
</body>
</html>