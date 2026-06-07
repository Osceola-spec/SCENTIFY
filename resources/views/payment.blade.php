@extends('base.base')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center relative px-4 sm:px-6 py-24 sm:py-32 overflow-hidden z-10">
    
    <div class="absolute top-[10%] left-[20%] w-[300px] h-[300px] bg-emerald-500/10 dark:bg-emerald-500/5 rounded-full animate-float pointer-events-none filter blur-[80px] -z-10"></div>
    <div class="absolute bottom-[20%] right-[20%] w-[300px] h-[300px] bg-amber-500/10 dark:bg-amber-500/5 rounded-full animate-float pointer-events-none filter blur-[80px] -z-10" style="animation-delay: 2s;"></div>

    <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:30px_30px] pointer-events-none opacity-60 -z-10"></div>

    <div class="w-full max-w-lg reveal active">
        <div class="glass-card bg-white/80 dark:bg-darkcard/80 backdrop-blur-xl border border-slate-200 dark:border-white/10 rounded-[2.5rem] shadow-2xl p-8 sm:p-12 text-center relative overflow-hidden">
            
            <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto bg-emerald-100 dark:bg-emerald-500/20 rounded-full flex items-center justify-center mb-6 sm:mb-8 relative">
                <div class="absolute inset-0 rounded-full border-4 border-emerald-500/30 animate-ping"></div>
                <i class="fas fa-check text-3xl sm:text-4xl text-emerald-500 relative z-10"></i>
            </div>

            <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-950 dark:text-white mb-2">Complete Payment</h2>
            <p class="text-sm text-slate-500 dark:text-zinc-400 mb-8 flex justify-center items-center gap-2">
                Order Number: 
                <span class="font-mono font-bold text-slate-800 dark:text-zinc-200 px-2.5 py-1 bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-white/5 rounded-md">
                    #{{ $order->order_number }}
                </span>
            </p>

            <div class="bg-slate-50 dark:bg-zinc-900/50 border border-slate-100 dark:border-white/5 rounded-2xl p-6 mb-8 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-amber-500/10 rounded-full group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>
                
                <p class="text-[10px] sm:text-xs font-mono uppercase tracking-widest text-slate-400 dark:text-zinc-500 mb-2 relative z-10">Total Payment Amount</p>
                <h3 class="text-3xl sm:text-4xl font-black text-amber-600 dark:text-amber-400 relative z-10 tracking-tight">
                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </h3>
            </div>

            <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 mb-8 leading-relaxed max-w-sm mx-auto">
                Please complete your payment now so that your exclusive order can be processed and shipped immediately.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 mt-4">
                <button id="pay-button" class="flex-1 py-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-base shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 cursor-pointer">
                    Pay Now
                </button>
                <a href="{{ route('orders.index') }}"
                   class="flex-1 py-4 rounded-xl border border-amber-400 text-amber-500 bg-white dark:bg-darkcard/80 hover:bg-amber-50 dark:hover:bg-amber-900/10 font-bold text-base shadow-lg transition-all duration-200 text-center flex items-center justify-center"
                   onclick="return confirm('Are you sure you want to pay for this order later? You can access this page again through the My Orders menu.')">
                    Pay Later
                </a>
            </div>
            
            <div class="mt-6 pt-6 border-t border-slate-100 dark:border-white/5 flex items-center justify-center gap-2 text-[10px] text-slate-400 dark:text-zinc-500 font-medium uppercase tracking-widest">
                <i class="fas fa-lock text-emerald-500"></i> Transaksi Aman Didukung Oleh Midtrans
            </div>
        </div>
    </div>
</div>

<!-- Scentify Premium Payment Modal -->
<div id="scentify-payment-modal" class="fixed inset-0 hidden items-center justify-center" style="z-index: 99999;">
    <!-- Backdrop with blur -->
    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity duration-300 opacity-0" id="scentify-modal-backdrop"></div>
    
    <!-- Modal Container -->
    <div class="relative w-full max-w-md md:max-w-lg lg:max-w-xl bg-white dark:bg-zinc-900 border border-amber-500/30 rounded-3xl shadow-[0_0_50px_-12px_rgba(245,158,11,0.3)] overflow-hidden flex flex-col transform transition-all duration-300 scale-95 opacity-0" id="scentify-modal-content">
        <!-- Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-slate-950 to-slate-900 flex justify-between items-center border-b border-amber-500/20">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-amber-500/10 border border-amber-500/30 flex items-center justify-center">
                    <i class="fas fa-crown text-amber-500 text-sm"></i>
                </div>
                <h3 class="text-white font-serif font-bold tracking-wider">Scentify Secure Pay</h3>
            </div>
            <button id="close-payment-modal" class="w-8 h-8 rounded-full bg-slate-800 text-slate-400 hover:text-white hover:bg-rose-500 transition-colors flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Loading State before Midtrans loads -->
        <div id="snap-loading" class="absolute inset-0 top-[65px] bg-white dark:bg-zinc-900 z-10 flex flex-col items-center justify-center pointer-events-none transition-opacity duration-500">
            <div class="w-12 h-12 border-4 border-slate-200 dark:border-zinc-800 border-t-amber-500 rounded-full animate-spin mb-4"></div>
            <p class="text-sm font-mono text-slate-500 dark:text-zinc-400 uppercase tracking-widest animate-pulse">Menghubungkan ke Gateway...</p>
        </div>

        <!-- Midtrans Embed Container -->
        <div id="snap-container" class="w-full bg-slate-50 dark:bg-zinc-900 min-h-[500px] h-[70vh] max-h-[600px] overflow-hidden rounded-b-3xl"></div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function() {
    const payButton = document.getElementById('pay-button');
    const modal = document.getElementById('scentify-payment-modal');
    const backdrop = document.getElementById('scentify-modal-backdrop');
    const content = document.getElementById('scentify-modal-content');
    const snapLoading = document.getElementById('snap-loading');
    const closeBtn = document.getElementById('close-payment-modal');
    
    if (payButton) {
        payButton.onclick = function(e) {
            e.preventDefault();
            
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin text-lg"></i> Menyiapkan Gateway...';
            btn.classList.add('opacity-75', 'pointer-events-none', 'scale-95');

            const snapToken = "{{ $snapToken }}";
            
            if (!snapToken || snapToken === "") {
                Swal.fire({ icon: 'error', title: 'Error!', text: 'Snap Token dari server kosong!', confirmButtonColor: '#f59e0b' });
                btn.innerHTML = originalText;
                btn.classList.remove('opacity-75', 'pointer-events-none', 'scale-95');
                return;
            }

            // Tampilkan Modal Custom Scentify
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Animasi masuk
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);

            // Hilangkan loading setelah iframe kemungkinan besar ter-load
            setTimeout(() => {
                snapLoading.classList.add('opacity-0');
            }, 2500);

            // Jalankan Layar Embed SNAP Midtrans
            snap.embed(snapToken, {
                embedId: 'snap-container',
                onSuccess: function(result) {
                    console.log(result);
                    window.location.href = "{{ route('orders.payment_finished') }}?order_id=" + result.order_id;
                },
                onPending: function(result) {
                    console.log(result);
                    Swal.fire({
                        icon: 'info',
                        title: 'Waiting for Payment!',
                        text: 'Complete the payment instructions on your selected channel.',
                        confirmButtonColor: '#f59e0b'
                    }).then(() => {
                        window.location.href = "{{ route('orders.index') }}";
                    });
                },
                onError: function(result) {
                    console.log(result);
                    closeModal();
                    Swal.fire({
                        icon: 'error',
                        title: 'Payment Failed!',
                        text: 'An error occurred while processing your payment.',
                        confirmButtonColor: '#ef4444'
                    });
                }
            });

            function closeModal() {
                backdrop.classList.remove('opacity-100');
                backdrop.classList.add('opacity-0');
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
                
                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                    document.getElementById('snap-container').innerHTML = ''; // Hapus iframe embed
                    snapLoading.classList.remove('opacity-0'); // Reset loading
                }, 300);
                
                btn.innerHTML = originalText;
                btn.classList.remove('opacity-75', 'pointer-events-none', 'scale-95');
            }

            closeBtn.onclick = function() {
                closeModal();
                Swal.fire({
                    icon: 'warning',
                    title: 'Cancelled',
                    text: 'You closed the screen before completing the payment.',
                    confirmButtonColor: '#64748b'
                });
            };
        };
    }
});
</script>
@endsection