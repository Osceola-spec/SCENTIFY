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

    <div class="max-w-xl w-full z-10 reveal">
        <div class="bg-white/70 dark:bg-darkcard/70 backdrop-blur-xl border border-slate-200 dark:border-white/5 rounded-[2rem] shadow-2xl p-8 sm:p-12 transition-colors duration-500">
            
            <div class="mb-8 text-center">
                <div class="w-16 h-16 bg-amber-100 dark:bg-amber-500/20 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl shadow-inner">
                    <i class="fas fa-key"></i>
                </div>
                <h3 class="text-2xl sm:text-3xl font-serif font-bold text-slate-950 dark:text-white">Lupa Kata Sandi?</h3>
                <p class="text-sm text-slate-500 dark:text-zinc-400 mt-3 leading-relaxed">
                    Jangan khawatir. Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
                </p>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-sm flex items-start gap-3">
                    <i class="fas fa-check-circle mt-0.5"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-sm flex items-start gap-3">
                    <i class="fas fa-check-circle mt-0.5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <div class="relative group">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder=" "
                           class="w-full px-5 py-4 bg-slate-100/50 dark:bg-zinc-800/20 border @error('email') border-rose-500 @else border-slate-200 dark:border-white/5 @enderror rounded-2xl text-slate-950 dark:text-white placeholder-transparent peer focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all duration-300">
                    <label for="email" class="absolute left-3 -top-3 text-slate-400 dark:text-zinc-500 text-xs font-medium transition-all pointer-events-none peer-placeholder-shown:text-sm peer-placeholder-shown:top-4 peer-placeholder-shown:left-5 peer-focus:-top-3 peer-focus:left-3 peer-focus:text-xs peer-focus:text-amber-500 bg-white/90 dark:bg-darkcard/90 px-2 rounded">
                        <i class="far fa-envelope mr-1.5"></i> Alamat Email
                    </label>
                    @error('email')
                        <p class="text-rose-500 text-[10px] sm:text-xs mt-2 pl-2 flex items-center gap-1.5 font-medium">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-4 bg-slate-900 dark:bg-amber-400 text-white dark:text-black font-semibold tracking-wider uppercase rounded-2xl hover:bg-amber-500 dark:hover:bg-amber-300 active:scale-95 transition-all duration-300 text-sm shadow-lg shadow-amber-500/5">
                    Kirim Tautan Pemulihan
                </button>
                
                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-sm text-slate-500 dark:text-zinc-400 hover:text-amber-500 font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Halaman Masuk
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
