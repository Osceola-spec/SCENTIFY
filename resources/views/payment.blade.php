@extends('base.base')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center relative px-4 sm:px-6 py-24 sm:py-32 overflow-hidden z-10">
    
    <!-- Ambient Glow Orbs -->
    <div class="absolute top-[10%] left-[20%] w-[300px] h-[300px] bg-emerald-500/10 dark:bg-emerald-500/5 rounded-full animate-float pointer-events-none filter blur-[80px] -z-10"></div>
    <div class="absolute bottom-[20%] right-[20%] w-[300px] h-[300px] bg-amber-500/10 dark:bg-amber-500/5 rounded-full animate-float pointer-events-none filter blur-[80px] -z-10" style="animation-delay: 2s;"></div>

    <!-- Garis Grid Latar Belakang -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:30px_30px] pointer-events-none opacity-60 -z-10"></div>

    <!-- Kartu Pembayaran Premium -->
    <div class="w-full max-w-lg reveal active">
        <div class="glass-card bg-white/80 dark:bg-darkcard/80 backdrop-blur-xl border border-slate-200 dark:border-white/10 rounded-[2.5rem] shadow-2xl p-8 sm:p-12 text-center relative overflow-hidden">
            
            <!-- Animasi Lingkaran Sukses -->
            <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto bg-emerald-100 dark:bg-emerald-500/20 rounded-full flex items-center justify-center mb-6 sm:mb-8 relative">
                <div class="absolute inset-0 rounded-full border-4 border-emerald-500/30 animate-ping"></div>
                <i class="fas fa-check text-3xl sm:text-4xl text-emerald-500 relative z-10"></i>
            </div>

            <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-950 dark:text-white mb-2">Pesanan Berhasil Dibuat!</h2>
            <p class="text-sm text-slate-500 dark:text-zinc-400 mb-8 flex justify-center items-center gap-2">
                Nomor Pesanan: 
                <span class="font-mono font-bold text-slate-800 dark:text-zinc-200 px-2.5 py-1 bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-white/5 rounded-md">
                    #{{ $order->order_number }}
                </span>
            </p>

            <!-- Box Total Tagihan -->
            <div class="bg-slate-50 dark:bg-zinc-900/50 border border-slate-100 dark:border-white/5 rounded-2xl p-6 mb-8 relative overflow-hidden group">
                <!-- Efek Hover Latar Dalam Box -->
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-amber-500/10 rounded-full group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>
                
                <p class="text-[10px] sm:text-xs font-mono uppercase tracking-widest text-slate-400 dark:text-zinc-500 mb-2 relative z-10">Total Tagihan Pembayaran</p>
                <h3 class="text-3xl sm:text-4xl font-black text-amber-600 dark:text-amber-400 relative z-10 tracking-tight">
                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </h3>
            </div>

            <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 mb-8 leading-relaxed max-w-sm mx-auto">
                Silakan selesaikan pembayaran Anda sekarang agar pesanan eksklusif Anda dapat segera kami proses dan kirimkan.
            </p>

            <!-- Tombol Pay Utama yang Memicu Midtrans Snap -->
            <button id="pay-button" class="w-full bg-slate-950 dark:bg-amber-400 text-white dark:text-black py-4 rounded-xl font-bold tracking-widest uppercase shadow-xl shadow-slate-900/20 dark:shadow-amber-500/20 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3">
                <i class="fas fa-wallet text-lg"></i> Bayar Sekarang
            </button>
            
            <div class="mt-6 pt-6 border-t border-slate-100 dark:border-white/5 flex items-center justify-center gap-2 text-[10px] text-slate-400 dark:text-zinc-500 font-medium uppercase tracking-widest">
                <i class="fas fa-lock text-emerald-500"></i> Transaksi Aman Didukung Oleh Midtrans
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Script Snap API Midtrans -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script type="text/javascript">
    document.getElementById('pay-button').onclick = function() {
        
        // Animasi loading pada tombol saat diklik
        const btn = this;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin text-lg"></i> Menyiapkan Gateway...';
        btn.classList.add('opacity-75', 'pointer-events-none', 'scale-95');

        // SnapToken dari controller dijalankan
        snap.pay('{{ $snapToken }}', {
            // Callback ketika pembayaran sukses
            onSuccess: function(result) {
                window.location.href = "{{ route('home') }}?status=success";
            },
            
            // Callback ketika pembayaran pending
            onPending: function(result) {
                Swal.fire({
                    icon: 'info',
                    title: 'Menunggu Pembayaran!',
                    text: 'Selesaikan instruksi pembayaran pada channel pembayaran yang Anda pilih.',
                    confirmButtonColor: '#f59e0b',
                    customClass: { popup: document.documentElement.classList.contains('dark') ? 'dark-swal rounded-[1.5rem]' : 'rounded-[1.5rem]' }
                });
                console.log(result);
                // Kembalikan status tombol
                btn.innerHTML = originalText;
                btn.classList.remove('opacity-75', 'pointer-events-none', 'scale-95');
            },
            
            // Callback ketika pembayaran gagal
            onError: function(result) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Gagal!',
                    text: 'Terjadi kesalahan saat memproses pembayaran Anda. Silakan coba metode lain.',
                    confirmButtonColor: '#ef4444',
                    customClass: { popup: document.documentElement.classList.contains('dark') ? 'dark-swal rounded-[1.5rem]' : 'rounded-[1.5rem]' }
                });
                console.log(result);
                btn.innerHTML = originalText;
                btn.classList.remove('opacity-75', 'pointer-events-none', 'scale-95');
            },
            
            // Callback ketika popup Midtrans ditutup tanpa melakukan bayar
            onClose: function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Dibatalkan',
                    text: 'Anda menutup layar sebelum menyelesaikan pembayaran. Anda dapat melanjutkannya nanti.',
                    confirmButtonColor: '#64748b',
                    customClass: { popup: document.documentElement.classList.contains('dark') ? 'dark-swal rounded-[1.5rem]' : 'rounded-[1.5rem]' }
                });
                btn.innerHTML = originalText;
                btn.classList.remove('opacity-75', 'pointer-events-none', 'scale-95');
            }
        });
    };
</script>
@endsection