@extends('admin.layout')

@section('title', 'Detail Cabang')

@section('content')
<div class="space-y-6 fade-in pb-10">
    <div class="pt-2 pb-4 border-b border-slate-200/50 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $branch->name }}</h1>
                <p class="text-sm text-slate-500 mt-1">Detail informasi cabang.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.branches.index') }}" class="inline-flex items-center gap-2 bg-white text-slate-700 px-4 py-2 rounded-xl border">Kembali</a>
                <a href="{{ route('admin.branches.edit', $branch->id) }}" class="inline-flex items-center gap-2 bg-amber-500 text-white px-4 py-2 rounded-xl">Edit</a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                <div class="w-full h-48 bg-slate-50 rounded-lg overflow-hidden">
                    <img src="{{ $branch->image_url ? (strpos($branch->image_url, 'http') === 0 ? $branch->image_url : asset('product_image/' . $branch->image_url)) : 'https://placehold.co/400x300?text=Shop' }}" alt="{{ $branch->name }}" class="w-full h-full object-cover">
                </div>
            </div>
            <div class="md:col-span-2">
                <h3 class="text-lg font-bold text-slate-900">Alamat</h3>
                <p class="text-sm text-slate-600 mt-1">{{ $branch->address }} {{ $branch->city ? ', '.$branch->city : '' }} {{ $branch->province ? ', '.$branch->province : '' }}</p>

                <div class="mt-4">
                    <h4 class="font-semibold text-sm">Kontak</h4>
                    <p class="text-sm text-slate-600">Telepon: {{ $branch->phone ?? '-' }}</p>
                    <p class="text-sm text-slate-600">Email: {{ $branch->email ?? '-' }}</p>
                </div>

                @if($branch->opening_hours)
                    <div class="mt-4">
                        <h4 class="font-semibold text-sm">Jam Buka</h4>
                        <div class="text-sm text-slate-600 mt-1">{!! nl2br(e($branch->opening_hours)) !!}</div>
                    </div>
                @endif

                @if($branch->latitude && $branch->longitude)
                    <div class="mt-4">
                        <h4 class="font-semibold text-sm">Lokasi (Koordinat)</h4>
                        <p class="text-sm text-slate-600">{{ $branch->latitude }}, {{ $branch->longitude }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
