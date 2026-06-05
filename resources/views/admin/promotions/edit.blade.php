@extends('admin.layout')

@section('content')
<div class="p-6 max-w-2xl">
    <h3 class="text-lg font-semibold mb-4">Edit Promo</h3>

    @if($errors->any())
        <div class="mb-4 p-3 bg-rose-50 text-rose-700 rounded">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="block text-sm">Title</label>
            <input name="title" value="{{ old('title', $promotion->title) }}" class="w-full border px-3 py-2 rounded" required>
        </div>
        <div class="mb-3">
            <label class="block text-sm">Description</label>
            <textarea name="description" class="w-full border px-3 py-2 rounded">{{ old('description', $promotion->description) }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm">Discount Type</label>
                <select name="discount_type" class="w-full border px-3 py-2 rounded">
                    <option value="percent" {{ $promotion->discount_type === 'percent' ? 'selected' : '' }}>Percent (%)</option>
                    <option value="fixed" {{ $promotion->discount_type === 'fixed' ? 'selected' : '' }}>Fixed Amount (Rp)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm">Discount Value</label>
                <input name="discount_value" type="number" step="0.01" value="{{ $promotion->discount_value }}" class="w-full border px-3 py-2 rounded" required>
            </div>
        </div>

        <div class="mt-3">
            <label class="inline-flex items-center"><input type="checkbox" name="applies_to_all" {{ $promotion->applies_to_all ? 'checked' : '' }}> Applies to all products</label>
        </div>

        <div class="mt-3">
            <label class="block text-sm">Select Product (optional)</label>
            <select name="product_id" class="w-full border px-3 py-2 rounded">
                <option value="">-- All Products --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ $promotion->product_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-3 mt-3">
            <div>
                <label class="block text-sm">Start (starts_at)</label>
                <input name="starts_at" type="datetime-local" value="{{ old('starts_at', optional($promotion->starts_at)->format('Y-m-d\TH:i')) }}" class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block text-sm">End (ends_at)</label>
                <input name="ends_at" type="datetime-local" value="{{ old('ends_at', optional($promotion->ends_at)->format('Y-m-d\TH:i')) }}" class="w-full border px-3 py-2 rounded">
            </div>
        </div>

        <div class="mt-4">
            <label class="inline-flex items-center"><input type="checkbox" name="is_active" {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}> Active</label>
        </div>

        <div class="mt-4">
            <button class="px-4 py-2 bg-amber-500 text-white rounded">Save Changes</button>
        </div>
    </form>
</div>
@endsection
