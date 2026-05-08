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
        // Data Produk Dummy
        const products = [
            { id: 1, name: "Bleu Ethereal", brand: "Chanel", price: 2850000, image: "https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&q=80&w=400" },
            { id: 2, name: "Rouge Elixir 54", brand: "Maison Francis Kurkdjian", price: 5500000, image: "https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&q=80&w=400" },
            { id: 3, name: "Midnight Senopati", brand: "HMNS", price: 385000, image: "https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&q=80&w=400" },
            { id: 4, name: "Santal Enigma", brand: "Le Labo", price: 4200000, image: "https://images.unsplash.com/photo-1615397323136-1e0f074d3da9?auto=format&fit=crop&q=80&w=400" },
            { id: 5, name: "Aqua di Profondo", brand: "Armani", price: 2100000, image: "https://images.unsplash.com/photo-1595532542520-50d220b30d31?auto=format&fit=crop&q=80&w=400" },
            { id: 6, name: "Jasmine Blooms", brand: "Dior", price: 2600000, image: "https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&q=80&w=400" },
            { id: 7, name: "Oud Batavia", brand: "Carl & Claire", price: 299000, image: "https://images.unsplash.com/photo-1592914610354-fd354d45e5b0?auto=format&fit=crop&q=80&w=400" },
            { id: 8, name: "Gypsy Soul", brand: "Byredo", price: 3900000, image: "https://images.unsplash.com/photo-1590156156108-9ba249f07897?auto=format&fit=crop&q=80&w=400" }
        ];

        // Format Harga Rupiah
        const formatRupiah = (number) => {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
        };

        // Render Produk ke HTML (Aman jika element tidak ditemukan di page lain)
        const productContainer = document.getElementById('product-container');
        
        if(productContainer) {
            products.forEach(product => {
                const productHTML = `
                    <div class="col-6 col-md-3">
                        <div class="card product-card shadow-sm h-100">
                            <div class="product-img-wrapper">
                                <img src="${product.image}" alt="">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">${product.brand}</small>
                                <h5 class="card-title fw-light mb-2 text-truncate" title="${product.name}">${product.name}</h5>
                                <p class="card-text fw-bold mb-3">${formatRupiah(product.price)}</p>
                                <button class="btn btn-dark w-100 mt-auto rounded-pill" onclick="addToCart()">Tambah ke Keranjang</button>
                            </div>
                        </div>
                    </div>
                `;
                productContainer.innerHTML += productHTML;
            });
        }

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