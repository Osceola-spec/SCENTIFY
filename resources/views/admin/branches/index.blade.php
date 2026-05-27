@extends('admin.layout')

@section('title', 'Manajemen Cabang')

@section('content')
<div class="space-y-6 fade-in pb-10">

    <div class="pt-2 pb-4 border-b border-slate-200/50 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Cabang Toko</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola lokasi cabang Scentify yang tersedia untuk pelanggan.</p>
            </div>
            <a href="{{ route('admin.branches.create') }}" class="inline-flex items-center gap-2 bg-amber-500 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-amber-600 transition-all shadow-lg shadow-amber-500/30 active:scale-95 shrink-0">
                <i class="fas fa-plus"></i> Tambah Cabang
            </a>
        </div>
    </div>

    <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4">
            @if(session('success'))
                <div class="mb-4 p-3 bg-emerald-50 text-emerald-700 rounded">{{ session('success') }}</div>
            @endif

            @if($branches->isEmpty())
                <div class="p-8 text-center text-slate-500">Belum ada cabang.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                <th class="px-6 py-4 border-b border-slate-100">#</th>
                                <th class="px-6 py-4 border-b border-slate-100">Nama</th>
                                <th class="px-6 py-4 border-b border-slate-100">Alamat</th>
                                <th class="px-6 py-4 border-b border-slate-100">Kontak</th>
                                <th class="px-6 py-4 border-b border-slate-100">Status</th>
                                <th class="px-6 py-4 border-b border-slate-100 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-slate-700 divide-y divide-slate-50">
                            @foreach($branches as $i => $branch)
                                <tr class="group hover:bg-slate-50/80 transition-colors">
                                    <td class="px-6 py-4">{{ $i + 1 }}</td>
                                    <td class="px-6 py-4 font-bold">{{ $branch->name }}</td>
                                    <td class="px-6 py-4">{{ $branch->address }} {{ $branch->city ? ', '.$branch->city : '' }}</td>
                                    <td class="px-6 py-4">{{ $branch->phone ?? '-' }}<br><small class="text-slate-400">{{ $branch->email ?? '' }}</small></td>
                                    <td class="px-6 py-4">@if($branch->is_active)<span class="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">Aktif</span>@else<span class="px-2 py-1 rounded-full bg-rose-50 text-rose-600 text-xs font-bold border border-rose-100">Nonaktif</span>@endif</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="inline-flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.branches.edit', $branch->id) }}" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 flex items-center justify-center transition-all shadow-sm" title="Edit Cabang">
                                                <i class="fas fa-pen text-xs"></i>
                                            </a>
                                            <form action="{{ route('admin.branches.destroy', $branch->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Hapus cabang ini?')" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 flex items-center justify-center transition-all shadow-sm" title="Hapus Cabang">
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
    </div>

</div>
@endsection
