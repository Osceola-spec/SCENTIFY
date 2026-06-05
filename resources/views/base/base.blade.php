<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scentify - Ultimate Premium Perfumery</title>

    <script>
        window.Pusher = function(key, options) {
            console.warn("Scentify Protection: Menjinakkan inisialisasi Pusher tanpa app key.");
            this.key = key || 'dummy_key_scentify';
            this.options = options || {};
            this.subscribe = function() { return { bind: function() {} }; };
            this.channel = function() { return { bind: function() {} }; };
        };
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                // Default: light mode — hapus dark jika ada
                document.documentElement.classList.remove('dark');
                if (!theme) {
                    localStorage.setItem('theme', 'light');
                }
            }
        })();
    </script>

    <link rel="stylesheet" href="/js/sweetalert2.min.css">
    <script src="/js/gsap.min.js"></script>
    <script src="/js/sweetalert2.min.js"></script>
    <script src="/js/fuse.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

        .will-animate {
            will-change: transform, opacity;
        }

        @media (min-width: 1024px) {
            body {
                cursor: none;
            }

            a,
            button,
            .tilt-card,
            select,
            input,
            textarea {
                cursor: none;
            }

            .cursor-dot {
                width: 6px;
                height: 6px;
                background-color: var(--accent-primary);
                position: fixed;
                top: 0;
                left: 0;
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
                top: 0;
                left: 0;
                border-radius: 50%;
                pointer-events: none;
                z-index: 999998;
                transform: translate(-50%, -50%);
                transition: width 0.25s cubic-bezier(0.25, 1, 0.5, 1), height 0.25s cubic-bezier(0.25, 1, 0.5, 1), background-color 0.25s, border-color 0.3s;
            }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: border-color 0.4s, box-shadow 0.4s, background-color 0.4s;
        }

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

        #navbar {
            z-index: 99999 !important;
        }

        /* Ensure SweetAlert overlays appear above fixed header */
        .swal2-container,
        .swal2-backdrop,
        .swal2-popup {
            z-index: 1000000 !important;
        }

        .light .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(15, 23, 42, 0.06);
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #3f3f46;
            border-radius: 10px;
        }

        .light ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-primary);
        }

        .text-gradient {
            background: linear-gradient(to right, var(--accent-primary), #fbbf24, var(--accent-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 200% auto;
            animation: textShine 5s linear infinite;
        }

        @keyframes textShine {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .ambient-glow-orb {
            filter: blur(130px);
            transform: translate3d(0, 0, 0);
            will-change: transform;
            transition: background-color 1s ease;
        }

        .tilt-container {
            perspective: 1000px;
        }

        .tilt-card {
            transform-style: preserve-3d;
            transition: transform 0.15s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .tilt-card.leave {
            transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(0.95);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }

            100% {
                transform: scale(0.95);
                opacity: 0.5;
            }
        }

        .pulse-ring {
            animation: pulse-ring 3s ease-in-out infinite;
        }

        @keyframes chatbot-heartbeat {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.5); }
            14%       { transform: scale(1.08); }
            28%       { transform: scale(1); }
            42%       { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
            70%       { transform: scale(1); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }

        .animate-pulse-slow {
            animation: chatbot-heartbeat 2s ease-in-out infinite;
        }

        #chatbot-window {
            transform-origin: bottom right;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.25s ease;
        }

        /* Chatbot window tertutup: tidak ada area klik tersembunyi */
        #chatbot-window.chatbot-closed {
            transform: scale(0.85) translateY(12px);
            opacity: 0;
            pointer-events: none !important;
            visibility: hidden;
            z-index: -1;
            display: none; /* ensure closed window doesn't occupy/cover touch area on mobile */
        }

        #chatbot-window.chatbot-open {
            transform: scale(1) translateY(0);
            opacity: 1;
            pointer-events: all;
            visibility: visible;
            display: block;
        }

        /* Di mobile, pastikan tombol chatbot tidak terlalu besar */
        @media (max-width: 640px) {
            #chatbot-widget {
                bottom: 1.25rem;
                right: 1rem;
            }
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 dark:bg-darkbg dark:text-zinc-50 antialiased selection:bg-amber-500 selection:text-black flex flex-col min-h-screen transition-colors duration-500 interactive-cursor-area overflow-x-hidden">

    <div class="cursor-dot hidden lg:block"></div>
    <div class="cursor-outline hidden lg:block"></div>

    <canvas id="particle-canvas" class="fixed top-0 left-0 w-full h-full -z-10 pointer-events-none opacity-40"></canvas>

    <div id="scroll-progress" class="fixed top-0 left-0 h-[3px] bg-gradient-to-r from-amber-400 to-amber-600 z-50 transition-all duration-100 w-0"></div>

    <div id="cursor-glow" class="fixed top-0 left-0 w-80 h-80 bg-amber-500/20 dark:bg-amber-500/30 rounded-full blur-[80px] pointer-events-none z-0 transform -translate-x-1/2 -translate-y-1/2 hidden lg:block mix-blend-multiply dark:mix-blend-screen transition-opacity duration-300"></div>

    <!-- AI Chatbot Floating Widget -->
    <!-- AI Chatbot Floating Widget -->
    <div id="chatbot-widget" class="fixed bottom-6 right-4 sm:right-6 z-40 flex flex-col items-end gap-3">
        
        <!-- Chat Window -->
        <div id="chatbot-window" class="chatbot-closed w-[calc(100vw-2rem)] sm:w-[380px] bg-white dark:bg-darkcard border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl flex flex-col overflow-hidden" style="max-height: 520px;">
            <!-- Header -->
            <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-amber-500 to-amber-600 shrink-0">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                    <i class="fas fa-robot text-white text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white font-bold text-sm leading-tight">Scenty</p>
                    <p class="text-amber-100 text-[10px]">AI Parfum Assistant</p>
                </div>
                <button onclick="toggleChatbot()" class="text-white/80 hover:text-white transition-colors p-1">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <!-- Messages -->
            <div id="chatbot-messages" class="flex-1 overflow-y-auto p-4 space-y-3" style="min-height: 280px; max-height: 360px;">
                <div class="flex gap-2">
                    <div class="w-6 h-6 rounded-full bg-amber-500 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fas fa-robot text-white text-[9px]"></i>
                    </div>
                    <div class="bg-slate-100 dark:bg-zinc-800 rounded-2xl rounded-tl-sm px-3 py-2 text-xs text-slate-700 dark:text-zinc-300 max-w-[80%]">
                        Hello! I'm Scenty 🌸 Need a perfume recommendation or product info? I'm here to help!
                    </div>
                </div>
            </div>
            <!-- Input -->
            <div class="px-3 py-3 border-t border-slate-100 dark:border-white/5 shrink-0">
                <div class="flex gap-2 items-center">
                    <input id="chatbot-input" type="text" placeholder="Ask Scenty..." maxlength="300"
                        class="flex-1 bg-slate-100 dark:bg-zinc-800 rounded-xl px-3 py-2 text-xs text-slate-800 dark:text-zinc-200 placeholder-slate-400 dark:placeholder-zinc-500 outline-none border border-transparent focus:border-amber-400 transition-colors"
                        onkeydown="if(event.key==='Enter') sendChatMessage()">
                    <button onclick="sendChatMessage()" id="chatbot-send-btn"
                        class="w-8 h-8 rounded-xl bg-amber-500 hover:bg-amber-600 flex items-center justify-center transition-colors shrink-0">
                        <i class="fas fa-paper-plane text-white text-[11px]"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Toggle Button -->
        <button onclick="toggleChatbot()" id="chatbot-toggle-btn"
            class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-white dark:bg-zinc-800 shadow-xl hover:scale-110 transition-transform duration-300 flex items-center justify-center relative group animate-pulse-slow border border-amber-200 dark:border-amber-500/30">
            <i id="chatbot-icon" class="fas fa-robot text-amber-500 text-lg sm:text-xl"></i>
            <span class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-green-400 rounded-full border-2 border-white dark:border-darkcard pulse-ring"></span>
        </button>
    </div>

    <button onclick="toggleTheme()" class="fixed bottom-6 left-6 z-50 p-4 rounded-full bg-white dark:bg-zinc-800 shadow-xl border border-gray-200 dark:border-white/10 hover:scale-110 hover:border-amber-400 transition-all duration-300 group">
        <i class="fas fa-sun text-amber-400 text-xl hidden dark:block group-hover:rotate-90 transition-transform"></i>
        <i class="fas fa-moon text-indigo-900 text-xl block dark:hidden group-hover:-rotate-12 transition-transform"></i>
    </button>

    @include('include.header')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('include.footer')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (document.querySelector(".hero-text-container")) {
                gsap.from(".hero-text-container", {
                    duration: 1.2,
                    y: 60,
                    opacity: 0,
                    ease: "power4.out"
                });
            }
            if (document.querySelector(".hero-bottle-container")) {
                gsap.from(".hero-bottle-container", {
                    duration: 1.5,
                    scale: 0.9,
                    opacity: 0,
                    delay: 0.3,
                    ease: "power3.out"
                });
            }
        });

        // Default (fallback) scent data used while API loads or when it fails
        let scentData = {
            woody: { badge: "Woody Recommendation", title: "Golden Amber", desc: "Warm woody notes.", price: "-", color: "#f59e0b", top: "", heart: "", base: "" },
            floral: { badge: "Floral & Sweet Recommendation", title: "Velvet Rose", desc: "Soft floral bouquet.", price: "-", color: "#ec4899", top: "", heart: "", base: "" },
            citrus: { badge: "Citrus & Sporty Recommendation", title: "Ocean Breeze", desc: "Fresh citrusy accord.", price: "-", color: "#10b981", top: "", heart: "", base: "" },
            oriental: { badge: "Oriental & Exotic Recommendation", title: "Oud Royale", desc: "Deep oriental resin.", price: "-", color: "#8b5cf6", top: "", heart: "", base: "" }
        };

        // Try to fetch real recommendations from backend
        document.addEventListener('DOMContentLoaded', () => {
            fetch('/api/scent-recommendations')
                .then(r => r.ok ? r.json() : Promise.reject(r))
                .then(data => {
                    // merge returned data into scentData
                    for (const key of Object.keys(data)) {
                        scentData[key] = Object.assign({}, scentData[key], data[key]);
                    }
                })
                .catch(err => console.warn('Scent recommendations API failed:', err));
        });

        function setScentMood(mood) {
            if(!document.getElementById('scent-result-card')) return;
            
            gsap.to("#scent-result-card", {
                duration: 0.3,
                opacity: 0.3,
                y: 10,
                onComplete: () => {
                    const data = scentData[mood];

                    document.getElementById('scent-badge').innerText = data.badge;
                    document.getElementById('scent-title').innerText = data.title;
                    document.getElementById('scent-desc').innerText = data.desc;
                    document.getElementById('scent-price').innerText = data.price;
                    document.getElementById('note-top').innerText = data.top;
                    document.getElementById('note-heart').innerText = data.heart;
                    document.getElementById('note-base').innerText = data.base;

                    document.getElementById('scent-badge').style.color = data.color;
                    document.getElementById('note-base').style.color = data.color;
                    document.getElementById('scent-card-ambient').style.backgroundColor = data.color;

                    const buttons = ['woody', 'floral', 'citrus', 'oriental'];
                    buttons.forEach(b => {
                        const btn = document.getElementById(`mood-${b}`);
                        if (btn) {
                            if (b === mood) {
                                btn.className = `w-full text-left p-4 rounded-xl border border-amber-500/30 bg-amber-500/10 text-amber-500 transition-all duration-300 font-medium text-sm flex justify-between items-center group`;
                            } else {
                                btn.className = `w-full text-left p-4 rounded-xl border border-slate-200 dark:border-white/5 hover:border-amber-500/30 hover:bg-amber-500/5 text-slate-700 dark:text-zinc-300 transition-all duration-300 font-medium text-sm flex justify-between items-center group`;
                            }
                        }
                    });

                    gsap.to("#scent-result-card", {
                        duration: 0.5,
                        opacity: 1,
                        y: 0,
                        ease: "power2.out"
                    });
                }
            });
        }

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
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            applyVariables(isDark);
        }

        // Terapkan variabel CSS sesuai theme saat ini saat halaman pertama kali load
        (function() {
            const isDark = document.documentElement.classList.contains('dark');
            if (!isDark) {
                document.body.classList.add('light-vars');
            }
        })();

        const cursorDot = document.querySelector('.cursor-dot');
        const cursorOutline = document.querySelector('.cursor-outline');
        const cursorGlow = document.getElementById('cursor-glow');
        const hoverables = document.querySelectorAll('a, button, .tilt-card, select, input, textarea');

        if (window.innerWidth >= 1024) {
            window.addEventListener('mousemove', (e) => {
                const posX = e.clientX;
                const posY = e.clientY;

                if (cursorDot) {
                    cursorDot.style.left = `${posX}px`;
                    cursorDot.style.top = `${posY}px`;
                }

                if (cursorOutline) {
                    cursorOutline.animate({
                        left: `${posX}px`,
                        top: `${posY}px`
                    }, {
                        duration: 400,
                        fill: "forwards"
                    });
                }

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

                if (typeof traceParticles !== 'undefined' && typeof TraceParticle !== 'undefined') {
                    for (let i = 0; i < 2; i++) {
                        traceParticles.push(new TraceParticle(posX, posY));
                    }
                }
            });

            hoverables.forEach(item => {
                item.addEventListener('mouseenter', () => {
                    if(cursorOutline) {
                        cursorOutline.style.width = '60px';
                        cursorOutline.style.height = '60px';
                        cursorOutline.style.backgroundColor = 'rgba(245, 158, 11, 0.08)';
                        cursorOutline.style.borderColor = 'rgba(245, 158, 11, 0.8)';
                    }
                });
                item.addEventListener('mouseleave', () => {
                    if(cursorOutline) {
                        cursorOutline.style.width = '36px';
                        cursorOutline.style.height = '36px';
                        cursorOutline.style.backgroundColor = 'transparent';
                        cursorOutline.style.borderColor = 'var(--accent-glow)';
                    }
                });
            });
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-icon');
            if (!menu || !icon) return;

            const isOpen = menu.classList.contains('mobile-menu-open');
            if (isOpen) {
                // close
                menu.classList.remove('mobile-menu-open');
                menu.classList.add('mobile-menu-collapsed');
                menu.setAttribute('aria-hidden', 'true');
                icon.className = 'fas fa-bars';
            } else {
                // open
                menu.classList.remove('mobile-menu-collapsed');
                menu.classList.add('mobile-menu-open');
                menu.setAttribute('aria-hidden', 'false');
                icon.className = 'fas fa-times';
            }
        }

        function toggleProfileDropdown(event) {
            event.preventDefault();
            const wrapper = event.currentTarget.closest('[data-profile-wrapper]');
            if (!wrapper) return;
            const menu = wrapper.querySelector('.profile-dropdown');
            if (!menu) return;
            const isOpen = menu.classList.contains('open');
            if (isOpen) {
                menu.classList.remove('open');
                wrapper.classList.remove('profile-open');
                event.currentTarget.setAttribute('aria-expanded', 'false');
            } else {
                menu.classList.add('open');
                wrapper.classList.add('profile-open');
                event.currentTarget.setAttribute('aria-expanded', 'true');
            }
        }

        // Close profile dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const openDropdown = document.querySelector('.profile-dropdown.open');
            if (!openDropdown) return;
            const wrapper = openDropdown.closest('[data-profile-wrapper]');
            if (wrapper && !wrapper.contains(e.target)) {
                openDropdown.classList.remove('open');
                wrapper.classList.remove('profile-open');
                const btn = wrapper.querySelector('button');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            }
        });

        const navbar = document.getElementById('navbar');
        const scrollProgress = document.getElementById('scroll-progress');

        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            
            if(scrollProgress) scrollProgress.style.width = scrolled + "%";

            if(navbar) {
                if (winScroll > 50) {
                    navbar.classList.add('bg-white/80', 'dark:bg-darkbg/80', 'backdrop-blur-lg', 'shadow-lg', 'py-4', 'border-slate-200', 'dark:border-white/5');
                    navbar.classList.remove('bg-transparent', 'py-6');
                } else {
                    navbar.classList.add('bg-transparent', 'py-6');
                    navbar.classList.remove('bg-white/80', 'dark:bg-darkbg/80', 'backdrop-blur-lg', 'shadow-lg', 'py-4', 'border-slate-200', 'dark:border-white/5');
                }
            }
        });

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

        const canvas = document.getElementById('particle-canvas');
        const ctx = canvas ? canvas.getContext('2d') : null;
        let particlesArray = [];
        let traceParticles = [];

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
            draw(context) {
                context.fillStyle = `rgba(${this.baseColor}, ${this.life})`;
                context.beginPath();
                context.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                context.fill();
            }
        }

        if (canvas && ctx) {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;

            class Particle {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 1.5 + 0.5;
                    this.speedX = Math.random() * 0.4 - 0.2;
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
                const isMobile = window.innerWidth < 768;
                const divisor = isMobile ? 35000 : 12000;

                let numberOfParticles = (canvas.height * canvas.width) / divisor;
                for (let i = 0; i < numberOfParticles; i++) {
                    particlesArray.push(new Particle());
                }
            }

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
                        bgCtx.beginPath();
                        bgCtx.arc(o.x, o.y, o.r, 0, Math.PI * 2);
                        bgCtx.fill();
                    }

                    if (isDark) {
                        for (const w of auroraWaves) {
                            w.phase += w.speed;
                            bgCtx.beginPath();
                            bgCtx.moveTo(0, bgCanvas.height);
                            for (let x = 0; x <= bgCanvas.width; x += 6) {
                                const y = w.yBase + Math.sin(x * 0.005 + w.phase) * w.amp + Math.sin(x * 0.002 + w.phase * 1.4) * (w.amp * 0.35);
                                bgCtx.lineTo(x, y);
                            }
                            bgCtx.lineTo(bgCanvas.width, bgCanvas.height);
                            bgCtx.closePath();
                            const grad = bgCtx.createLinearGradient(0, w.yBase - w.amp, 0, w.yBase + w.amp);
                            grad.addColorStop(0, `hsla(${w.hue},80%,60%,0.055)`);
                            grad.addColorStop(0.5, `hsla(${w.hue},80%,60%,0.1)`);
                            grad.addColorStop(1, `hsla(${w.hue},80%,60%,0)`);
                            bgCtx.fillStyle = grad;
                            bgCtx.fill();
                        }
                    }

                    if (ctx && canvas) {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        particlesArray.forEach(p => { p.update(); p.draw(); });
                        traceParticles = traceParticles.filter(tp => {
                            tp.update();
                            if (tp.life > 0) { tp.draw(ctx); return true; }
                            return false;
                        });
                    }

                    requestAnimationFrame(drawBgAnimation);
                }

                window.addEventListener('resize', () => {
                    bgCanvas.width = window.innerWidth;
                    bgCanvas.height = window.innerHeight;
                    if(canvas) {
                        canvas.width = window.innerWidth;
                        canvas.height = window.innerHeight;
                    }
                    auroraWaves.forEach((w, i) => {
                        w.yBase = bgCanvas.height * (0.25 + i * 0.18);
                    });
                    initParticles();
                });

                initParticles();
                drawBgAnimation();
            }
        }
    </script>

    <script>
        function toggleChatbot() {
            const win     = document.getElementById('chatbot-window');
            const icon    = document.getElementById('chatbot-icon');
            const widget  = document.getElementById('chatbot-widget');
            const isClosed = win.classList.contains('chatbot-closed');

            if (isClosed) {
                win.classList.remove('chatbot-closed');
                win.classList.add('chatbot-open');
                icon.className = 'fas fa-times text-amber-500 text-lg sm:text-xl';

                // Naikkan z-index saat terbuka
                widget.style.zIndex = '9999';

                setTimeout(() => {
                    const input = document.getElementById('chatbot-input');
                    if (input) input.focus();
                }, 300);
            } else {
                win.classList.remove('chatbot-open');
                win.classList.add('chatbot-closed');
                icon.className = 'fas fa-robot text-amber-500 text-lg sm:text-xl';

                // Turunkan z-index saat tertutup agar tidak menghalangi
                widget.style.zIndex = '40';
            }
        }

        async function sendChatMessage() {
            const input = document.getElementById('chatbot-input');
            const msg = input.value.trim();
            if (!msg) return;

            const messages = document.getElementById('chatbot-messages');
            const sendBtn = document.getElementById('chatbot-send-btn');

            // Append user bubble
            messages.innerHTML += `
                <div class="flex gap-2 justify-end">
                    <div class="bg-amber-500 rounded-2xl rounded-tr-sm px-3 py-2 text-xs text-white max-w-[80%]">${msg.replace(/</g,'&lt;')}</div>
                </div>`;
            input.value = '';
            messages.scrollTop = messages.scrollHeight;

            // Loading bubble
            const loadingId = 'chat-loading-' + Date.now();
            messages.innerHTML += `
                <div id="${loadingId}" class="flex gap-2">
                    <div class="w-6 h-6 rounded-full bg-amber-500 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fas fa-robot text-white text-[9px]"></i>
                    </div>
                    <div class="bg-slate-100 dark:bg-zinc-800 rounded-2xl rounded-tl-sm px-3 py-2 text-xs text-slate-400 dark:text-zinc-500">
                        <i class="fas fa-circle-notch fa-spin"></i> Scenty sedang berpikir...
                    </div>
                </div>`;
            messages.scrollTop = messages.scrollHeight;
            sendBtn.disabled = true;
            input.disabled = true;

            try {
                const res = await fetch('/api/chatbot', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message: msg })
                });
                const data = await res.json();
                const reply = data.reply || data.message || 'Maaf, terjadi kesalahan.';

                document.getElementById(loadingId)?.remove();
                messages.innerHTML += `
                    <div class="flex gap-2">
                        <div class="w-6 h-6 rounded-full bg-amber-500 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fas fa-robot text-white text-[9px]"></i>
                        </div>
                        <div class="bg-slate-100 dark:bg-zinc-800 rounded-2xl rounded-tl-sm px-3 py-2 text-xs text-slate-700 dark:text-zinc-300 max-w-[80%] whitespace-pre-wrap">${reply.replace(/</g,'&lt;')}</div>
                    </div>`;
            } catch (e) {
                document.getElementById(loadingId)?.remove();
                messages.innerHTML += `
                    <div class="flex gap-2">
                        <div class="w-6 h-6 rounded-full bg-rose-500 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fas fa-robot text-white text-[9px]"></i>
                        </div>
                        <div class="bg-rose-50 dark:bg-rose-500/10 rounded-2xl rounded-tl-sm px-3 py-2 text-xs text-rose-600 dark:text-rose-400 max-w-[80%]">Koneksi bermasalah. Coba lagi ya Kak.</div>
                    </div>`;
            } finally {
                sendBtn.disabled = false;
                input.disabled = false;
                input.focus();
                messages.scrollTop = messages.scrollHeight;
            }
        }
    </script>

    @yield('scripts')

    <script>
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: @json(session('success')), timer: 3000, timerProgressBar: true, showConfirmButton: false, toast: true, position: 'top' });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: @json(session('error')), toast: true, position: 'top', showConfirmButton: false, timer: 4000, timerProgressBar: true });
        @endif
        @if(session('warning'))
            Swal.fire({ icon: 'warning', title: 'Perhatian!', text: @json(session('warning')), toast: true, position: 'top', showConfirmButton: false, timer: 4000, timerProgressBar: true });
        @endif
        @if(session('info'))
            Swal.fire({ icon: 'info', title: 'Info', text: @json(session('info')), toast: true, position: 'top', showConfirmButton: false, timer: 3000, timerProgressBar: true });
        @endif
    </script>
</body>

</html>