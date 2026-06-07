@extends('admin.layout')

@section('title', 'Edit Cabang')

@section('content')
<div class="space-y-6 fade-in pb-10">
    <div class="pt-2 pb-4 border-b border-slate-200/50 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Edit Cabang</h1>
                <p class="text-sm text-slate-500 mt-1">Perbarui detail cabang.</p>
            </div>
            <a href="{{ route('admin.branches.index') }}" class="inline-flex items-center gap-2 bg-white text-slate-700 px-4 py-2 rounded-xl border">Back</a>
        </div>
    </div>

    <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm">
        @if($errors->any())
            <div class="mb-4 p-3 bg-rose-50 text-rose-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.branches.update', $branch->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700">Nama Cabang</label>
                <input type="text" name="name" value="{{ old('name', $branch->name) }}" class="w-full mt-1 p-3 border rounded-lg" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $branch->phone) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full mt-1 p-3 border rounded-lg">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700">Alamat</label>
                <textarea name="address" class="w-full mt-1 p-3 border rounded-lg">{{ old('address', $branch->address) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Kota</label>
                <input type="text" name="city" value="{{ old('city', $branch->city) }}" class="w-full mt-1 p-3 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Provinsi</label>
                <input type="text" name="province" value="{{ old('province', $branch->province) }}" class="w-full mt-1 p-3 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Kode Pos</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $branch->postal_code) }}" class="w-full mt-1 p-3 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $branch->email) }}" class="w-full mt-1 p-3 border rounded-lg">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700">Jam Buka (Opsional)</label>
                <textarea name="opening_hours" class="w-full mt-1 p-3 border rounded-lg">{{ old('opening_hours', $branch->opening_hours) }}</textarea>
            </div>

            <div class="md:col-span-2 flex items-center gap-3 mt-2">
                <label class="inline-flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="form-checkbox" {{ $branch->is_active ? 'checked' : '' }}>
                    <span class="ml-2">Active</span>
                </label>
                <button type="submit" class="ml-auto bg-amber-500 text-white px-5 py-2.5 rounded-xl font-semibold">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
