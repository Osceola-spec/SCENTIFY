@extends('base.base')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-32 relative z-10">

    {{-- Ambient Glow --}}
    <div class="absolute top-1/4 left-1/4 w-[300px] h-[300px] bg-amber-500/10 dark:bg-amber-500/5 rounded-full pointer-events-none filter blur-[100px] -z-10"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[250px] h-[250px] bg-purple-500/10 dark:bg-purple-900/5 rounded-full pointer-events-none filter blur-[100px] -z-10"></div>

    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="glass-card bg-white/70 dark:bg-darkcard/70 rounded-3xl border border-slate-200 dark:border-white/5 shadow-2xl p-8 sm:p-10">

            {{-- Icon --}}
            <div class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-envelope-open-text text-2xl text-amber-500"></i>
            </div>

            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900 dark:text-white">Verifikasi Email</h1>
                <p class="text-sm text-slate-500 dark:text-zinc-400 mt-2 leading-relaxed">
                    Masukkan 6 digit kode OTP yang telah dikirim ke email Anda.
                </p>
            </div>

            {{-- Alert --}}
            @if(session('status'))
                <div class="mb-6 flex items-center gap-3 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 rounded-xl px-4 py-3 text-sm font-medium">
                    <i class="fas fa-check-circle shrink-0"></i>
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 flex items-center gap-3 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-600 dark:text-rose-400 rounded-xl px-4 py-3 text-sm font-medium">
                    <i class="fas fa-exclamation-circle shrink-0"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('verify.email.post') }}" id="otpForm">
                @csrf

                {{-- Hidden input yang akan dikirim ke server --}}
                <input type="hidden" name="code" id="otpHidden">

                {{-- 6 Kotak OTP --}}
                <div class="flex items-center justify-center gap-2 sm:gap-3 mb-8" id="otpBoxes">
                    @for ($i = 0; $i < 6; $i++)
                        <input
                            type="text"
                            inputmode="numeric"
                            maxlength="1"
                            pattern="[0-9]"
                            class="otp-input w-11 h-14 sm:w-13 sm:h-16 text-center text-xl sm:text-2xl font-bold bg-white dark:bg-zinc-900/80 border-2 border-slate-200 dark:border-white/10 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all duration-200 caret-transparent"
                            autocomplete="off"
                            data-index="{{ $i }}"
                        >
                    @endfor
                </div>

                {{-- Tombol --}}
                <button type="submit" id="submitBtn"
                        class="w-full py-3.5 bg-slate-900 dark:bg-amber-400 text-white dark:text-black font-bold text-sm tracking-widest uppercase rounded-xl hover:bg-amber-500 dark:hover:bg-amber-300 active:scale-95 transition-all shadow-lg disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-slate-900 dark:disabled:hover:bg-amber-400 disabled:active:scale-100"
                        disabled>
                    <i class="fas fa-shield-check mr-2"></i> Verifikasi Sekarang
                </button>
            </form>

            {{-- Resend --}}
            <div class="mt-5 text-center">
                <p class="text-xs text-slate-400 dark:text-zinc-500 mb-3">Tidak menerima kode?</p>
                <form method="POST" action="{{ route('resend.otp') }}">
                    @csrf
                    <button type="submit"
                            class="text-sm font-semibold text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 transition-colors underline underline-offset-2">
                        Kirim Ulang Kode OTP
                    </button>
                </form>
            </div>

            {{-- Footer note --}}
            <p class="text-center text-[11px] text-slate-300 dark:text-zinc-600 mt-6 flex items-center justify-center gap-1.5">
                <i class="fas fa-lock"></i> Kode berlaku selama 10 menit
            </p>

        </div>
    </div>
</div>

<style>
    .otp-input {
        width: 48px;
        height: 60px;
    }
    @media (min-width: 640px) {
        .otp-input { width: 56px; height: 68px; }
    }
    .otp-input.filled {
        border-color: #f59e0b;
        background-color: rgba(245, 158, 11, 0.05);
    }
    .otp-input.error {
        border-color: #f43f5e;
        animation: shake 0.3s ease;
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-4px); }
        75% { transform: translateX(4px); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputs     = document.querySelectorAll('.otp-input');
    const hiddenInput = document.getElementById('otpHidden');
    const submitBtn  = document.getElementById('submitBtn');
    const form       = document.getElementById('otpForm');

    // Sync semua input ke hidden field & update tombol
    function syncOTP() {
        const val = Array.from(inputs).map(i => i.value).join('');
        hiddenInput.value = val;

        const complete = val.length === 6 && /^\d{6}$/.test(val);
        submitBtn.disabled = !complete;

        inputs.forEach((inp, idx) => {
            inp.classList.toggle('filled', inp.value !== '');
        });
    }

    inputs.forEach((input, index) => {

        // Hanya terima angka
        input.addEventListener('keydown', function (e) {
            const allowed = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'];
            if (!/^\d$/.test(e.key) && !allowed.includes(e.key)) {
                e.preventDefault();
            }
        });

        input.addEventListener('input', function (e) {
            // Ambil hanya angka dari input
            const digit = this.value.replace(/\D/g, '').slice(-1);
            this.value = digit;

            syncOTP();

            // Pindah ke kotak berikutnya
            if (digit && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        // Backspace: hapus dan mundur
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace') {
                if (!this.value && index > 0) {
                    inputs[index - 1].value = '';
                    inputs[index - 1].focus();
                    syncOTP();
                }
            }
            if (e.key === 'ArrowLeft' && index > 0) inputs[index - 1].focus();
            if (e.key === 'ArrowRight' && index < inputs.length - 1) inputs[index + 1].focus();
        });

        // Paste: distribusi ke 6 kotak
        input.addEventListener('paste', function (e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData)
                .getData('text')
                .replace(/\D/g, '')
                .slice(0, 6);

            pasted.split('').forEach((digit, i) => {
                if (inputs[i]) inputs[i].value = digit;
            });

            syncOTP();

            // Fokus ke kotak terakhir yang terisi
            const nextEmpty = Array.from(inputs).findIndex(inp => !inp.value);
            if (nextEmpty !== -1) {
                inputs[nextEmpty].focus();
            } else {
                inputs[inputs.length - 1].focus();
            }
        });

        // Klik ulang pada kotak yang sudah terisi — pilih semua
        input.addEventListener('click', function () {
            this.select();
        });
    });

    // Focus ke kotak pertama saat load
    inputs[0].focus();

    // Validasi sebelum submit
    form.addEventListener('submit', function (e) {
        const val = hiddenInput.value;
        if (!/^\d{6}$/.test(val)) {
            e.preventDefault();
            inputs.forEach(inp => inp.classList.add('error'));
            setTimeout(() => inputs.forEach(inp => inp.classList.remove('error')), 500);
        }
    });
});
</script>
@endsection