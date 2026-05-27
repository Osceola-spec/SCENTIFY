@extends('admin.layout')

@section('title', 'Tambah Cabang')

@section('content')
<div class="space-y-6 fade-in pb-10">
    <div class="pt-2 pb-4 border-b border-slate-200/50 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Tambah Cabang</h1>
                <p class="text-sm text-slate-500 mt-1">Tambahkan lokasi toko baru yang akan ditampilkan ke pelanggan.</p>
            </div>
            <a href="{{ route('admin.branches.index') }}" class="inline-flex items-center gap-2 bg-white text-slate-700 px-4 py-2 rounded-xl border">Kembali</a>
        </div>
    </div>

    <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm">
        <form action="{{ route('admin.branches.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700">Nama Cabang</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full mt-1 p-3 border rounded-lg" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full mt-1 p-3 border rounded-lg">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700">Alamat</label>
                <textarea name="address" class="w-full mt-1 p-3 border rounded-lg">{{ old('address') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Kota</label>
                <input type="text" name="city" value="{{ old('city') }}" class="w-full mt-1 p-3 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Provinsi</label>
                <input type="text" name="province" value="{{ old('province') }}" class="w-full mt-1 p-3 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Kode Pos</label>
                <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="w-full mt-1 p-3 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full mt-1 p-3 border rounded-lg">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700">Jam Buka (Opsional)</label>
                <textarea name="opening_hours" class="w-full mt-1 p-3 border rounded-lg">{{ old('opening_hours') }}</textarea>
            </div>

            <div class="md:col-span-2 flex items-center gap-3 mt-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" class="form-checkbox" checked>
                    <span class="ml-2">Aktif</span>
                </label>
                <button type="submit" class="ml-auto bg-amber-500 text-white px-5 py-2.5 rounded-xl font-semibold">Simpan Cabang</button>
            </div>
        </form>
    </div>
</div>
@endsection
