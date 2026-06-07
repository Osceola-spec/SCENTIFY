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
                <i class="fas fa-plus"></i> Add Branch
            </a>
        </div>
    </div>

    <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4">
            <div class="mb-4">
                <input id="admin-branch-search" type="search" placeholder="Search branches..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-4 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
            </div>

            @php
                $branchesForJson = $branches->map(fn($b) => [
                    'id' => $b->id,
                    'name' => $b->name,
                    'address' => $b->address,
                    'city' => $b->city,
                    'phone' => $b->phone,
                    'email' => $b->email,
                ]);
            @endphp
            @if($branches->isEmpty())
                <div class="p-8 text-center text-slate-500">Belum ada cabang.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse table-fixed">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                <th class="px-6 py-4 border-b border-slate-100 w-12">#</th>
                                <th class="px-6 py-4 border-b border-slate-100 w-43">Nama</th>
                                <th class="px-6 py-4 border-b border-slate-100 ">Alamat</th>
                                <th class="px-6 py-4 border-b border-slate-100 w-44">Kontak</th>
                                <th class="px-6 py-4 border-b border-slate-100 w-28">Status</th>
                                <th class="px-6 py-4 border-b border-slate-100 w-36 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-slate-700 divide-y divide-slate-50">
                            @foreach($branches as $i => $branch)
                                <tr class="group hover:bg-slate-50/80 transition-colors branch-row" data-id="{{ $branch->id }}">
                                    <td class="px-6 py-4">{{ $i + 1 }}</td>
                                    <td class="px-6 py-4 font-bold">
                                        <div class="max-w-[160px] md:max-w-[260px] truncate" title="{{ $branch->name }}">{{ $branch->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="max-w-[220px] md:max-w-[520px] truncate" title="{{ $branch->address }}{{ $branch->city ? ', '.$branch->city : '' }}">{{ $branch->address }} {{ $branch->city ? ', '.$branch->city : '' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="max-w-[180px] truncate">
                                            <span title="{{ $branch->phone ?? '-' }}">{{ $branch->phone ?? '-' }}</span>
                                            <br>
                                            <small class="text-slate-400 truncate block" title="{{ $branch->email ?? '' }}">{{ $branch->email ?? '' }}</small>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">@if($branch->is_active)<span class="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">Aktif</span>@else<span class="px-2 py-1 rounded-full bg-rose-50 text-rose-600 text-xs font-bold border border-rose-100">Nonaktif</span>@endif</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="inline-flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.branches.edit', $branch->id) }}" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 flex items-center justify-center transition-all shadow-sm" title="Edit Cabang">
                                                <i class="fas fa-pen text-xs"></i>
                                            </a>
                                            <form action="{{ route('admin.branches.destroy', $branch->id) }}" method="POST" class="form-delete inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" data-name="{{ $branch->name }}" class="btn-delete w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 flex items-center justify-center transition-all shadow-sm" title="Delete Branch">
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

                <script>
                    (function(){
                        const data = @json($branchesForJson);
                        const fuse = new Fuse(data, { keys: ['name','address','city','phone','email'], threshold: 0.35 });
                        const input = document.getElementById('admin-branch-search');
                        input.addEventListener('input', function(e){
                            const q = e.target.value.trim();
                            const matches = q ? fuse.search(q).map(r => r.item.id) : data.map(d => d.id);
                            document.querySelectorAll('.branch-row').forEach(r => {
                                const id = parseInt(r.getAttribute('data-id'));
                                r.style.display = matches.includes(id) ? '' : 'none';
                            });
                        });
                    })();
                </script>
            @endif
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.form-delete');
                const branchName = this.getAttribute('data-name');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Cabang "${branchName}" akan dipindahkan ke tempat sampah!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ff2a5f',
                    cancelButtonColor: '#0f172a',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    scrollbarPadding: false,
                    heightAuto: false,
                    customClass: {
                        popup: 'rounded-[1.5rem] shadow-2xl border border-slate-100',
                        confirmButton: 'rounded-xl px-5 py-2.5 font-bold',
                        cancelButton: 'rounded-xl px-5 py-2.5 font-bold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
