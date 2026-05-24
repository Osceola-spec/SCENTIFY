@extends('admin.layout')

@section('title', 'Detail Pelanggan — ' . $user->name)

@section('content')
<div class="space-y-6 fade-in pb-10">

    {{-- Back & Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <a href="{{ route('admin.customers.index') }}"
               class="inline-flex items-center gap-2 text-xs text-slate-400 hover:text-amber-500 transition-colors mb-2">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pelanggan
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Detail Pelanggan</h1>
        </div>
        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold uppercase tracking-wider border {{ $level['bg'] }} {{ $level['color'] }} {{ $level['border'] }}">
            <i class="fas {{ $level['icon'] }}"></i> {{ $level['name'] }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri --}}
        <div class="space-y-6">

            {{-- Profile Card --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 text-center">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-3xl font-black text-white uppercase mx-auto shadow-lg shadow-amber-200">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h2 class="text-lg font-bold text-slate-900 mt-4">{{ $user->name }}</h2>
                <p class="text-sm text-slate-400 mt-0.5">{{ $user->email }}</p>
                <p class="text-xs font-mono text-slate-400 mt-1">Bergabung {{ $user->created_at->format('d M Y') }}</p>

                {{-- Level Badge Besar --}}
                <div class="mt-5 p-4 rounded-2xl border {{ $level['border'] }} {{ $level['bg'] }}">
                    <div class="flex items-center justify-center gap-2 mb-2">
                        <i class="fas {{ $level['icon'] }} {{ $level['color'] }} text-xl"></i>
                        <span class="text-lg font-black {{ $level['color'] }}">{{ $level['name'] }}</span>
                    </div>

                    {{-- Progress Bar --}}
                    @php
                        $levels   = ['Bronze' => 0, 'Silver' => 500000, 'Gold' => 2000000, 'Platinum' => 5000000, 'Diamond' => 10000000];
                        $spending = (int)($user->total_spending ?? 0);
                        $prevMin  = $levels[$level['name']] ?? 0;
                    @endphp
                    @if ($nextLevel)
                        @php
                            $progress = min(100, round((($spending - $prevMin) / ($nextLevel['min'] - $prevMin)) * 100));
                            $remaining = $nextLevel['min'] - $spending;
                        @endphp
                        <div class="w-full h-2 bg-white/60 rounded-full overflow-hidden mt-2">
                            <div class="h-full rounded-full {{ $level['color'] === 'text-orange-600' ? 'bg-orange-400' : ($level['color'] === 'text-slate-500' ? 'bg-slate-400' : ($level['color'] === 'text-amber-600' ? 'bg-amber-400' : 'bg-violet-400')) }} transition-all duration-700"
                                 style="width: {{ $progress }}%"></div>
                        </div>
                        <p class="text-[11px] {{ $level['color'] }} opacity-80 mt-2">
                            Rp {{ number_format($remaining, 0, ',', '.') }} lagi menuju <strong>{{ $nextLevel['name'] }}</strong>
                        </p>
                    @else
                        <div class="w-full h-2 bg-white/60 rounded-full overflow-hidden mt-2">
                            <div class="h-full rounded-full bg-cyan-400 w-full"></div>
                        </div>
                        <p class="text-[11px] text-cyan-600 font-bold mt-2">✦ Level Tertinggi Tercapai</p>
                    @endif
                </div>
            </div>

            {{-- Stats --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Statistik</h3>
                <div class="flex justify-between items-center py-3 border-b border-slate-50">
                    <span class="text-sm text-slate-500 flex items-center gap-2">
                        <i class="fas fa-shopping-bag text-amber-400 w-4 text-center"></i> Total Pesanan
                    </span>
                    <span class="font-black text-slate-900">{{ $user->orders_count }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-slate-50">
                    <span class="text-sm text-slate-500 flex items-center gap-2">
                        <i class="fas fa-coins text-amber-400 w-4 text-center"></i> Total Spending
                    </span>
                    <span class="font-black text-slate-900 text-sm">Rp {{ number_format($spending, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-sm text-slate-500 flex items-center gap-2">
                        <i class="fas fa-chart-line text-amber-400 w-4 text-center"></i> Rata-rata Order
                    </span>
                    <span class="font-black text-slate-900 text-sm">
                        Rp {{ $user->orders_count > 0 ? number_format($spending / $user->orders_count, 0, ',', '.') : '0' }}
                    </span>
                </div>
            </div>

            {{-- Level Roadmap --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-5">Level Roadmap</h3>
                @php
                    $roadmap = [
                        'Bronze'   => ['min' => 0,        'icon' => 'fa-shield', 'color' => 'text-orange-500', 'bg' => 'bg-orange-50',  'border' => 'border-orange-200'],
                        'Silver'   => ['min' => 500000,   'icon' => 'fa-medal',  'color' => 'text-slate-500',  'bg' => 'bg-slate-100',  'border' => 'border-slate-200'],
                        'Gold'     => ['min' => 2000000,  'icon' => 'fa-star',   'color' => 'text-amber-500',  'bg' => 'bg-amber-50',   'border' => 'border-amber-200'],
                        'Platinum' => ['min' => 5000000,  'icon' => 'fa-crown',  'color' => 'text-violet-500', 'bg' => 'bg-violet-50',  'border' => 'border-violet-200'],
                        'Diamond'  => ['min' => 10000000, 'icon' => 'fa-gem',    'color' => 'text-cyan-500',   'bg' => 'bg-cyan-50',    'border' => 'border-cyan-200'],
                    ];
                @endphp
                <div class="space-y-3">
                    @foreach ($roadmap as $name => $meta)
                        @php $isReached = $spending >= $meta['min']; @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 border
                                {{ $isReached ? $meta['bg'] . ' ' . $meta['border'] : 'bg-slate-50 border-slate-100' }}">
                                <i class="fas {{ $meta['icon'] }} text-xs {{ $isReached ? $meta['color'] : 'text-slate-300' }}"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold {{ $isReached ? $meta['color'] : 'text-slate-300' }}">{{ $name }}</p>
                                <p class="text-[10px] text-slate-400">min. Rp {{ number_format($meta['min'], 0, ',', '.') }}</p>
                            </div>
                            @if ($level['name'] === $name)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $meta['bg'] }} {{ $meta['color'] }} {{ $meta['border'] }}">Sekarang</span>
                            @elseif ($isReached)
                                <i class="fas fa-check-circle text-emerald-400 text-sm"></i>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Riwayat Order --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Riwayat Pesanan</h3>
                    <span class="text-xs font-mono text-slate-400">{{ $orders->total() }} total pesanan</span>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse ($orders as $order)
                        @php
                            $stClass = match($order->status) {
                                'Pending'    => 'bg-amber-50 text-amber-600 border-amber-200',
                                'Processing' => 'bg-blue-50 text-blue-600 border-blue-200',
                                'Shipped'    => 'bg-indigo-50 text-indigo-600 border-indigo-200',
                                'Completed'  => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                'Cancelled'  => 'bg-rose-50 text-rose-600 border-rose-200',
                                default      => 'bg-slate-50 text-slate-600 border-slate-200',
                            };
                            $stIcon = match($order->status) {
                                'Pending'    => 'fa-clock',
                                'Processing' => 'fa-box-open',
                                'Shipped'    => 'fa-truck',
                                'Completed'  => 'fa-che