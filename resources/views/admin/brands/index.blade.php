@extends('admin.layout')

@section('title', 'Brand Management & Showcase')

@section('content')
<div class="space-y-8 fade-in pb-12">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-200/50 pb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Brand Management</h1>
            <p class="text-sm text-slate-500 mt-1">Manage, add, and showcase all Scentify perfume brand partners.</p>
        </div>
        <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-xs font-mono font-semibold text-amber-600 dark:text-amber-400">
            <i class="fas fa-tag"></i> Total Brands: {{ $brands->count() }}
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Side: Tambah Brand Form (Col 4) -->
        <aside class="lg:col-span-4 lg:sticky lg:top-28">
            <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm p-6 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full pointer-events-none"></div>
                
                <h5 class="font-serif text-lg font-bold text-slate-800 border-b border-slate-100 pb-4 mb-6 relative z-10 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-amber-500 text-sm"></i> Add Brand
                </h5>

                <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 relative z-10">
                    @csrf

                    <!-- Brand Name -->
                    <div>
                        <label class="block text-xs font-mono uppercase tracking-wider text-slate-400 mb-2 font-bold">Brand Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 bg-slate-50 border @error('name') border-rose-500 @else border-slate-200 @enderror rounded-xl text-slate-700 placeholder-slate-400 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all" 
                               placeholder="Example: Chanel, Dior, HMNS">
                        @error('name')
                            <p class="text-rose-500 text-xs mt-2 pl-1 flex items-center gap-1.5"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Brand Logo -->
                    <div>
                        <label class="block text-xs font-mono uppercase tracking-wider text-slate-400 mb-2 font-bold">Brand Logo (Optional)</label>
                        <div class="relative">
                            <input type="file" name="logo_image" accept="image/*"
                                   class="w-full px-4 py-3 bg-slate-50 border @error('logo_image') border-rose-500 @else border-slate-200 @enderror rounded-xl text-slate-500 text-xs file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200 transition-all cursor-pointer">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2 flex items-center gap-1.5"><i class="fas fa-info-circle"></i> Format: JPG, JPEG, PNG. Max 2MB.</p>
                        @error('logo_image')
                            <p class="text-rose-500 text-xs mt-2 pl-1 flex items-center gap-1.5"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-slate-900 text-white font-semibold tracking-wide py-3.5 rounded-xl hover:bg-slate-800 active:scale-95 transition-all duration-300 text-sm shadow-lg shadow-amber-500/5 flex items-center justify-center gap-2">
                        <i class="fas fa-save text-xs text-amber-400"></i> Save Brand
                    </button>
                </form>
            </div>
        </aside>

        <!-- Right Side: Showcase Brand Table (Col 8) -->
        <main class="lg:col-span-8">
            <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h5 class="font-serif text-lg font-bold text-slate-800">All Brands Showcase</h5>
                    <p class="text-xs text-slate-400 mt-1">List of all Scentify cosmetic and perfume brand partners.</p>
                </div>

                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="px-6 py-4 border-b border-slate-100 w-24">Logo</th>
                                    <th class="px-6 py-4 border-b border-slate-100">Brand Name</th>
                                    <th class="px-6 py-4 border-b border-slate-100 text-center w-32">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-slate-700 divide-y divide-slate-50">
                                @forelse($brands as $brand)
                                    <tr class="hover:bg-slate-50/85 transition-colors group">
                                        <!-- Logo -->
                                        <td class="px-6 py-4">
                                            @if ($brand->logo_url)
                                                <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 overflow-hidden shadow-sm flex items-center justify-center p-1.5 shrink-0">
                                                    <img src="{{ asset('storage/' . $brand->logo_url) }}" 
                                                         alt="{{ $brand->name }}" 
                                                         class="max-w-full max-h-full object-contain transition-transform duration-500 group-hover:scale-110">
                                                </div>
                                            @else
                                                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-500 border border-slate-200 flex items-center justify-center text-lg font-bold uppercase shrink-0">
                                                    {{ strtoupper(substr($brand->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Brand Name -->
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-slate-900 group-hover:text-amber-600 transition-colors">{{ $brand->name }}</span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <!-- Custom Open Edit Modal Button -->
                                                <button type="button" onclick="openEditBrandModal('{{ $brand->id }}')"
                                                        class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 flex items-center justify-center transition-all shadow-sm"
                                                        title="Edit Brand">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </button>

                                                <!-- Delete Button Form -->
                                                <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="form-delete inline m-0 p-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" data-name="{{ $brand->name }}"
                                                            class="btn-delete w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 flex items-center justify-center transition-all shadow-sm"
                                                            title="Delete Brand">
                                                        <i class="fas fa-trash-alt text-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- =========================================================================
                                         MODAL EDIT BRAND (Tailwind Glassmorphic Rendered Inline per Brand)
                                         ========================================================================= -->
                                    <div id="editModal{{ $brand->id }}" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300">
                                        <div class="bg-white rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300 max-h-[90vh] flex flex-col border border-slate-100">
                                            
                                            <!-- Modal Header -->
                                            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                                                <h5 class="font-serif font-bold text-lg text-slate-900">Edit Partner Brand</h5>
                                                <button type="button" onclick="closeEditBrandModal('{{ $brand->id }}')" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-colors focus:outline-none">
                                                    <i class="fas fa-times text-xs"></i>
                                                </button>
                                            </div>

                                            <!-- Modal Form -->
                                            <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data" class="flex-grow overflow-y-auto">
                                                @csrf
                                                @method('PUT')
                                                <div class="p-6 sm:p-8 space-y-5">
                                                    
                                                    <!-- Name input -->
                                                    <div>
                                                        <label class="block text-xs font-mono uppercase tracking-wider text-slate-500 mb-2 font-bold">Brand Name <span class="text-rose-500">*</span></label>
                                                        <input type="text" name="name" value="{{ $brand->name }}" required
                                                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
                                                    </div>

                                                    <!-- Logo file input -->
                                                    <div>
                                                        <label class="block text-xs font-mono uppercase tracking-wider text-slate-500 mb-2 font-bold">Change Brand Logo (Optional)</label>
                                                        <div class="flex items-center gap-4">
                                                            @if($brand->logo_url)
                                                                <div class="w-12 h-12 rounded-xl border border-slate-200 p-1 bg-white shrink-0 flex items-center justify-center">
                                                                    <img src="{{ asset('storage/' . $brand->logo_url) }}" alt="Preview" class="max-w-full max-h-full object-contain">
                                                                </div>
                                                            @endif
                                                            <input type="file" name="logo_image" accept="image/*"
                                                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-500 text-xs file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200 transition-all cursor-pointer">
                                                        </div>
                                                        <small class="text-slate-400 d-block mt-2 flex items-center gap-1.5"><i class="fas fa-info-circle"></i> Leave empty if you don't want to change the current logo.</small>
                                                    </div>

                                                </div>

                                                <!-- Modal Footer -->
                                                <div class="px-6 py-5 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">
                                                    <button type="button" onclick="closeEditBrandModal('{{ $brand->id }}')" class="px-6 py-2.5 rounded-xl font-semibold text-xs tracking-wider uppercase border border-slate-200 text-slate-700 hover:bg-white transition-all focus:outline-none">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" class="px-6 py-2.5 rounded-xl font-semibold text-xs tracking-wider uppercase bg-slate-900 text-white hover:bg-slate-800 shadow-md active:scale-95 transition-all focus:outline-none">
                                                        Save Changes
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center text-slate-400">
                                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 text-2xl mx-auto mb-4">
                                                <i class="fas fa-tag"></i>
                                            </div>
                                            <h6 class="text-slate-800 font-bold mb-1">No Brands yet</h6>
                                            <p class="text-xs text-slate-500">Use the form on the left to add a new brand partner.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

    </div>
</div>

<!-- =========================================================================
     SCRIPTS & SWEETALERT INTEGRATIONS
     ========================================================================= -->

<script>
    // FUNGSI MODAL DI KANVAS (Dengan transisi scale & opacity Tailwind)
    function openEditBrandModal(id) {
        const modal = document.getElementById('editModal' + id);
        if(modal) {
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.querySelector('.bg-white').classList.remove('scale-95');
        }
    }

    function closeEditBrandModal(id) {
        const modal = document.getElementById('editModal' + id);
        if(modal) {
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.querySelector('.bg-white').classList.add('scale-95');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // 1. Handle Konfirmasi Hapus dengan SweetAlert2 (Gaya Scentify)
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('.form-delete');
                const brandName = this.getAttribute('data-name');

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Brand partner "${brandName}" and all its products will be moved to the trash!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0f172a', // Slate-900 (Tema gelap Scentify)
                    cancelButtonColor: '#ff2a5f', // Rose-500
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
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

        // 2. Handle Notifikasi Sukses dari Session Laravel
        @if (session('success'))
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#0f172a',
                customClass: {
                    popup: 'rounded-[1.5rem] shadow-2xl border border-slate-100',
                    confirmButton: 'rounded-xl px-5 py-2.5 font-bold'
                }
            });
        @endif
    });
</script>
@endsection