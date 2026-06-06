<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Wishlist Discount Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #f59e0b;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
            color: #333333;
            line-height: 1.6;
        }
        .product-title {
            font-size: 20px;
            font-weight: bold;
            color: #f59e0b;
            margin-bottom: 10px;
        }
        .discount-badge {
            display: inline-block;
            background-color: #f43f5e;
            color: #ffffff;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            background-color: #f59e0b;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #888888;
            background-color: #f9fafb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Scentify</h1>
        </div>
        <div class="content">
            <p>Hi {{ $user->name }},</p>
            
            <p>Kabar gembira! Produk impianmu di wishlist kini sedang diskon. Jangan lewatkan kesempatan emas ini untuk membawanya pulang sebelum kehabisan!</p>

            <div class="product-title">{{ $product->name }}</div>
            
            <div class="discount-badge">
                Diskon {{ $product->discount_percent }}% OFF!
            </div>

            <p>Segera cek dan amankan pesananmu sekarang juga.</p>

            <p style="margin-top: 30px;">
                <a href="{{ url('/shop') }}" class="btn">Lihat Produk Sekarang</a>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Scentify. All rights reserved.<br>
            Jika ada pertanyaan, silakan hubungi tim support kami.
        </div>
    </div>
</body>
</html>
