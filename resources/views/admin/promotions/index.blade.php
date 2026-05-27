@extends('admin.layout')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">Manajemen Promo</h3>
        <a href="{{ route('admin.promotions.create') }}" class="px-4 py-2 bg-amber-500 text-white rounded">Buat Promo</a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="text-left">
                <th>Judul</th>
                <th>Diskon</th>
                <th>Periode</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($promotions as $promo)
                <tr class="border-t">
                    <td class="py-3">{{ $promo->title }}</td>
                    <td>{{ $promo->discount_type === 'percent' ? $promo->discount_value . '%' : 'Rp ' . number_format($promo->discount_value,0,',','.') }}</td>
                    <td>{{ $promo->starts_at?->format('d M Y H:i') }} - {{ $promo->ends_at?->format('d M Y H:i') }}</td>
                    <td>{{ $promo->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.promotions.edit', $promo->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded">Edit</a>
                        <form action="{{ route('admin.promotions.destroy', $promo->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1 bg-rose-500 text-white rounded">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $promotions->links() }}
    </div>
</div>
@endsection
