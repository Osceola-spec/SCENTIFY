@extends('admin.layout')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="space-y-6 fade-in pb-12">

    <!-- Sticky Header Area -->
    <div class="sticky top-0 z-30 bg-adminbg/90 backdrop-blur-md pt-2 pb-4 border-b border-slate-200/50 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-mono uppercase tracking-wider text-slate-500 hover:text-amber-600 transition-colors mb-2">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Pesanan <span class="text-amber-500">#{{ $order->order_number }}</span></h1>
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-xs font-mono font-semibold text-slate-600">
                <i class="far fa-calendar-alt"></i> {{ $order->created_at->format('d M Y, H:i') }}
            </div>
        </div>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-700 text-sm font-medium shadow-sm mb-6">
            <i class="fas fa-check-circle text-emerald-500"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Kolom Kiri: Detail Item & Pengiriman (Col 8) -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- Card 1: Item yang Dibeli -->
            <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden p-6 sm:p-8">
                <h5 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-shopping-bag text-amber-500"></i> Item yang Dibeli
                </h5>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="text-slate-400 text-[10px] uppercase tracking-widest border-b border-slate-100">
                                <th class="pb-3 font-semibold">Parfum</th>
                                <th class="pb-3 font-semibold text-center">Ukuran</th>
                                <th class="pb-3 font-semibold text-center">Jumlah</th>
                                <th class="pb-3 font-semibold text-right">Harga Satuan</th>
                                <th class="pb-3 font-semibold text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach ($order->items as $item)
                                <tr class="border-b border-dashed border-slate-200 hover:bg-slate-50/50 transition-colors">
                                    <td class="py-4">
                                        <p class="font-bold text-slate-900">{{ $item->variant->product->name ?? 'Produk Dihapus' }}</p>
                                        <p class="text-[10px] font-mono text-amber-600 uppercase tracking-widest mt-0.5">
                                            {{ $item->variant->product->brand->name ?? 'Unknown Brand' }}
                                        </p>
                                    </td>
                                    <td class="py-4 text-center">
                                        <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-semibold">
                                            {{ $item->variant->size }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-center font-mono font-bold text-slate-700">
                                        {{ $item->quantity }}<span class="text-[10px] text-slate-400 ml-0.5">x</span>
                                    </td>
                                    <td class="py-4 text-right font-medium text-slate-600">
                                        Rp {{ number_format($item->price_at_purchase, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 text-right font-bold text-slate-900">
                                        Rp {{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                            <!-- Grand Total Row -->
                            <tr>
                                <td colspan="4" class="pt-6 text-right font-semibold text-slate-500 uppercase tracking-wider text-xs">
                                    Grand Total:
                                </td>
                                <td class="pt-6 text-right font-black text-xl text-emerald-600">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Card 2: Tujuan Pengiriman -->
            <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden p-6 sm:p-8">
                <h5 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-rose-500"></i> Tujuan Pengiriman
                </h5>

                <div class="flex flex-col sm:flex-row gap-6">
                    <div class="flex-1">
                        <p class="text-lg font-bold text-slate-900 mb-1">{{ $order->user->name ?? 'Pelanggan Scentify' }}</p>
                        <p class="text-sm font-mono text-slate-500 flex items-center gap-2 mb-4">
                            <i class="fas fa-phone text-slate-400"></i> {{ $order->phone_number ?? 'No. Telp Tersimpan di Alamat' }}
                        </p>
                    </div>
                    <div class="flex-[2]">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-sm text-slate-700 leading-relaxed relative">
                            <i class="fas fa-quote-left absolute top-3 left-3 text-2xl text-slate-200 opacity-50"></i>
                            <span class="relative z-10 block pl-2">{{ $order->shipping_address }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Status Operasional (Col 4 - Sticky) -->
        <div class="lg:col-span-4">
            <!-- Menggunakan tema dark (bg-slate-900) agar terlihat menonjol dan premium -->
            <div class="bg-slate-900 rounded-[1.5rem] border border-slate-800 shadow-xl overflow-hidden p-6 sm:p-8 lg:sticky lg:top-28 relative">
                
                <!-- Background decoration -->
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

                <h5 class="text-lg font-bold text-white mb-4 flex items-center gap-2 relative z-10">
                    <i class="fas fa-tasks text-amber-500"></i> Status Operasional
                </h5>
                <hr class="border-slate-800 mb-6 relative z-10">

                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="relative z-10">
                    @csrf
                    @method('PUT')

                    <!-- Dropdown Status -->
                    <div class="mb-5">
                        <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-2 font-bold">Tahapan Logistik</label>
                        <div class="relative">
                            <select name="status" class="w-full appearance-none bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white font-medium focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all cursor-pointer">
                                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>⏳ Pending (Belum Direspon)</option>
                                <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>📦 Processing (Sedang Dikemas)</option>
                                <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>🚚 Shipped (Diserahkan ke Kurir)</option>
                                <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>✅ Completed (Selesai)</option>
                                <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>❌ Cancelled (Batalkan Pesanan)</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-chevron-down text-[10px]"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Input Resi -->
                    <div class="mb-8">
                        <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-2 font-bold">Nomor Resi Pengiriman</label>
                        <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" 
                               placeholder="Contoh: JNE123456789"
                               class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-xl text-white font-mono text-sm placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
                        <p class="text-[10px] text-slate-500 mt-2 flex items-start gap-1.5 leading-relaxed">
                            <i class="fas fa-info-circle mt-0.5"></i> Isi bagian ini hanya jika paket sudah diserahkan secara fisik ke pihak jasa ekspedisi.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-amber-500 text-slate-900 font-bold tracking-wide py-3.5 rounded-xl hover:bg-amber-400 active:scale-95 transition-all shadow-lg shadow-amber-500/20 flex items-center justify-center gap-2">
                        <i class="fas fa-save text-sm"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection