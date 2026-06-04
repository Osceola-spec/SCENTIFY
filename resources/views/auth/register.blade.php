@extends('base.base')

@section('content')
<!-- Animasi Latar Belakang Senada dengan Halaman Beranda -->
<style>
    @keyframes pulse-slow {
        0%, 100% { opacity: 0.15; transform: scale(1); }
        50% { opacity: 0.35; transform: scale(1.08); }
    }
    .animate-pulse-slow {
        animation: pulse-slow 10s ease-in-out infinite;
    }
</style>

<div class="min-h-screen flex items-center justify-center relative px-4 sm:px-6 overflow-hidden pt-28 pb-16 bg-white dark:bg-darkbg transition-colors duration-500">
    <!-- Ambient Glow Orbs -->
    <div class="absolute top-[10%] right-[5%] w-[250px] h-[250px] sm:w-[350px] sm:h-[350px] bg-amber-500/15 dark:bg-amber-500/5 rounded-full animate-pulse-slow pointer-events-none filter blur-[80px]"></div>
    <div class="absolute bottom-[10%] left-[5%] w-[300px] h-[300px] sm:w-[400px] sm:h-[400px] bg-purple-500/10 dark:bg-purple-900/5 rounded-full animate-pulse-slow pointer-events-none filter blur-[80px]" style="animation-delay: 3s;"></div>

    <!-- Garis Grid Latar Belakang -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:30px_30px] pointer-events-none opacity-60"></div>

    <div class="max-w-5xl w-full z-10 reveal">
        <!-- Container Utama: Desain Glassmorphism Premium (Form kiri, Gambar kanan untuk variasi dibanding Login) -->
        <div class="bg-white/40 dark:bg-darkcard/40 backdrop-blur-xl border border-slate-200 dark:border-white/5 rounded-[2rem] shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12 items-stretch min-h-[550px]">
            
            <!-- Sisi Kiri: Formulir Register -->
            <div class="md:col-span-6 p-8 sm:p-12 flex flex-col justify-center bg-white/70 dark:bg-darkcard/70 transition-colors duration-500 order-last md:order-first">
                <div class="mb-8 text-center sm:text-left">
                    <h3 class="text-2xl sm:text-3xl font-serif font-bold text-slate-950 dark:text-white">Buat Akun Baru</h3>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 mt-2">
                        Sudah memiliki akun? 
                        <a href="{{ route('login') }}" class="text-amber-600 dark:text-amber-400 font-semibold border-b border-amber-500/30 hover:border-amber-500 transition-colors">Masuk di sini</a>
                    </p>
                </div>

                <!-- Form Register -->
                <form method="POST" action="{{ route('register.auth') }}" class="space-y-5">
                    @csrf

                    <!-- Input Nama Lengkap (Floating Label Premium) -->
                    <div class="relative group">
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder=" "
                               class="w-full px-5 py-4 bg-slate-100/50 dark:bg-zinc-800/20 border @error('name') border-rose-500 @else border-slate-200 dark:border-white/5 @enderror rounded-2xl text-slate-950 dark:text-white placeholder-transparent peer focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all duration-300 text-sm">
                        <label for="name" class="absolute left-5 -top-3 text-slate-400 dark:text-zinc-500 text-xs sm:text-sm transition-all pointer-events-none peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:-top-3 peer-focus:left-3 peer-focus:text-xs peer-focus:text-amber-500 bg-white dark:bg-darkcard px-2 rounded">
                            <i class="far fa-user mr-1.5"></i> Nama Lengkap
                        </label>
                        @error('name')
                            <p class="text-rose-500 text-[10px] sm:text-xs mt-2 pl-2 flex items-center gap-1.5 font-medium">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Input Email (Floating Label Premium) -->
                    <div class="relative group">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder=" "
                               class="w-full px-5 py-4 bg-slate-100/50 dark:bg-zinc-800/20 border @error('email') border-rose-500 @else border-slate-200 dark:border-white/5 @enderror rounded-2xl text-slate-950 dark:text-white placeholder-transparent peer focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all duration-300 text-sm">
                        <label for="email" class="absolute left-5 -top-3 text-slate-400 dark:text-zinc-500 text-xs sm:text-sm transition-all pointer-events-none peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:-top-3 peer-focus:left-3 peer-focus:text-xs peer-focus:text-amber-500 bg-white dark:bg-darkcard px-2 rounded">
                            <i class="far fa-envelope mr-1.5"></i> Alamat Email
                        </label>
                        @error('email')
                            <p class="text-rose-500 text-[10px] sm:text-xs mt-2 pl-2 flex items-center gap-1.5 font-medium">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Input Password (Floating Label Premium) -->
                    <div class="relative group">
                        <input type="password" id="password" name="password" required placeholder=" "
                               class="w-full px-5 py-4 bg-slate-100/50 dark:bg-zinc-800/20 border @error('password') border-rose-500 @else border-slate-200 dark:border-white/5 @enderror rounded-2xl text-slate-950 dark:text-white placeholder-transparent peer focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all duration-300 text-sm">
                        <label for="password" class="absolute left-5 -top-3 text-slate-400 dark:text-zinc-500 text-xs sm:text-sm transition-all pointer-events-none peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:-top-3 peer-focus:left-3 peer-focus:text-xs peer-focus:text-amber-500 bg-white dark:bg-darkcard px-2 rounded">
                            <i class="fas fa-lock mr-1.5"></i> Kata Sandi
                        </label>
                        @error('password')
                            <p class="text-rose-500 text-[10px] sm:text-xs mt-2 pl-2 flex items-center gap-1.5 font-medium">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Input Konfirmasi Password (Floating Label Premium) -->
                    <div class="relative group">
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder=" "
                               class="w-full px-5 py-4 bg-slate-100/50 dark:bg-zinc-800/20 border border-slate-200 dark:border-white/5 rounded-2xl text-slate-950 dark:text-white placeholder-transparent peer focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all duration-300 text-sm">
                        <label for="password_confirmation" class="absolute left-5 -top-3 text-slate-400 dark:text-zinc-500 text-xs sm:text-sm transition-all pointer-events-none peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:-top-3 peer-focus:left-3 peer-focus:text-xs peer-focus:text-amber-500 bg-white dark:bg-darkcard px-2 rounded">
                            <i class="fas fa-check-double mr-1.5"></i> Konfirmasi Kata Sandi
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-4 mt-2 bg-slate-900 dark:bg-amber-400 text-white dark:text-black font-semibold tracking-wider uppercase rounded-2xl hover:bg-amber-500 dark:hover:bg-amber-300 active:scale-95 transition-all duration-300 text-sm shadow-lg shadow-amber-500/5">
                        Daftar Sekarang
                    </button>

                    <p class="text-center text-[10px] sm:text-xs text-slate-400 dark:text-zinc-500 leading-relaxed mt-4">
                        Dengan mendaftar, Anda menyetujui <a href="#" class="underline hover:text-amber-500 transition-colors">Syarat & Ketentuan</a> serta <a href="#" class="underline hover:text-amber-500 transition-colors">Kebijakan Privasi</a> Scentify.
                    </p>
                </form>
                <script>
                    document.addEventListener('DOMContentLoaded', function(){
                        const form = document.querySelector('form[action="{{ route('register.auth') }}"]');
                        if (!form) return;
                        const pwd = form.querySelector('input[name="password"]');
                        const pwdc = form.querySelector('input[name="password_confirmation"]');
                        const submit = form.querySelector('button[type="submit"]');
                        if (!pwd || !pwdc || !submit) return;

                        const errorEl = document.createElement('p');
                        errorEl.className = 'text-rose-500 text-[10px] sm:text-xs mt-2 pl-2 flex items-center gap-1.5 font-medium';
                        errorEl.style.display = 'none';
                        errorEl.innerHTML = '<i class="fas fa-exclamation-circle"></i> Password and confirmation must match.';
                        pwdc.parentNode.appendChild(errorEl);

                        function validate(){
                            if (pwd.value && pwdc.value && pwd.value !== pwdc.value){
                                errorEl.style.display = 'flex';
                                pwd.classList.add('border-rose-500');
                                pwdc.classList.add('border-rose-500');
                                submit.disabled = true;
                                submit.classList.add('opacity-50','cursor-not-allowed');
                                return false;
                            } else {
                                errorEl.style.display = 'none';
                                pwd.classList.remove('border-rose-500');
                                pwdc.classList.remove('border-rose-500');
                                submit.disabled = false;
                                submit.classList.remove('opacity-50','cursor-not-allowed');
                                return true;
                            }
                        }

                        pwd.addEventListener('input', validate);
                        pwdc.addEventListener('input', validate);
                        form.addEventListener('submit', function(e){
                            if (!validate()){
                                e.preventDefault();
                                pwdc.focus();
                            }
                        });
                    });
                </script>
            </div>

            <!-- Sisi Kanan: Gambar Estetik & Narasi (Sembunyi di Mobile) -->
            <div class="hidden md:flex md:col-span-6 relative flex-col justify-end p-10 overflow-hidden bg-cover bg-center" 
                 style="background-image: linear-gradient(to top, rgba(5,5,7,0.95) 20%, rgba(5,5,7,0.3) 100%), url('https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&q=80&w=800');">
                
                <div class="relative z-10 space-y-4">
                    <span class="text-xs font-mono text-amber-400 uppercase tracking-widest font-semibold">Join Scentify Circle</span>
                    <h2 class="text-3xl lg:text-4xl font-serif text-white font-bold leading-tight">Bergabung dengan Klub.</h2>
                    <p class="text-zinc-300 text-sm leading-relaxed">Dapatkan akses ke rilis produk terbatas (*limited edition*), kelola daftar keinginan (*wishlist*), dan dapatkan kenyamanan penelusuran status pesanan Anda.</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection