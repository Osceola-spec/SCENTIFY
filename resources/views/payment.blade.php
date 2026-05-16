<!-- resources/views/payment.blade.php -->
@extends('base.base')

@section('content')
    <div class="container py-5 mt-5 text-center">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-lg rounded-4 p-5">
                    <i class="fas fa-check-circle text-success fa-4x mb-4"></i>
                    <h3 class="fw-bold">Pesanan Berhasil Dibuat!</h3>
                    <p class="text-muted">Nomor Pesanan: <strong>{{ $order->order_number }}</strong></p>

                    <div class="bg-light p-3 rounded-3 mb-4">
                        <p class="mb-1 text-muted small">Total Tagihan:</p>
                        <h2 class="fw-bold mb-0">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h2>
                    </div>

                    <p class="text-muted small mb-4">Silakan selesaikan pembayaran Anda agar pesanan dapat segera kami
                        proses.</p>

                    <!-- Tombol ini yang akan memicu popup Midtrans -->
                    <button id="pay-button" class="btn btn-dark btn-lg rounded-pill px-5 py-3 fw-bold w-100">
                        <i class="fas fa-wallet me-2"></i> Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Snap API Midtrans -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            // SnapToken dari controller diletakkan di sini
            snap.pay('{{ $snapToken }}', {
                // Callback ketika pembayaran sukses
                onSuccess: function(result) {
                    window.location.href = "{{ route('home') }}?status=success";
                },
                // Callback ketika pembayaran pending
                onPending: function(result) {
                    alert("Menunggu pembayaran Anda!");
                    console.log(result);
                },
                // Callback ketika pembayaran gagal
                onError: function(result) {
                    alert("Pembayaran gagal!");
                    console.log(result);
                },
                // Callback ketika popup ditutup tanpa bayar
                onClose: function() {
                    alert('Anda menutup layar sebelum menyelesaikan pembayaran');
                }
            });
        };
    </script>
@endsection
