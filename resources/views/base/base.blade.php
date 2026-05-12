<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scentify - Toko Parfum Premium</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Custom Styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        /* Navbar Custom */
        .navbar-brand {
            font-weight: 300;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.8)), 
                        url('https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&q=80&w=1200') center/cover no-repeat;
            height: 95vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 300;
            margin-bottom: 20px;
        }

        /* Product Card */
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .product-img-wrapper {
            height: 300px;
            overflow: hidden;
            background-color: #eee;
        }

        .product-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Category Card */
        .category-card {
            height: 250px;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            color: white;
            display: flex;
            align-items: flex-end;
            padding: 20px;
            text-decoration: none;
        }

        .category-card img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
            transition: transform 0.5s ease;
        }

        .category-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            z-index: 2;
        }

        .category-card:hover img {
            transform: scale(1.1);
        }

        .category-card h3 {
            position: relative;
            z-index: 3;
            margin: 0;
            font-weight: 300;
        }

        /* Custom Toast Notification */
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1055;
        }
    </style>
</head>
<body>

    <!-- 1. MEMANGGIL HEADER -->
    @include('include.header')

    <!-- 2. MEMANGGIL KONTEN HALAMAN (Dari home.blade.php) -->
    <main>
        @yield('content')
    </main>

    <!-- 3. MEMANGGIL FOOTER -->
    @include('include.footer')

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript (Data Produk & Add to Cart) -->
    <script>
        
        // Logika Keranjang Belanja & Notifikasi
        let cartCount = 0;
        const cartBadge = document.getElementById('cart-badge');
        
        // Inisialisasi Bootstrap Toast
        const toastElList = [].slice.call(document.querySelectorAll('.toast'));
        let toastList = [];
        if(toastElList.length > 0) {
            toastList = toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl, { delay: 3000 });
            });
        }

        function addToCart() {
            cartCount++;
            if(cartBadge) cartBadge.innerText = cartCount;
            if(toastList.length > 0) toastList[0].show();
        }
    </script>
</body>
</html>