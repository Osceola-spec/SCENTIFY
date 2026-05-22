<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<!DOCTYPE html>
<html lang="id" class="scroll-smooth dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scentify - Ultimate Premium Perfumery</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        darkbg: '#050507',
                        darkcard: '#0c0c0e',
                        gold: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        }
                    },
                    fontFamily: {
                        serif: ['Playfair Display', 'serif'],
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome Icons & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- GSAP (GreenSock Animation Platform) untuk Animasi Super Mulus & Ringan di Mobile -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --accent-primary: #f59e0b;
            --accent-secondary: #d97706;
            --accent-glow: rgba(245, 158, 11, 0.3);
            --bg-color: #050507;
            --text-main: #f4f4f5;
        }

        .light-vars {
            --accent-primary: #d97706;
            --accent-secondary: #b45309;
            --accent-glow: rgba(217, 119, 6, 0.15);
            --bg-color: #fcfcfd;
            --text-main: #0f172a;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            transition: background-color 0.5s cubic-bezier(0.25, 1, 0.5, 1), color 0.5s cubic-bezier(0.25, 1, 0.5, 1);
        }

        /* Optimalisasi Performa Handphone */
        .will-animate {
            will-change: transform, opacity;
        }

        /* Custom Dual Cursor (Dinonaktifkan di Mobile) --> Desktop Only */
        @media (min-width: 1024px) {
            body { cursor: none; }
            a, button, .tilt-card, select, input { cursor: none; }
            
            .cursor-dot {
                width: 6px;
                height: 6px;
                background-color: var(--accent-primary);
                position: fixed;
                top: 0; left: 0;
                border-radius: 50%;
                pointer-events: none;
                z-index: 999999;
                transform: translate(-50%, -50%);
                box-shadow: 0 0 10px var(--accent-primary), 0 0 20px var(--accent-primary);
                transition: background-color 0.3s ease;
            }

            .cursor-outline {
                width: 36px;
                height: 36px;
                border: 1.5px solid var(--accent-glow);
                position: fixed;
                top: 0; left: 0;
                border-radius: 50%;
                pointer-events: none;
                z-index: 999998;
                transform: translate(-50%, -50%);
                transition: width 0.25s cubic-bezier(0.25, 1, 0.5, 1), height 0.25s cubic-bezier(0.25, 1, 0.5, 1), background-color 0.25s, border-color 0.3s;
            }
        }

        /* Glassmorphism Premium */
        .glass-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: border-color 0.4s, box-shadow 0.4s, background-color 0.4s;
        }

        /* Ensure scent explorer stays below fixed header */
        #scent-explorer {
            position: relative;
            z-index: 1;
        }

        #scent-result-card {
            position: relative;
            z-index: 1;
        }

        #scent-card-ambient {
            position: absolute;
            z-index: 0;
        }

        /* Ensure navbar always stays on top */
        #navbar {
            z-index: 99999 !important;
        }
        .light .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(15, 23, 42, 0.06);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { 
            background: #3f3f46; 
            border-radius: 10px; 
        }
        .light ::-webkit-scrollbar-thumb { background: #cbd5e1; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent-primary); }

        /* Text Shine animatif */
        .text-gradient {
            background: linear-gradient(to right, var(--accent-primary), #fbbf24, var(--accent-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 200% auto;
            animation: textShine 5s linear infinite;
        }
        @keyframes textShine {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Ambient Glow mengambang */
        .ambient-glow-orb {
            filter: blur(130px);
            transform: translate3d(0, 0, 0);
            will-change: transform;
            transition: background-color 1s ease;
        }

        /* 3D Tilt */
        .tilt-container { perspective: 1000px; }
        .tilt-card {
            transform-style: preserve-3d;
            transition: transform 0.15s cubic-bezier(0.25, 1, 0.5, 1);
        }
        .tilt-card.leave {
            transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
        }

        /* Scent Note Badge Pulse */
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
            100% { transform: scale(0.95); opacity: 0.5; }
        }
        .pulse-ring {
            animation: pulse-ring 3s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 dark:bg-darkbg dark:text-zinc-50 antialiased selection:bg-amber-500 selection:text-black flex flex-col min-h-screen transition-colors duration-500 interactive-cursor-area overflow-x-hidden">

    <!-- Dual Cursor (Hanya Desktop) -->
    <div class="cursor-dot hidden lg:block"></div>
    <div class="cursor-outline hidden lg:block"></div>

    <!-- Background Canvas Efek Partikel Ringan -->
    <canvas id="particle-canvas" class="fixed top-0 left-0 w-full h-full -z-10 pointer-events-none opacity-40"></canvas>

    <!-- Progress Scroll Bar Modern -->
    <div id="scroll-progress" class="fixed top-0 left-0 h-[3px] bg-gradient-to-r from-amber-400 to-amber-600 z-50 transition-all duration-100 w-0"></div>

    <!-- Glowing Cursor (Hanya Desktop) -->
    <div id="cursor-glow" class="fixed top-0 left-0 w-80 h-80 bg-amber-500/20 dark:bg-amber-500/30 rounded-full blur-[80px] pointer-events-none z-0 transform -translate-x-1/2 -translate-y-1/2 hidden lg:block mix-blend-multiply dark:mix-blend-screen transition-opacity duration-300"></div>

    <!-- Tombol Toggle Tema (Floating) -->
    <button onclick="toggleTheme()" class="fixed bottom-6 left-6 z-50 p-4 rounded-full bg-white dark:bg-zinc-800 shadow-xl border border-gray-200 dark:border-white/10 hover:scale-110 hover:border-amber-400 transition-all duration-300 group">
        <!-- Ikon Sun untuk beralih ke Light Mode (tampil saat Dark Mode) -->
        <i class="fas fa-sun text-amber-400 text-xl hidden dark:block group-hover:rotate-90 transition-transform"></i>
        <!-- Ikon Moon untuk beralih ke Dark Mode (tampil saat Light Mode) -->
        <i class="fas fa-moon text-indigo-900 text-xl block dark:hidden group-hover:-rotate-12 transition-transform"></i>
    </button>

    <!-- 1. MEMANGGIL HEADER -->
    @include('include.header')

    <!-- 2. MEMANGGIL KONTEN HALAMAN -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- 3. MEMANGGIL FOOTER -->
    @include('include.footer')

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom JavaScript -->
    <script>
        // 1. Inisialisasi awal GSAP untuk animasi halaman masuk (Page Load Hero)
        document.addEventListener('DOMContentLoaded', () => {
            gsap.from(".hero-text-container", {
                duration: 1.2,
                y: 60,
                opacity: 0,
                ease: "power4.out"
            });
            gsap.from(".hero-bottle-container", {
                duration: 1.5,
                scale: 0.9,
                opacity: 0,
                delay: 0.3,
                ease: "power3.out"
            });
        });

        // 2. Skema Scent Mood Data untuk Interaksi Kuis Mini
        const scentData = {
            woody: {
                badge: "Woody Recommendation",
                title: "Golden Amber",
                desc: "Sempurna untuk mereka yang menyukai aroma alam yang dalam. Memancarkan aura kebijaksanaan, kehangatan yang bersahaja, serta impresi ketenangan berkelas berkat racikan cedarwood, vetiver, dan premium amber.",
                price: "Rp 425.000",
                color: "#f59e0b",
                top: "Lemon, Bergamot",
                heart: "Rosewood, Nutmeg",
                base: "Amber, Cedarwood"
            },
            floral: {
                badge: "Floral & Sweet Recommendation",
                title: "Velvet Rose",
                desc: "Aroma anggun yang mengekspresikan sisi romantis dan kelembutan sensual. Campuran mawar segar pegunungan, jasmine, serta musk lembut memberikan kesan bersih, mewah, dan memikat sepanjang hari.",
                price: "Rp 295.000",
                color: "#ec4899", // Pink
                top: "Mountain Rose, Peony",
                heart: "Jasmine, Lily of Valley",
                base: "White Musk, Vanilla Orchid"
            },
            citrus: {
                badge: "Citrus & Sporty Recommendation",
                title: "Ocean Breeze",
                desc: "Sensasi kesegaran tiada tara bagi jiwa yang aktif dan penuh energi. Ledakan citrus bergamot, mint dingin, serta aroma angin laut segar yang bersih, memberikan rasa percaya diri instan pasca-olahraga.",
                price: "Rp 375.000",
                color: "#10b981", // Emerald Green
                top: "Siberian Mint, Sea Salt",
                heart: "Lemon, Eucalyptus",
                base: "Amberwood, Vetiver"
            },
            oriental: {
                badge: "Oriental & Exotic Recommendation",
                title: "Oud Royale",
                desc: "Simbol kemewahan malam hari yang misterius dan penuh intrik. Diracik khusus menggunakan gaharu (oud) langka Timur Tengah yang intens, dibungkus secara manis oleh madu hitam dan kapulaga eksotis.",
                price: "Rp 850.000",
                color: "#8b5cf6", // Purple
                top: "Black Honey, Cardamom",
                heart: "Incense, Saffron",
                base: "Aged Oud, Sandalwood"
            }
        };

        function setScentMood(mood) {
            // Animasi transisi konten menggunakan GSAP untuk efek yang sehalus sutra
            gsap.to("#scent-result-card", {
                duration: 0.3,
                opacity: 0.3,
                y: 10,
                onComplete: () => {
                    const data = scentData[mood];
                    
                    // Update Konten
                    document.getElementById('scent-badge').innerText = data.badge;
                    document.getElementById('scent-title').innerText = data.title;
                    document.getElementById('scent-desc').innerText = data.desc;
                    document.getElementById('scent-price').innerText = data.price;
                    document.getElementById('note-top').innerText = data.top;
                    document.getElementById('note-heart').innerText = data.heart;
                    document.getElementById('note-base').innerText = data.base;

                    // Mengganti warna aksen visual secara dinamis sesuai mood
                    document.getElementById('scent-badge').style.color = data.color;
                    document.getElementById('note-base').style.color = data.color;
                    document.getElementById('scent-card-ambient').style.backgroundColor = data.color;

                    // Update tombol mood aktif
                    const buttons = ['woody', 'floral', 'citrus', 'oriental'];
                    buttons.forEach(b => {
                        const btn = document.getElementById(`mood-${b}`);
                        if (b === mood) {
                            btn.className = `w-full text-left p-4 rounded-xl border border-amber-500/30 bg-amber-500/10 text-amber-500 transition-all duration-300 font-medium text-sm flex justify-between items-center group`;
                        } else {
                            btn.className = `w-full text-left p-4 rounded-xl border border-slate-200 dark:border-white/5 hover:border-amber-500/30 hover:bg-amber-500/5 text-slate-700 dark:text-zinc-300 transition-all duration-300 font-medium text-sm flex justify-between items-center group`;
                        }
                    });

                    // Kembalikan opacity dengan animasi meluncur naik
                    gsap.to("#scent-result-card", {
                        duration: 0.5,
                        opacity: 1,
                        y: 0,
                        ease: "power2.out"
                    });
                }
            });
        }

        // 3. Theme Configuration (Dark / Light Mode)
        function applyVariables(isDark) {
            if (isDark) {
                document.body.classList.remove('light-vars');
            } else {
                document.body.classList.add('light-vars');
            }
            if (typeof initParticles === 'function') {
                initParticles();
            }
        }

        function toggleTheme() {
            const html = document.documentElement;
            html.classList.toggle('dark');
            const isDark = html.classList.contains('dark');
            localStorage.theme = isDark ? 'dark' : 'light';
            applyVariables(isDark);
        }

        // 4. Custom Dual Cursor Dynamics (Desktop Only)
        const cursorDot = document.querySelector('.cursor-dot');
        const cursorOutline = document.querySelector('.cursor-outline');
        const hoverables = document.querySelectorAll('a, button, .tilt-card, select, input');

        if (window.innerWidth >= 1024) {
            window.addEventListener('mousemove', (e) => {
                const posX = e.clientX;
                const posY = e.clientY;

                cursorDot.style.left = `${posX}px`;
                cursorDot.style.top = `${posY}px`;

                cursorOutline.animate({
                    left: `${posX}px`,
                    top: `${posY}px`
                }, { duration: 400, fill: "forwards" });
            });

            hoverables.forEach(item => {
                item.addEventListener('mouseenter', () => {
                    cursorOutline.style.width = '60px';
                    cursorOutline.style.height = '60px';
                    cursorOutline.style.backgroundColor = 'rgba(245, 158, 11, 0.08)';
                    cursorOutline.style.borderColor = 'rgba(245, 158, 11, 0.8)';
                });
                item.addEventListener('mouseleave', () => {
                    cursorOutline.style.width = '36px';
                    cursorOutline.style.height = '36px';
                    cursorOutline.style.backgroundColor = 'transparent';
                    cursorOutline.style.borderColor = 'var(--accent-glow)';
                });
            });
        }

        // 5. Mobile Navigation Menu Toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-icon');
            menu.classList.toggle('hidden');
            if (menu.classList.contains('hidden')) {
                icon.className = 'fas fa-bars';
            } else {
                icon.className = 'fas fa-times';
            }
        }

        // 6. Navbar Scroll Effects & Scroll Progress Indicator (Throttled)
        let lastScrollTop = 0;
        const navbar = document.getElementById('navbar');
        const scrollProgress = document.getElementById('scroll-progress');

        window.addEventListener('scroll', () => {
            // Hitung persentase scroll
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            scrollProgress.style.width = scrolled + "%";

            // Animasi navbar mengambang
            if (winScroll > 50) {
                navbar.classList.add('bg-white/80', 'dark:bg-darkbg/80', 'backdrop-blur-lg', 'shadow-lg', 'py-4', 'border-slate-200', 'dark:border-white/5');
                navbar.classList.remove('bg-transparent', 'py-6');
            } else {
                navbar.classList.add('bg-transparent', 'py-6');
                navbar.classList.remove('bg-white/80', 'dark:bg-darkbg/80', 'backdrop-blur-lg', 'shadow-lg', 'py-4', 'border-slate-200', 'dark:border-white/5');
            }
        });

        // 7. Scroll Reveal dengan Performa Tinggi
        const observerOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -40px 0px"
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // 8. 3D Tilt Card Physics Engine (Hanya aktif di desktop untuk menghemat baterai HP)
        if (window.innerWidth >= 1024) {
            const tiltCards = document.querySelectorAll('.tilt-card');
            tiltCards.forEach(card => {
                card.addEventListener('mousemove', (e) => {
                    card.classList.remove('leave');
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left; 
                    const y = e.clientY - rect.top;  
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    
                    const rotateX = ((y - centerY) / centerY) * -10; 
                    const rotateY = ((x - centerX) / centerX) * 10;
                    
                    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
                });

                card.addEventListener('mouseleave', () => {
                    card.classList.add('leave');
                    card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`;
                });
            });
        }

        // 9. Interactive Background Canvas Particle Simulator (Dioptimalkan untuk HP)
        const canvas = document.getElementById('particle-canvas');
        const ctx = canvas.getContext('2d');
        let particlesArray;

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 1.5 + 0.5; // Partikel lebih kecil untuk tampilan elegan
                this.speedX = Math.random() * 0.4 - 0.2; // Gerakan lebih lambat dan tenang
                this.speedY = Math.random() * 0.4 - 0.2;
                this.updateColor();
            }
            updateColor() {
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) {
                    this.color = Math.random() > 0.5 ? 'rgba(245, 158, 11, 0.2)' : 'rgba(168, 85, 247, 0.1)';
                } else {
                    this.color = Math.random() > 0.5 ? 'rgba(217, 119, 6, 0.12)' : 'rgba(147, 51, 234, 0.08)';
                }
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;

                if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
                if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
            }
            draw() {
                ctx.fillStyle = this.color;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        function initParticles() {
            particlesArray = [];
            // Deteksi HP: Kurangi jumlah partikel hingga 70% agar hemat daya & ringan
            const isMobile = window.innerWidth < 768;
            const divisor = isMobile ? 35000 : 12000;
            
            let numberOfParticles = (canvas.height * canvas.width) / divisor;
            for (let i = 0; i < numberOfParticles; i++) {
                particlesArray.push(new Particle());
            }
        }

        function animateParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (let i = 0; i < particlesArray.length; i++) {
                particlesArray[i].update();
                particlesArray[i].draw();
            }
            requestAnimationFrame(animateParticles);
        }

        initParticles();
        animateParticles();

        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            initParticles();
        });

        // 10. Keranjang Belanja & Umpan Balik SweetAlert2
        let cartCount = 0;
        const cartBadge = document.getElementById('cart-badge');

        function addToCart() {
            cartCount++;
            if (cartBadge) {
                cartBadge.innerText = cartCount;
                // Animasi pop kecil saat keranjang bertambah
                gsap.fromTo("#cart-badge", { scale: 0.6 }, { scale: 1.2, duration: 0.2, yoyo: true, repeat: 1 });
            }
            
            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                customClass: { popup: document.documentElement.classList.contains('dark') ? 'dark-swal' : '' },
                iconColor: '#f59e0b',
            });

            Toast.fire({
                icon: 'success',
                title: 'Parfum berhasil ditambahkan ke keranjang'
            });
        }

        function openCartPreview() {
            Swal.fire({
                icon: 'info',
                title: 'Keranjang Belanja Scentify',
                text: `Jumlah produk terpilih: ${cartCount} unit. Segera selesaikan transaksi eksklusif Anda di halaman checkout Scentify.`,
                confirmButtonColor: '#f59e0b',
                customClass: { popup: document.documentElement.classList.contains('dark') ? 'dark-swal' : '' }
            });
        }

        function showDemoAlert(event, moduleName) {
            event.preventDefault();
            Swal.fire({
                icon: 'info',
                title: 'Koleksi Eksklusif',
                text: `Menuju halaman kurasi ${moduleName}. Pada rute Laravel, ini akan merender template kategori khusus dengan pencarian filter modern.`,
                confirmButtonColor: '#f59e0b',
                customClass: { popup: document.documentElement.classList.contains('dark') ? 'dark-swal' : '' }
            });
        }

        function subscribeNewsletter(event) {
            event.preventDefault();
            Swal.fire({
                icon: 'success',
                title: 'Selamat Bergabung!',
                text: 'Email Anda terdaftar dalam Scentify Circle. Silakan periksa pesan selamat datang kami.',
                confirmButtonColor: '#f59e0b',
                customClass: { popup: document.documentElement.classList.contains('dark') ? 'dark-swal' : '' }
            });
            event.target.reset();
        }
    </script>

    @yield('scripts')
</body>
</html>