<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<!DOCTYPE html>
<html lang="id" class="scroll-smooth dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scentify - Ultimate Premium Perfumery</title>

    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    
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
    {{-- <canvas id="particle-canvas" class="fixed top-0 left-0 w-full h-full z-[999997] pointer-events-none opacity-50"></canvas> --}}


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
            localStorage.theme = isDark ? 'dark' : 'light'; // ✅ sudah benar
            applyVariables(isDark);
        }

        // 4. Custom Dual Cursor Dynamics (Desktop Only)
        const cursorDot = document.querySelector('.cursor-dot');
        const cursorOutline = document.querySelector('.cursor-outline');
        const cursorGlow = document.getElementById('cursor-glow');
        const hoverables = document.querySelectorAll('a, button, .tilt-card, select, input');

        if (window.innerWidth >= 1024) {
            window.addEventListener('mousemove', (e) => {
                const posX = e.clientX;
                const posY = e.clientY;

                 if (cursorDot) {
                    cursorDot.style.left = `${posX}px`;
                    cursorDot.style.top = `${posY}px`;
                }

                // Outline Follower (Native CSS Animation)
                if (cursorOutline) {
                    cursorOutline.animate({
                        left: `${posX}px`,
                        top: `${posY}px`
                    }, { duration: 400, fill: "forwards" });
                }

                // Ambient Glow Background Follower (Smooth GSAP)
                if (cursorGlow) {
                    gsap.to(cursorGlow, {
                        x: posX,
                        y: posY,
                        xPercent: -50,
                        yPercent: -50,
                        duration: 0.8,
                        ease: "power2.out"
                    });
                }

                // --- PEMICU EFEK JEJAK (TRACE MIST) ---
                if (typeof traceParticles !== 'undefined') {
                    for (let i = 0; i < 2; i++) {
                        traceParticles.push(new TraceParticle(posX, posY));
                    }
                }
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

        // 9. Interactive Background Canvas Particle Simulator & TRACE EFEK
        const canvas = document.getElementById('particle-canvas');
        const ctx = canvas.getContext('2d');
        let particlesArray = [];
        let traceParticles = []; // Array khusus untuk efek kabut jejak kursor

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 1.5 + 0.5; // Partikel kecil elegan
                this.speedX = Math.random() * 0.4 - 0.2; // Gerakan lambat dan tenang
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

        // --- Class Tambahan: Untuk Trace / Jejak Kursor ---
        class TraceParticle {
            constructor(x, y) {
                this.x = x;
                this.y = y;
                this.size = Math.random() * 4 + 1;
                this.speedX = Math.random() * 2 - 1;
                this.speedY = Math.random() * 2 - 1;
                this.life = 1;
                this.decay = Math.random() * 0.03 + 0.02;
                
                const isDark = document.documentElement.classList.contains('dark');
                this.baseColor = isDark ? '245, 158, 11' : '217, 119, 6';
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                this.life -= this.decay;
                if (this.size > 0.1) this.size -= 0.1;
            }
            draw(ctx) {
                ctx.fillStyle = `rgba(${this.baseColor}, ${this.life})`;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        function initParticles() {
            particlesArray = [];
            const isMobile = window.innerWidth < 768;
            const divisor = isMobile ? 35000 : 12000;
            
            let numberOfParticles = (canvas.height * canvas.width) / divisor;
            for (let i = 0; i < numberOfParticles; i++) {
                particlesArray.push(new Particle());
            }

            // === BACKGROUND ANIMATION TAMBAHAN ===
            // Guard so we only initialize the bg animation once (prevents duplicates on resize/theme toggle)
            if (!window.__bgAnimationInitialized) {
                window.__bgAnimationInitialized = true;

                const bgCanvas = document.createElement('canvas');
                bgCanvas.id = 'bg-ambient-canvas';
                bgCanvas.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;z-index:-11;pointer-events:none;';
                document.body.appendChild(bgCanvas);
                const bgCtx = bgCanvas.getContext('2d');
                bgCanvas.width = window.innerWidth;
                bgCanvas.height = window.innerHeight;

                const mistOrbs = Array.from({ length: 12 }, () => ({
                    x: Math.random() * bgCanvas.width,
                    y: Math.random() * bgCanvas.height,
                    r: Math.random() * 200 + 100,
                    vx: (Math.random() - 0.5) * 0.18,
                    vy: (Math.random() - 0.5) * 0.12,
                    life: Math.random() * Math.PI * 2,
                    lifeSpeed: Math.random() * 0.004 + 0.002,
                    c: [[245,158,11],[168,85,247],[20,184,166],[239,68,68]][Math.floor(Math.random()*4)]
                }));

                const auroraWaves = Array.from({ length: 4 }, (_, i) => ({
                    phase: Math.random() * Math.PI * 2,
                    speed: 0.004 + i * 0.0015,
                    amp: 50 + i * 25,
                    yBase: bgCanvas.height * (0.25 + i * 0.18),
                    hue: [38, 270, 180, 330][i]
                }));

                function drawBgAnimation() {
                    bgCtx.clearRect(0, 0, bgCanvas.width, bgCanvas.height);
                    const isDark = document.documentElement.classList.contains('dark');

                    // --- Mist Orbs ---
                    for (const o of mistOrbs) {
                        o.x += o.vx; o.y += o.vy; o.life += o.lifeSpeed;
                        if (o.x < -o.r) o.x = bgCanvas.width + o.r;
                        if (o.x > bgCanvas.width + o.r) o.x = -o.r;
                        if (o.y < -o.r) o.y = bgCanvas.height + o.r;
                        if (o.y > bgCanvas.height + o.r) o.y = -o.r;
                        const a = (Math.sin(o.life) * 0.5 + 0.5) * (isDark ? 0.055 : 0.035);
                        const g = bgCtx.createRadialGradient(o.x, o.y, 0, o.x, o.y, o.r);
                        g.addColorStop(0, `rgba(${o.c[0]},${o.c[1]},${o.c[2]},${a})`);
                        g.addColorStop(1, `rgba(${o.c[0]},${o.c[1]},${o.c[2]},0)`);
                        bgCtx.fillStyle = g;
                        bgCtx.beginPath(); bgCtx.arc(o.x, o.y, o.r, 0, Math.PI * 2); bgCtx.fill();
                    }

                    // --- Aurora Waves (dark mode only) ---
                    if (isDark) {
                        for (const w of auroraWaves) {
                            w.phase += w.speed;
                            bgCtx.beginPath();
                            bgCtx.moveTo(0, bgCanvas.height);
                            for (let x = 0; x <= bgCanvas.width; x += 6) {
                                const y = w.yBase
                                    + Math.sin(x * 0.005 + w.phase) * w.amp
                                    + Math.sin(x * 0.002 + w.phase * 1.4) * (w.amp * 0.35);
                                bgCtx.lineTo(x, y);
                            }
                            bgCtx.lineTo(bgCanvas.width, bgCanvas.height);
                            bgCtx.closePath();
                            const grad = bgCtx.createLinearGradient(0, w.yBase - w.amp, 0, w.yBase + w.amp);
                            grad.addColorStop(0, `hsla(${w.hue},80%,60%,0.055)`);
                            grad.addColorStop(0.5, `hsla(${w.hue},80%,60%,0.1)`);
                            grad.addColorStop(1, `hsla(${w.hue},80%,60%,0)`);
                            bgCtx.fillStyle = grad; bgCtx.fill();
                        }
                    }

                    requestAnimationFrame(drawBgAnimation);
                }

                window.addEventListener('resize', () => {
                    const c = document.getElementById('bg-ambient-canvas');
                    if (!c) return;
                    c.width = window.innerWidth;
                    c.height = window.innerHeight;
                    auroraWaves.forEach((w, i) => { w.yBase = c.height * (0.25 + i * 0.18); });
                });

                drawBgAnimation();
            }
            // === END BACKGROUND ANIMATION ===
        }

        function animateParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Gambar Partikel Background
            for (let i = 0; i < particlesArray.length; i++) {
                particlesArray[i].update();
                particlesArray[i].draw();
            }

            // Gambar Jejak Partikel Kursor (Trace)
            for (let i = 0; i < traceParticles.length; i++) {
                traceParticles[i].update();
                traceParticles[i].draw(ctx);
                if (traceParticles[i].life <= 0 || traceParticles[i].size <= 0) {
                    traceParticles.splice(i, 1);
                    i--;
                }
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

        // 11. Toggle Chatbot Interface
        let isChatOpen = false;

        function toggleChatbot() {
            const chatWindow = document.getElementById('chatbot-window');
            isChatOpen = !isChatOpen;

            if (isChatOpen) {
                chatWindow.classList.remove('hidden', 'pointer-events-none', 'translate-y-10', 'opacity-0');
                chatWindow.classList.add('translate-y-0', 'opacity-100');
                document.getElementById('chat-input').focus();
            } else {
                chatWindow.classList.remove('translate-y-0', 'opacity-100');
                chatWindow.classList.add('translate-y-10', 'opacity-0', 'pointer-events-none');
            }
        }

        async function sendChatMessage(e) {
            e.preventDefault();
            
            const inputElement = document.getElementById('chat-input');
            const messageArea = document.getElementById('chat-messages');
            const messageText = inputElement.value.trim();
            
            if (!messageText) return;

            // 1. Bersihkan input & Tampilkan pesan user ke layar
            inputElement.value = '';
            
            const userBubble = `
                <div class="flex gap-2 max-w-[85%] self-end flex-row-reverse">
                    <div class="p-3 bg-amber-500/10 dark:bg-amber-500/20 text-amber-900 dark:text-amber-200 rounded-2xl rounded-tr-none border border-amber-500/10 shadow-sm">
                        ${messageText}
                    </div>
                </div>
            `;
            messageArea.insertAdjacentHTML('beforeend', userBubble);
            messageArea.scrollTop = messageArea.scrollHeight; // Auto-scroll ke bawah

            // 2. Tampilkan Efek Loading Mengetik dari AI
            const loadingId = 'ai-loading-' + Date.now();
            const loadingBubble = `
                <div id="${loadingId}" class="flex gap-2 max-w-[85%]">
                    <div class="w-7 h-7 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center text-xs flex-shrink-0"><i class="fas fa-robot"></i></div>
                    <div class="p-3 bg-white dark:bg-zinc-900 rounded-2xl rounded-tl-none border border-slate-100 dark:border-white/5 shadow-sm text-slate-400 flex items-center gap-1">
                        <i class="fas fa-circle-notch animate-spin mr-1"></i> Scenty sedang berpikir...
                    </div>
                </div>
            `;
            messageArea.insertAdjacentHTML('beforeend', loadingBubble);
            messageArea.scrollTop = messageArea.scrollHeight;

            try {
                // 3. Kirim data via Fetch API ke backend Laravel Controller kita
                const response = await fetch('/api/chatbot', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json', // <--- BARIS INI WAJIB ADA AGAR LARAVEL TIDAK MENGIRIM HTML
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ message: messageText })
                });

                const data = await response.json();
                
                // Hapus bubble loading
                const loadingEl = document.getElementById(loadingId);
                if (loadingEl) loadingEl.remove();

                if (data.status === 'success') {
                    // 4. Render jawaban asli dari AI
                    const aiBubble = `
                        <div class="flex gap-2 max-w-[85%]">
                            <div class="w-7 h-7 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center text-xs flex-shrink-0"><i class="fas fa-robot"></i></div>
                            <div class="p-3 bg-white dark:bg-zinc-900 rounded-2xl rounded-tl-none border border-slate-100 dark:border-white/5 shadow-sm text-slate-700 dark:text-zinc-200 leading-relaxed">
                                ${data.reply.replace(/\n/g, '<br>')}
                            </div>
                        </div>
                    `;
                    messageArea.insertAdjacentHTML('beforeend', aiBubble);
                } else {
                    // Lempar pesan error dari server agar ditangkap oleh catch block di bawah
                    throw new Error(data.message || 'Gagal memuat respons AI.');
                }

            } catch (error) {
                // Cetak pesan error yang SEBENARNYA ke console browser (Tekan F12 untuk melihatnya)
                console.error("ALASAN GAGAL:", error.message);
                
                const loadingEl = document.getElementById(loadingId);
                if (loadingEl) loadingEl.remove();
                
                // Beri indikasi error merah kecil di jendela chat
                const errorBubble = `
                    <div class="flex gap-2 max-w-[85%]">
                        <div class="w-7 h-7 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center text-xs flex-shrink-0"><i class="fas fa-exclamation-triangle"></i></div>
                        <div class="p-3 bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 rounded-2xl rounded-tl-none border border-red-500/10 text-xs">
                            Sistem sibuk. Silakan coba ajukan pertanyaan beberapa saat lagi.<br>
                            <span class="text-[9px] opacity-70">(Cek F12 Console untuk detail error)</span>
                        </div>
                    </div>
                `;
                messageArea.insertAdjacentHTML('beforeend', errorBubble);
            }

            messageArea.scrollTop = messageArea.scrollHeight;
        }
    </script>

    @yield('scripts')

    <div id="chatbot-window" class="fixed bottom-24 right-6 w-[92vw] sm:w-[400px] h-[500px] rounded-2xl glass-card shadow-[0_10px_40px_rgba(0,0,0,0.2)] dark:shadow-[0_10px_40px_rgba(245,158,11,0.05)] z-[99] flex flex-col translate-y-10 opacity-0 pointer-events-none transition-all duration-500 ease-out">
    
        <div class="p-4 border-b border-slate-200 dark:border-white/5 flex items-center justify-between bg-white/20 dark:bg-zinc-900/40 rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-500/10 flex items-center justify-center text-amber-500 relative">
                    <i class="fas fa-robot"></i>
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-slate-900 animate-pulse"></span>
                </div>
                <div>
                    <h4 class="font-semibold text-sm tracking-wide text-gradient">Scenty AI</h4>
                    <p class="text-[11px] text-slate-500 dark:text-zinc-400">Asisten Parfum Eksklusif Anda</p>
                </div>
            </div>
            <button onclick="toggleChatbot()" class="text-slate-400 hover:text-amber-500 transition-colors p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div id="chat-messages" class="flex-grow p-4 overflow-y-auto space-y-4 text-sm flex flex-col custom-scrollbar">
            <div class="flex gap-2 max-w-[85%]">
                <div class="w-7 h-7 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center text-xs flex-shrink-0"><i class="fas fa-robot"></i></div>
                <div class="p-3 bg-white dark:bg-zinc-900 rounded-2xl rounded-tl-none border border-slate-100 dark:border-white/5 shadow-sm text-slate-700 dark:text-zinc-200">
                    Halo! Selamat datang di Scentify ✨ Ada aroma atau parfum spesifik yang sedang Anda cari hari ini? Saya bisa bantu rekomendasikan yang paling cocok untuk Anda.
                </div>
            </div>
        </div>

        <form id="chat-form" onsubmit="sendChatMessage(event)" class="p-3 border-t border-slate-200 dark:border-white/5 bg-white/10 dark:bg-zinc-900/20 rounded-b-2xl flex gap-2 items-center">
            @csrf
            <input type="text" id="chat-input" placeholder="Ketik aroma impian Anda di sini..." autocomplete="off" class="flex-grow bg-white/50 dark:bg-zinc-900/50 text-slate-800 dark:text-zinc-100 text-sm px-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/5 focus:outline-none focus:border-amber-500/50 backdrop-blur-md transition-colors placeholder:text-slate-400 dark:placeholder:text-zinc-500">
            <button type="submit" class="w-10 py-2.5 bg-gradient-to-tr from-amber-600 to-amber-400 text-white rounded-xl shadow-md hover:scale-105 active:scale-95 transition-all flex items-center justify-center">
                <i class="fas fa-paper-plane text-xs"></i>
            </button>
        </form>
    </div>

    <div class="fixed bottom-6 right-6 z-[90] flex flex-col items-end group">
        <div class="mb-3 px-4 py-2 bg-white dark:bg-zinc-800 text-sm font-medium text-slate-700 dark:text-zinc-200 rounded-xl shadow-xl border border-slate-200 dark:border-white/10 opacity-0 transform translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 pointer-events-none">
            Tanya Scenty AI <i class="fas fa-sparkles text-amber-500 ml-1"></i>
        </div>
        
        <button onclick="toggleChatbot()" class="relative flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-tr from-amber-600 to-amber-400 text-white shadow-[0_0_20px_rgba(245,158,11,0.3)] hover:shadow-[0_0_30px_rgba(245,158,11,0.6)] hover:scale-110 transition-all duration-300 z-10">
            <span class="absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-40 animate-ping"></span>
            
            <i class="fas fa-robot text-xl z-20 transition-transform duration-300 group-hover:rotate-12"></i>
        </button>
    </div>
</body>
</html>