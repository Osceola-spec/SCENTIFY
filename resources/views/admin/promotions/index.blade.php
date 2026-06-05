@extends('admin.layout')

@section('title', 'Promotion Management')

@section('content')
<div class="space-y-6 fade-in pb-10">

    <div class="pt-2 pb-4 border-b border-slate-200/50 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Promotion Management</h1>
                <p class="text-sm text-slate-500 mt-1">Manage all product promotions and discounts.</p>
            </div>
            <a href="{{ route('admin.promotions.create') }}" class="inline-flex items-center gap-2 bg-amber-500 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-amber-600 transition-all shadow-lg shadow-amber-500/30 active:scale-95 shrink-0">
                <i class="fas fa-plus"></i> Create Promo
            </a>
        </div>
    </div>

    <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden">
        @if($promotions->isEmpty())
            <div class="flex flex-col items-center justify-center p-12 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 text-3xl mb-4">
                    <i class="fas fa-tag"></i>
                </div>
                <h5 class="text-lg font-bold text-slate-800 mb-1">No Promotions Yet</h5>
                <p class="text-sm text-slate-500">Click the "Create Promo" button to add a new promotion.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                            <th class="px-6 py-4 border-b border-slate-100">#</th>
                            <th class="px-6 py-4 border-b border-slate-100">Title</th>
                            <th class="px-6 py-4 border-b border-slate-100">Discount</th>
                            <th class="px-6 py-4 border-b border-slate-100">Start</th>
                            <th class="px-6 py-4 border-b border-slate-100">End</th>
                            <th class="px-6 py-4 border-b border-slate-100 text-center">Status</th>
                            <th class="px-6 py-4 border-b border-slate-100 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700 divide-y divide-slate-50">
                        @foreach($promotions as $index => $promo)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-400">{{ $index + 1 }}</td>

                                <td class="px-6 py-4 font-bold text-slate-900">{{ $promo->title }}</td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-amber-50 text-amber-600 text-[11px] font-bold border border-amber-100">
                                        {{ $promo->discount_type === 'percent' ? $promo->discount_value . '%' : 'Rp ' . number_format($promo->discount_value, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-slate-500 text-xs">{{ $promo->starts_at?->format('d M Y, H:i') ?? '-' }}</td>

                                <td class="px-6 py-4 text-slate-500 text-xs">{{ $promo->ends_at?->format('d M Y, H:i') ?? '-' }}</td>

                                <td class="px-6 py-4 text-center">
                                    @if($promo->is_active)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-bold border border-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[11px] font-bold border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span> Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.promotions.edit', $promo->id) }}"
                                           class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 flex items-center justify-center transition-all shadow-sm"
                                           title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form action="{{ route('admin.promotions.destroy', $promo->id) }}" method="POST" class="inline m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Delete this promotion?')"
                                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 flex items-center justify-center transition-all shadow-sm"
                                                    title="Delete">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div>{{ $promotions->links() }}</div>

</div>
@endsection
