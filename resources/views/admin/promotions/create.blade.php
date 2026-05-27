@extends('admin.layout')

@section('content')
<div class="p-6 max-w-2xl">
    <h3 class="text-lg font-semibold mb-4">Buat Promo Baru</h3>
    @if($errors->any())
        <div class="mb-4 p-3 bg-rose-50 text-rose-700 rounded">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.promotions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="block text-sm">Judul</label>
            <input name="title" value="{{ old('title') }}" class="w-full border px-3 py-2 rounded" required>
        </div>
        <div class="mb-3">
            <label class="block text-sm">Deskripsi</label>
            <textarea name="description" class="w-full border px-3 py-2 rounded">{{ old('description') }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm">Tipe Diskon</label>
                <select name="discount_type" class="w-full border px-3 py-2 rounded">
                    <option value="percent">Persen (%)</option>
                    <option value="fixed">Nominal (Rp)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm">Nilai Diskon</label>
                <input name="discount_value" type="number" step="0.01" class="w-full border px-3 py-2 rounded" required>
            </div>
        </div>

        <div class="mt-3">
            <label class="inline-flex items-center">
                <input type="checkbox" name="applies_to_all" {{ old('applies_to_all', request('product_id') ? false : true) ? 'checked' : '' }}> Berlaku untuk semua produk
            </label>
        </div>

        <div class="mt-3">
            <label class="block text-sm">Pilih Produk (opsional)</label>
            <select name="product_id" class="w-full border px-3 py-2 rounded">
                <option value="">-- Semua Produk --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-3 mt-3">
            <div>
                <label class="block text-sm">Mulai (starts_at)</label>
                <input name="starts_at" type="datetime-local" value="{{ old('starts_at') ?? request('starts_at') }}" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block text-sm">Berakhir (ends_at)</label>
                <input name="ends_at" type="datetime-local" value="{{ old('ends_at') ?? request('ends_at') }}" class="w-full border px-3 py-2 rounded">
            </div>
        </div>

        <div class="mt-4">
            <label class="inline-flex items-center"><input type="checkbox" name="is_active" {{ old('is_active', '1') ? 'checked' : '' }}> Aktif</label>
        </div>

        <div class="mt-4">
            <button class="px-4 py-2 bg-amber-500 text-white rounded">Simpan Promo</button>
        </div>
    </form>
</div>
@endsection
