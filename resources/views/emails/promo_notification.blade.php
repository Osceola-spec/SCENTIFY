<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Promo Notification</title>
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
        .promo-title {
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
            
            @if($isWishlistMatch)
                <p>Great news! A product you've been eyeing in your wishlist is currently on sale. Don't miss this chance to make it yours!</p>
            @else
                <p>We have a special promotion just for you. Check out the details below and treat yourself to a new scent.</p>
            @endif

            <div class="promo-title">{{ $promotion->title }}</div>
            
            <div class="discount-badge">
                @if($promotion->discount_type === 'percent')
                    Discount: {{ (float) $promotion->discount_value }}% OFF
                @else
                    Discount: Rp {{ number_format((float) $promotion->discount_value, 0, ',', '.') }} OFF
                @endif
            </div>

            <p>{{ $promotion->description }}</p>

            <p style="margin-top: 30px;">
                <a href="{{ url('/shop') }}" class="btn">Shop Now</a>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Scentify. All rights reserved.<br>
            If you have any questions, reply to this email or contact our support team.
        </div>
    </div>
</body>
</html>
