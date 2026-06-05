@extends('admin.layout')

@section('title', 'Order History')

@section('content')
<div class="space-y-6 fade-in pb-10">

    <div class="pt-2 pb-4 border-b border-slate-200/50 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Order History</h1>
                <p class="text-sm text-slate-500 mt-1">Manage transactions, update shipping status, and track all Scentify incoming orders.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[1.5rem] p-5 border border-slate-100 shadow-sm relative overflow-hidden">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center relative z-10">
            
            <div class="md:col-span-5 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-amber-500 transition-colors">
                    <i class="fas fa-search"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all" 
                       placeholder="Search Order No. or Customer Name...">
            </div>

            <div class="md:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-filter text-xs"></i>
                </div>
                <select name="status" onchange="this.form.submit()" 
                        class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-10 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all cursor-pointer font-medium">
                    <option value="">All Order Statuses</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending (Waiting for Payment)</option>
                    <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                    <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-chevron-down text-[10px]"></i>
                </div>
            </div>

            <div class="md:col-span-3 flex gap-3">
                <button type="submit" class="flex-1 bg-slate-900 text-white font-semibold text-sm py-2.5 rounded-xl hover:bg-slate-800 active:scale-95 transition-all shadow-md">
                    Filter
                </button>
                <a href="{{ route('admin.orders.index') }}" class="flex-1 bg-white border border-slate-200 text-slate-600 font-semibold text-sm py-2.5 rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-colors text-center flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                            <th class="px-6 py-4 border-b border-slate-100">Order No.</th>
                            <th class="px-6 py-4 border-b border-slate-100">Customer</th>
                            <th class="px-6 py-4 border-b border-slate-100">Order Date</th>
                            <th class="px-6 py-4 border-b border-slate-100">Total Price</th>
                            <th class="px-6 py-4 border-b border-slate-100 text-center">Status</th>
                            <th class="px-6 py-4 border-b border-slate-100 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700 divide-y divide-slate-50">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4">
                                    <span class="font-mono font-bold text-slate-900 group-hover:text-amber-600 transition-colors">#{{ $order->order_number }}</span>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold uppercase shrink-0">
                                            {{ substr($order->user->username ?? 'P', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $order->user->username ?? 'Scentify Customer' }}</p>
                                            <p class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $order->phone_number }}</p>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 text-slate-500">
                                        <i class="far fa-calendar-alt text-amber-500/70"></i>
                                        <span>{{ $order->created_at->format('d M Y, H:i') }} <span class="text-[10px] uppercase ml-0.5">WIB</span></span>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusClass = match($order->status) {
                                            'Pending' => 'text-amber-600 bg-amber-50 border-amber-100',
                                            'Processing' => 'text-blue-600 bg-blue-50 border-blue-100',
                                            'Shipped' => 'text-indigo-600 bg-indigo-50 border-indigo-100',
                                            'Completed' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
                                            'Cancelled' => 'text-rose-600 bg-rose-50 border-rose-100',
                                            default => 'text-slate-600 bg-slate-50 border-slate-100'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold border {{ $statusClass }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                       class="inline-flex items-center justify-center gap-1.5 px-4 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 transition-all shadow-sm font-semibold text-xs">
                                        <i class="fas fa-eye text-xs"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 text-3xl mx-auto mb-4 border border-slate-100">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                    <h5 class="text-lg font-bold text-slate-800 mb-1">No Transactions Yet</h5>
                                    <p class="text-sm text-slate-500 max-w-sm mx-auto">No order history found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-center custom-pagination">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>

</div>

<style>
    .custom-pagination .pagination { display: flex; gap: 0.25rem; margin: 0; padding: 0; list-style: none; }
    .custom-pagination .page-link { color: #475569; border: 1px solid #e2e8f0; background-color: #ffffff; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; font-weight: 500; transition: all 0.2s; display: flex; align-items: center; justify-content: center; }
    .custom-pagination .page-item.active .page-link { background-color: #0f172a !important; color: #ffffff !important; border-color: #0f172a !important; }
    .custom-pagination .page-link:hover { background-color: #f8fafc !important; color: #0f172a !important; border-color: #cbd5e1 !important; }
    .custom-pagination .page-item.disabled .page-link { color: #94a3b8; background-color: #f8fafc; border-color: #f1f5f9; cursor: not-allowed; }
</style>
@endsection