@extends('base.base')

@section('content')
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
    <div class="absolute top-[10%] left-[5%] w-[250px] h-[250px] sm:w-[350px] sm:h-[350px] bg-amber-500/15 dark:bg-amber-500/5 rounded-full animate-pulse-slow pointer-events-none filter blur-[80px]"></div>
    <div class="absolute bottom-[10%] right-[5%] w-[300px] h-[300px] sm:w-[400px] sm:h-[400px] bg-purple-500/10 dark:bg-purple-900/5 rounded-full animate-pulse-slow pointer-events-none filter blur-[80px]" style="animation-delay: 3s;"></div>

    <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:30px_30px] pointer-events-none opacity-60"></div>

    <div class="max-w-5xl w-full z-10 reveal">
        <div class="bg-white/40 dark:bg-darkcard/40 backdrop-blur-xl border border-slate-200 dark:border-white/5 rounded-[2rem] shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12 items-stretch min-h-[550px]">
            
            <div class="hidden md:flex md:col-span-6 relative flex-col justify-end p-10 overflow-hidden bg-cover bg-center" 
                 style="background-image: linear-gradient(to top, rgba(5,5,7,0.9) 20%, rgba(5,5,7,0.3) 100%), url('https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&q=80&w=800');">
                
                <div class="relative z-10 space-y-4">
                    <span class="text-xs font-mono text-amber-400 uppercase tracking-widest font-semibold">Welcome Back</span>
                    <h2 class="text-3xl lg:text-4xl font-serif text-white font-bold leading-tight">Selamat Datang Kembali.</h2>
                    <p class="text-zinc-300 text-sm leading-relaxed">Lanjutkan perjalanan Anda untuk menemukan aroma khas yang mendefinisikan karakter sejati Anda.</p>
                </div>
            </div>

            <div class="md:col-span-6 p-8 sm:p-12 flex flex-col justify-center bg-white/70 dark:bg-darkcard/70 transition-colors duration-500">
                <div class="mb-10 text-center sm:text-left">
                    <h3 class="text-2xl sm:text-3xl font-serif font-bold text-slate-950 dark:text-white">Masuk ke Scentify</h3>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 mt-2">
                        Belum memiliki akun? 
                        <a href="{{ route('register') }}" class="text-amber-600 dark:text-amber-400 font-semibold border-b border-amber-500/30 hover:border-amber-500 transition-colors">Daftar sekarang</a>
                    </p>
                </div>

                <form method="POST" action="{{ route('login.auth') }}" class="space-y-6">
                    @csrf

                    <div class="relative group">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder=" "
                               class="w-full px-5 py-4 bg-slate-100/50 dark:bg-zinc-800/20 border @error('email') border-rose-500 @else border-slate-200 dark:border-white/5 @enderror rounded-2xl text-slate-950 dark:text-white placeholder-transparent peer focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all duration-300">
                        <label for="email" class="absolute left-3 -top-3 text-slate-400 dark:text-zinc-500 text-xs font-medium transition-all pointer-events-none peer-placeholder-shown:text-sm peer-placeholder-shown:top-4 peer-placeholder-shown:left-5 peer-focus:-top-3 peer-focus:left-3 peer-focus:text-xs peer-focus:text-amber-500 bg-white dark:bg-darkcard px-2 rounded">
                            <i class="far fa-envelope mr-1.5"></i> Alamat Email
                        </label>
                        @error('email')
                            <p class="text-rose-500 text-[10px] sm:text-xs mt-2 pl-2 flex items-center gap-1.5 font-medium">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="relative group">
                        <input type="password" id="password" name="password" required placeholder=" "
                               class="w-full px-5 py-4 bg-slate-100/50 dark:bg-zinc-800/20 border @error('password') border-rose-500 @else border-slate-200 dark:border-white/5 @enderror rounded-2xl text-slate-950 dark:text-white placeholder-transparent peer focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all duration-300">
                        <label for="password" class="absolute left-3 -top-3 text-slate-400 dark:text-zinc-500 text-xs font-medium transition-all pointer-events-none peer-placeholder-shown:text-sm peer-placeholder-shown:top-4 peer-placeholder-shown:left-5 peer-focus:-top-3 peer-focus:left-3 peer-focus:text-xs peer-focus:text-amber-500 bg-white dark:bg-darkcard px-2 rounded">
                            <i class="fas fa-lock mr-1.5"></i> Kata Sandi
                        </label>
                        @error('password')
                            <p class="text-rose-500 text-[10px] sm:text-xs mt-2 pl-2 flex items-center gap-1.5 font-medium">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between text-xs sm:text-sm">
                        <label class="flex items-center group cursor-pointer text-slate-500 dark:text-zinc-400 hover:text-amber-500 transition-colors">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                                   class="rounded border-slate-300 dark:border-zinc-700 text-amber-500 focus:ring-amber-500 bg-transparent mr-2.5 w-4.5 h-4.5 transition-colors cursor-pointer">
                            <span class="font-medium selection:bg-transparent">Ingat Saya</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-slate-400 dark:text-zinc-500 hover:text-amber-500 transition-colors font-medium">Lupa Sandi?</a>
                        @endif
                    </div>

                    <button type="submit" class="w-full py-4 bg-slate-900 dark:bg-amber-400 text-white dark:text-black font-semibold tracking-wider uppercase rounded-2xl hover:bg-amber-500 dark:hover:bg-amber-300 active:scale-95 transition-all duration-300 text-sm shadow-lg shadow-amber-500/5">
                        Masuk
                    </button>

                    <div class="relative flex py-4 items-center">
                        <div class="flex-grow border-t border-slate-200 dark:border-white/10"></div>
                        <span class="flex-shrink mx-4 text-xs font-mono uppercase text-slate-400 dark:text-zinc-500">Atau masuk dengan</span>
                        <div class="flex-grow border-t border-slate-200 dark:border-white/10"></div>
                    </div>

                    <div class="grid grid-cols-1 gap-3.5">
                        <a href="{{ route('google.login') }}" class="flex items-center justify-center gap-2 py-3 rounded-xl border border-slate-200 dark:border-white/10 hover:border-rose-500/50 hover:bg-rose-500/5 text-xs font-semibold text-slate-700 dark:text-zinc-300 transition-all active:scale-95">
                            <i class="fab fa-google text-rose-500"></i> Google
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    function showSocialDemoAlert(event, platform) {
        event.preventDefault();
        Swal.fire({
            icon: 'info',
            title: 'Layanan Eksklusif',
            text: `Masuk menggunakan ${platform} saat ini sedang dalam proses konfigurasi API keamanan.`,
            confirmButtonColor: '#f59e0b',
            customClass: { popup: document.documentElement.classList.contains('dark') ? 'dark-swal' : '' }
        });
    }
</script>
@endsection