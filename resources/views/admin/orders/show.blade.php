@extends('admin.layout')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="space-y-6 fade-in pb-10">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 text-xs text-slate-400 hover:text-amber-500 transition-colors mb-2">
                <i class="fas fa-arrow-left"></i> Back to Order History
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Order Details</h1>
            <p class="text-sm text-slate-500 font-mono mt-0.5">#{{ $order->order_number }}</p>
        </div>

        @php
            $displayStatus = $order->status === 'Paid' ? 'Processing' : $order->status;
            $statusClass = match($displayStatus) {
                'Pending'    => 'bg-amber-50 text-amber-600 border-amber-200',
                'Processing' => 'bg-blue-50 text-blue-600 border-blue-200',
                'Shipped'    => 'bg-indigo-50 text-indigo-600 border-indigo-200',
                'Completed'  => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                'Cancelled'  => 'bg-rose-50 text-rose-600 border-rose-200',
                default      => 'bg-slate-50 text-slate-600 border-slate-200',
            };
            $statusIcon = match($displayStatus) {
                'Pending'    => 'fa-clock',
                'Processing' => 'fa-box-open',
                'Shipped'    => 'fa-truck',
                'Completed'  => 'fa-check-circle',
                'Cancelled'  => 'fa-times-circle',
                default      => 'fa-circle',
            };
        @endphp
        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold uppercase tracking-wider border {{ $statusClass }}">
            <i class="fas {{ $statusIcon }}"></i> {{ $displayStatus }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Info Pesanan & Produk --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Informasi Pelanggan --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Customer Information</h3>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-lg font-bold uppercase">
                        {{ substr($order->user->username ?? 'P', 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-slate-900 text-base">{{ $order->user->username ?? 'Customer' }}</p>
                        <p class="text-sm text-slate-400">{{ $order->user->email ?? '-' }}</p>
                        <p class="text-sm text-slate-400 font-mono">{{ $order->phone_number ?? '-' }}</p>
                    </div>
                </div>
                @if ($order->shipping_address)
                    <div class="mt-4 pt-4 border-t border-slate-50">
                        <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Shipping Address</p>
                        <p class="text-sm text-slate-700">{{ $order->shipping_address }}</p>
                    </div>
                @endif
            </div>

            {{-- Daftar Produk --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-50">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Ordered Products</h3>
                </div>
                <div class="divide-y divide-slate-50">
                    @foreach ($order->items as $item)
                        @php
                            $variant     = $item->variant;
                            $product     = $variant?->product;
                            $productName = $product?->name ?? 'Deleted Product';
                            $imgRaw      = $product?->image_url;
                            $imgSrc      = $imgRaw
                                ? (str_starts_with($imgRaw, 'http') ? $imgRaw : asset('product_image/' . $imgRaw))
                                : 'https://placehold.co/80x80?text=No+Img';
                            $itemPrice   = $item->price ?? $item->price_at_purchase ?? 0;
                        @endphp
                        <div class="flex items-center gap-4 px-6 py-4">
                            <img src="{{ $imgSrc }}" alt="{{ $productName }}"
                                class="w-14 h-14 rounded-xl object-cover bg-slate-100 border border-slate-100 shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-900 text-sm truncate">{{ $productName }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    Size: <span class="font-semibold text-slate-600">{{ $variant?->size ?? '-' }}ml</span>
                                </p>
                                <p class="text-xs text-slate-400">
                                    Qty: <span class="font-semibold text-slate-600">{{ $item->quantity }}</span>
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-xs text-slate-400 font-mono">@ Rp {{ number_format($itemPrice, 0, ',', '.') }}</p>
                                <p class="font-bold text-slate-900 text-sm mt-0.5">
                                    Rp {{ number_format($itemPrice * $item->quantity, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Rincian Biaya --}}
                @php
                    $subtotal = $order->subtotal;
                    $tax      = $order->tax_amount;
                    $total    = $order->total_amount;
                    $shipping = $total - $subtotal - $tax;
                @endphp
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 space-y-2">
                    <div class="flex justify-between items-center text-sm text-slate-500">
                        <span>Product Subtotal</span>
                        <span class="font-medium text-slate-700">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm text-slate-500">
                        <span>Shipping Cost</span>
                        <span class="font-medium text-slate-700">Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm text-slate-500 pb-3 border-b border-slate-200">
                        <span>Tax (11%)</span>
                        <span class="font-medium text-slate-700">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-sm font-bold text-slate-700">Total Payment</span>
                        <span class="text-lg font-bold text-slate-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Nomor Resi (jika sudah ada) --}}
            @if ($order->tracking_number)
                <div class="bg-indigo-50 border border-indigo-200 rounded-2xl px-6 py-4 flex items-center gap-4">
                    <i class="fas fa-truck text-indigo-500 text-xl"></i>
                    <div>
                        <p class="text-xs text-indigo-400 font-bold uppercase tracking-wider">Shipping Tracking Number</p>
                        <p class="font-mono font-bold text-indigo-800 text-base mt-0.5">{{ $order->tracking_number }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Kolom Kanan: Update Status & Waktu Terkini --}}
        <div class="space-y-6">

            {{-- Info Waktu Pesanan (Selalu Format WIB) --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-amber-400 to-amber-500"></div>
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="far fa-clock text-amber-500"></i> Order Time
                </h3>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400">Created</span>
                        <span class="font-semibold text-slate-700" id="createdAtTime" data-utc="{{ $order->created_at->toIso8601String() }}">
                            {{ $order->created_at->format('d M Y, H:i') }} WIB
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400">Updated</span>
                        <span class="font-semibold text-slate-700" id="updatedAtTime" data-utc="{{ $order->updated_at->toIso8601String() }}">
                            {{ $order->updated_at->format('d M Y, H:i') }} WIB
                        </span>
                    </div>
                </div>
            </div>

            {{-- Form Update Status --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Update Status</h3>

                @if (count($allowedStatuses) > 0 && !in_array($order->status, ['Completed', 'Cancelled']))
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" id="statusForm">
                        @csrf
                        @method('PUT')

                        {{-- Pilih Status --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">New Status</label>
                            <div class="relative">
                                <select name="status" id="statusSelect"
                                        onchange="handleStatusChange(this.value)"
                                        class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 pr-10 text-sm font-semibold text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all cursor-pointer">
                                    <option value="">— Select Status —</option>
                                    @foreach ($allowedStatuses as $status)
                                        @php
                                            $labelMap = [
                                                'Processing' => 'Processing',
                                                'Shipped'    => 'Shipped',
                                                'Completed'  => 'Completed',
                                                'Cancelled'  => 'Cancelled',
                                            ];
                                        @endphp
                                        <option value="{{ $status }}">{{ $labelMap[$status] ?? $status }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400">
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Input Nomor Resi --}}
                        <div id="trackingField" class="mb-4 hidden">
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">
                                Tracking Number <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="tracking_number" id="trackingInput"
                                   value="{{ old('tracking_number', $order->tracking_number) }}"
                                   placeholder="Example: JNE-1234567890"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono font-semibold text-slate-700 placeholder-slate-300 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
                            <p class="text-[11px] text-slate-400 mt-1.5">Required for Shipped status.</p>
                        </div>

                        {{-- Warning Cancelled --}}
                        <div id="cancelWarning" class="hidden mb-4 bg-rose-50 border border-rose-200 rounded-xl px-4 py-3">
                            <p class="text-xs text-rose-600 font-semibold flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle"></i>
                                Cancelled orders cannot be restored.
                            </p>
                        </div>

                        <button type="button" onclick="confirmUpdate()"
                                id="submitBtn"
                                disabled
                                class="w-full bg-slate-900 text-white font-bold text-sm py-3 rounded-xl hover:bg-amber-500 active:scale-95 transition-all shadow-md disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-slate-900">
                            <i class="fas fa-save mr-2"></i> Save Changes
                        </button>
                    </form>
                @else
                    <div class="text-center py-6">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-300 text-xl">
                            <i class="fas fa-lock"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-500">
                            @if ($order->status === 'Completed')
                                Order is completed.
                            @elseif ($order->status === 'Cancelled')
                                Order is cancelled.
                            @else
                                No further status.
                            @endif
                        </p>
                        <p class="text-xs text-slate-400 mt-1">Status can no longer be changed.</p>
                    </div>
                @endif
            </div>

            {{-- Progress Visual Status --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-5">Order Progress</h3>
                @php
                    $displayStatus = $order->status === 'Paid' ? 'Processing' : $order->status;
                    $steps = ['Pending', 'Processing', 'Shipped', 'Completed'];
                    $currentIdx = array_search($displayStatus, $steps);
                    $isCancelled = $order->status === 'Cancelled';

                    $colorMap = [
                        'Pending'    => ['bg' => 'bg-amber-500', 'text' => 'text-amber-600', 'badge' => 'bg-amber-50 text-amber-500 border-amber-200'],
                        'Processing' => ['bg' => 'bg-blue-500',  'text' => 'text-blue-600',  'badge' => 'bg-blue-50 text-blue-500 border-blue-200'],
                        'Shipped'    => ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-600', 'badge' => 'bg-indigo-50 text-indigo-500 border-indigo-200'],
                        'Completed'  => ['bg' => 'bg-emerald-500','text' => 'text-emerald-600','badge' => 'bg-emerald-50 text-emerald-500 border-emerald-200'],
                        'Cancelled'  => ['bg' => 'bg-rose-500',   'text' => 'text-rose-600',   'badge' => 'bg-rose-50 text-rose-500 border-rose-200']
                    ];

                    $iconMap = [
                        'Pending' => 'fa-clock', 
                        'Processing' => 'fa-box-open', 
                        'Shipped' => 'fa-truck', 
                        'Completed' => 'fa-check-circle'
                    ];
                @endphp
                <div class="space-y-3">
                    @foreach ($steps as $i => $step)
                        @php
                            $isDone    = !$isCancelled && $currentIdx !== false && $i <= $currentIdx;
                            $isCurrent = !$isCancelled && $currentIdx !== false && $i === $currentIdx;
                            
                            if ($isCurrent) {
                                $stepClass = $colorMap[$step]['bg'] . ' text-white shadow-md ring-4 ring-' . ($step == 'Pending' ? 'amber' : ($step == 'Processing' ? 'blue' : ($step == 'Shipped' ? 'indigo' : 'emerald'))) . '-100';
                                $textClass = $colorMap[$step]['text'] . ' font-bold';
                            } elseif ($isDone) {
                                $stepClass = 'bg-emerald-500 text-white';
                                $textClass = 'text-slate-700 font-semibold';
                            } else {
                                $stepClass = 'bg-slate-100 text-slate-300';
                                $textClass = 'text-slate-300';
                            }
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 text-xs transition-all {{ $stepClass }}">
                                <i class="fas {{ $isDone && !$isCurrent ? 'fa-check' : $iconMap[$step] }}"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm transition-colors {{ $textClass }}">
                                    {{ $step }}
                                </p>
                            </div>
                            @if ($isCurrent)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $colorMap[$step]['badge'] }}">Now</span>
                            @endif
                        </div>
                    @endforeach

                    @if ($isCancelled)
                        <div class="flex items-center gap-3 mt-1">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 text-xs bg-rose-500 text-white shadow-md ring-4 ring-rose-100">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-rose-600">Cancelled</p>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $colorMap['Cancelled']['badge'] }}">Now</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // --- FITUR LOCAL TIMEZONE (WIB FIXED) ---
    function convertUTCToLocal() {
        const options = { 
            day: '2-digit', 
            month: 'short', 
            year: 'numeric', 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: false,
            timeZone: 'Asia/Jakarta' // Memaksa kalkulasi waktu ke zona Asia/Jakarta (WIB)
        };

        ['createdAtTime', 'updatedAtTime'].forEach(id => {
            const el = document.getElementById(id);
            if (el && el.getAttribute('data-utc')) {
                const utcDate = new Date(el.getAttribute('data-utc'));
                if (!isNaN(utcDate.getTime())) {
                    // Konversi waktu dan format tanda titik ke titik dua jika ada (misal 14.30 -> 14:30)
                    const localString = utcDate.toLocaleDateString('id-ID', options).replace(/\./g, ':');
                    el.textContent = `${localString} WIB`;
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        convertUTCToLocal();
    });

    // --- LOGIC FORM STATUS ---
    function handleStatusChange(val) {
        const trackingField  = document.getElementById('trackingField');
        const cancelWarning  = document.getElementById('cancelWarning');
        const submitBtn      = document.getElementById('submitBtn');
        const trackingInput  = document.getElementById('trackingInput');

        trackingField.classList.add('hidden');
        cancelWarning.classList.add('hidden');
        trackingInput.removeAttribute('required');

        if (val === 'Shipped') {
            trackingField.classList.remove('hidden');
            trackingInput.setAttribute('required', 'required');
        }

        if (val === 'Cancelled') {
            cancelWarning.classList.remove('hidden');
        }

        submitBtn.disabled = val === '';
    }

    function confirmUpdate() {
        const status        = document.getElementById('statusSelect').value;
        const trackingInput = document.getElementById('trackingInput');

        if (!status) return;

        if (status === 'Shipped' && !trackingInput.value.trim()) {
            trackingInput.focus();
            trackingInput.classList.add('border-rose-400', 'ring-1', 'ring-rose-400');
            setTimeout(() => trackingInput.classList.remove('border-rose-400', 'ring-1', 'ring-rose-400'), 2000);
            return;
        }

        const labelMap = {
            'Processing': 'Processing',
            'Shipped':    'Shipped',
            'Completed':  'Completed',
            'Cancelled':  'Cancelled',
        };

        const isCancelled = status === 'Cancelled';

        Swal.fire({
            title: isCancelled ? 'Cancel Order?' : 'Confirm Status Change',
            html: isCancelled
                ? `<p class="text-sm text-slate-500">This order will be permanently cancelled and <strong>cannot be restored</strong>.</p>`
                : `<p class="text-sm text-slate-500">Change order status to <strong>${labelMap[status]}</strong>?</p>`,
            icon: isCancelled ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonText: isCancelled ? '<i class="fas fa-times mr-1"></i> Yes, Cancel' : '<i class="fas fa-check mr-1"></i> Yes, Update',
            cancelButtonText: 'Cancel',
            confirmButtonColor: isCancelled ? '#f43f5e' : '#f59e0b',
            cancelButtonColor: '#64748b',
            reverseButtons: true,
            customClass: { popup: 'rounded-2xl' }
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById('statusForm').submit();
            }
        });
    }
</script>
@endsection