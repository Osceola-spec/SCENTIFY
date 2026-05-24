@extends('admin.layout')

@section('title', 'Manajemen Pelanggan')

@section('content')
<div class="space-y-6 fade-in pb-10">

    {{-- Header --}}
    <div class="sticky top-0 z-30 bg-adminbg/90 backdrop-blur-md pt-2 pb-4 border-b border-slate-200/50 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Pelanggan</h1>
                <p class="text-sm text-slate-500 mt-1">Daftar seluruh pelanggan Scentify beserta level loyalitas mereka.</p>
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-xs font-mono font-semibold text-amber-600">
                <i class="fas fa-users"></i> Customer Management
            </div>
        </div>
    </div>

    {{-- Level Summary Cards --}}
    @php
        $levelMeta = [
            'Diamond'  => ['icon' => 'fa-gem',    'color' => 'text-cyan-600',   'bg' => 'bg-cyan-50',   'border' => 'border-cyan-200'],
            'Platinum' => ['icon' => 'fa-crown',  'color' => 'text-violet-600', 'bg' => 'bg-violet-50', 'border' => 'border-violet-200'],
            'Gold'     => ['icon' => 'fa-star',   'color' => 'text-amber-600',  'bg' => 'bg-amber-50',  'border' => 'border-amber-200'],
            'Silver'   => ['icon' => 'fa-medal',  'color' => 'text-slate-500',  'bg' => 'bg-slate-100', 'border' => 'border-slate-200'],
            'Bronze'   => ['icon' => 'fa-shield', 'color' => 'text-orange-600', 'bg' => 'bg-orange-50', 'border' => 'border-orange-200'],
        ];
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
        @foreach ($levelMeta as $levelName => $meta)
            <a href="?level={{ $levelName }}"
               class="bg-white rounded-2xl border {{ $meta['border'] }} p-4 flex flex-col items-center gap-2 shadow-sm hover:shadow-md transition-all group {{ request('level') === $levelName ? 'ring-2 ring-offset-1 ring-amber-400' : '' }}">
                <div class="w-10 h-10 rounded-full {{ $meta['bg'] }} {{ $meta['border'] }} border flex items-center justify-center">
                    <i class="fas {{ $meta['icon'] }} {{ $meta['color'] }} text-sm"></i>
                </div>
                <p class="text-xs font-bold {{ $meta['color'] }}">{{ $levelName }}</p>
                <p class="text-xl font-black text-slate-900">
                    {{ isset($allSpending[$levelName]) ? $allSpending[$levelName]->count() : 0 }}
                </p>
                <p class="text-[10px] text-slate-400">pelanggan</p>
            </a>
        @endforeach
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-[1.5rem] p-5 border border-slate-100 shadow-sm">
        <form action="{{ route('admin.customers.index') }}" method="GET"
              class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
            <div class="md:col-span-6 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-amber-500 transition-colors">
                    <i class="fas fa-search text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama atau email pelanggan..."
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
            </div>
            <div class="md:col-span-3 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-layer-group text-xs"></i>
                </div>
                <select name="level" onchange="this.form.submit()"
                        class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-10 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all cursor-pointer">
                    <option value="">Semua Level</option>
                    @foreach (array_keys($levelMeta) as $lvl)
                        <option value="{{ $lvl }}" {{ request('level') === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400">
                    <i class="fas fa-chevron-down text-[10px]"></i>
                </div>
            </div>
            <div class="md:col-span-3 flex gap-3">
                <button type="submit"
                        class="flex-1 bg-slate-900 text-white font-semibold text-sm py-2.5 rounded-xl hover:bg-slate-800 active:scale-95 transition-all shadow-md">
                    Filter
                </button>
                <a href="{{ route('admin.customers.index') }}"
                   class="flex-1 bg-white border border-slate-200 text-slate-600 font-semibold text-sm py-2.5 rounded-xl hover:bg-slate-50 transition-colors text-center flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Tabel Pelanggan --}}
    <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                        <th class="px-6 py-4 border-b border-slate-100">Pelanggan</th>
                        <th class="px-6 py-4 border-b border-slate-100">Level</th>
                        <th class="px-6 py-4 border-b border-slate-100 text-center">Total Order</th>
                        <th class="px-6 py-4 border-b border-slate-100">Total Spending</th>
                        <th class="px-6 py-4 border-b border-slate-100">Bergabung</th>
                        <th class="px-6 py-4 border-b border-slate-100 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700 divide-y divide-slate-50">
                    @forelse ($customers as $customer)
                        @php
                            $spending = (int)($customer->total_spending ?? 0);
                            $level    = \App\Http\Controllers\AdminCustomerController::getLevel($spending);

                            // Progress ke level berikutnya
                            $levels   = ['Bronze' => 0, 'Silver' => 500000, 'Gold' => 2000000, 'Platinum' => 5000000, 'Diamond' => 10000000];
                            $nextMin  = null;
                            $nextName = null;
                            
                            foreach ($levels as $n => $m) {
                                if ($spending < $m) { 
                                    $nextMin = $m; 
                                    $nextName = $n; 
                                    break; 
                                }
                            }
                            
                            $prevMin  = $levels[$level['name']] ?? 0;
                            
                            // PERBAIKAN: Hitung pembagi/denominator secara aman dan pastikan tidak 0 (Division by Zero protection)
                            $denominator = $nextMin - $prevMin;
                            
                            if ($nextMin !== null && $denominator > 0) {
                                $progress = min(100, max(0, round((($spending - $prevMin) / $denominator) * 100)));
                            } else {
                                $progress = 100; // Sudah level maksimum atau kondisi anomali aman
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            {{-- Pelanggan --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-xs font-black text-white uppercase shrink-0 shadow-sm">
                                        {{ substr($customer->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $customer->name }}</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">{{ $customer->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Level Badge --}}
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider border {{ $level['bg'] }} {{ $level['color'] }} {{ $level['border'] }}">
                                    <i class="fas {{ $level['icon'] }}"></i> {{ $level['name'] }}
                                </span>
                            </td>

                            {{-- Total Order --}}
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-slate-900">{{ $customer->orders_count }}</span>
                                <span class="text-slate-400 text-xs ml-1">pesanan</span>
                            </td>

                            {{-- Total Spending --}}
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-bold text-slate-900">Rp {{ number_format($spending, 0, ',', '.') }}</p>
                                    {{-- Progress bar --}}
                                    @if ($nextName)
                                        <div class="mt-1.5 w-32">
                                            <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full transition-all duration-500
                                                    {{ $level['name'] === 'Bronze' ? 'bg-orange-400' : ($level['name'] === 'Silver' ? 'bg-slate-400' : ($level['name'] === 'Gold' ? 'bg-amber-400' : 'bg-violet-400')) }}"
                                                     style="width: {{ $progress }}%"></div>
                                            </div>
                                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $progress }}% menuju {{ $nextName }}</p>
                                        </div>
                                    @else
                                        <p class="text-[10px] text-cyan-500 font-semibold mt-0.5">Level Tertinggi ✦</p>
                                    @endif
                                </div>
                            </td>

                            {{-- Bergabung --}}
                            <td class="px-6 py-4 text-slate-500 text-xs">
                                {{ $customer->created_at->format('d M Y') }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.customers.show', $customer->id) }}"
                                   class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:text-amber-600 hover:bg-amber-50 hover:border-amber-200 transition-all shadow-sm font-semibold text-xs">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 text-2xl mx-auto mb-4 border border-slate-100">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h5 class="text-base font-bold text-slate-800 mb-1">Tidak Ada Pelanggan</h5>
                                <p class="text-sm text-slate-500">Coba ubah kata kunci pencarian atau filter level.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="flex justify-center custom-pagination mt-8">
        {{ $customers->links('pagination::bootstrap-5') }}
    </div>

</div>

<style>
    .custom-pagination .pagination { display: flex; gap: 0.25rem; margin: 0; padding: 0; list-style: none; }
    .custom-pagination .page-link { color: #475569; border: 1px solid #e2e8f0; background-color: #fff; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; font-weight: 500; transition: all 0.2s; display: flex; align-items: center; justify-content: center; }
    .custom-pagination .page-item.active .page-link { background-color: #0f172a !important; color: #fff !important; border-color: #0f172a !important; }
    .custom-pagination .page-link:hover { background-color: #f8fafc !important; color: #0f172a !important; border-color: #cbd5e1 !important; }
    .custom-pagination .page-item.disabled .page-link { color: #94a3b8; background-color: #f8fafc; border-color: #f1f5f9; cursor: not-allowed; }
</style>
@endsection