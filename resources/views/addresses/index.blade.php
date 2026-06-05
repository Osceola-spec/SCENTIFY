@extends('base.base')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-32 relative z-10">
    <div class="absolute top-[10%] left-[5%] w-[250px] h-[250px] bg-amber-500/10 dark:bg-amber-500/5 rounded-full animate-float pointer-events-none filter blur-[80px] -z-10"></div>
    <div class="absolute bottom-[20%] right-[5%] w-[300px] h-[300px] bg-purple-500/10 dark:bg-purple-900/5 rounded-full animate-float pointer-events-none filter blur-[80px] -z-10" style="animation-delay: 2s;"></div>

    <nav class="mb-8 reveal">
        <ol class="flex items-center space-x-2 text-xs font-mono uppercase tracking-wider text-slate-400 dark:text-zinc-500">
            <li><a href="{{ route('home') }}" class="hover:text-amber-500 transition-colors">Home</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('profile') }}" class="hover:text-amber-500 transition-colors">Profile</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-amber-500 font-semibold">My Addresses</li>
        </ol>
    </nav>

    <div class="mb-10 reveal">
        <span class="text-[10px] sm:text-xs font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block">Manage Shipping</span>
        <h1 class="text-3xl md:text-4xl font-serif mt-2 text-slate-950 dark:text-white">Addresses <span class="text-amber-500 font-normal">My</span></h1>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-xl text-sm reveal">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-500 rounded-xl text-sm reveal">
            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <!-- Address List -->
    <div class="space-y-4 mb-8 reveal">
        @forelse($addresses as $addr)
        <div class="glass-card bg-white/60 dark:bg-darkcard/60 rounded-2xl border {{ $addr->is_default ? 'border-amber-500/40' : 'border-slate-200 dark:border-white/5' }} p-5 sm:p-6 shadow-md relative">
                @if($addr->is_default)
                <span class="absolute top-4 right-4 text-[9px] font-mono uppercase tracking-widest bg-amber-500 text-black px-2.5 py-1 rounded-full font-bold">Primary</span>
            @endif
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-amber-500/10 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fas fa-map-marker-alt text-amber-500 text-sm"></i>
                </div>
                <div class="flex-grow min-w-0">
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        <span class="text-xs font-mono uppercase tracking-widest text-amber-600 dark:text-amber-400 font-bold">{{ $addr->label ?? 'Address' }}</span>
                    </div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $addr->first_name }} {{ $addr->last_name }}</p>
                    <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">{{ $addr->phone }}</p>
                    <p class="text-xs text-slate-600 dark:text-zinc-300 mt-1">{{ $addr->address }}, {{ $addr->city }} {{ $addr->postal_code }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100 dark:border-white/5 flex-wrap">
                @if(!$addr->is_default)
                <form action="{{ route('addresses.setDefault', $addr) }}" method="POST" class="m-0">
                    @csrf @method('PUT')
                    <button type="submit" class="text-[10px] font-mono uppercase tracking-widest text-slate-500 hover:text-amber-500 transition-colors border border-slate-200 dark:border-white/10 hover:border-amber-500 px-3 py-1.5 rounded-lg">
                        <i class="fas fa-star mr-1"></i> Set as Default
                    </button>
                </form>
                @endif
                    <button onclick="openEditModal({{ $addr->id }})" 
                        data-addr="{{ json_encode($addr) }}"
                        class="text-[10px] font-mono uppercase tracking-widest text-slate-500 hover:text-amber-500 transition-colors border border-slate-200 dark:border-white/10 hover:border-amber-500 px-3 py-1.5 rounded-lg">
                    <i class="fas fa-pen mr-1"></i> Edit
                </button>
                    <form action="{{ route('addresses.destroy', $addr) }}" method="POST" class="m-0" onsubmit="return confirm('Delete this address?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-[10px] font-mono uppercase tracking-widest text-rose-400 hover:text-rose-500 transition-colors border border-rose-200/50 dark:border-rose-500/20 hover:border-rose-400 px-3 py-1.5 rounded-lg">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="glass-card bg-white/60 dark:bg-darkcard/60 rounded-2xl border border-slate-200 dark:border-white/5 p-10 text-center shadow-md">
            <div class="w-14 h-14 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-map-marker-alt text-slate-300 dark:text-zinc-600 text-xl"></i>
            </div>
            <p class="text-sm text-slate-400 dark:text-zinc-500">No saved addresses.</p>
            <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Add a shipping address to speed up the checkout process.</p>
        </div>
        @endforelse
    </div>

    <!-- Tombol Tambah Alamat -->
    <div class="reveal">
            <button onclick="document.getElementById('addModal').classList.remove('hidden')"
                class="flex items-center gap-2 px-6 py-3 bg-slate-950 dark:bg-amber-500 text-white dark:text-black rounded-xl font-semibold text-xs tracking-widest uppercase hover:bg-amber-500 dark:hover:bg-amber-400 transition-all shadow-lg">
                <i class="fas fa-plus"></i> Add New Address
        </button>
    </div>
</div>

<!-- Modal Tambah Alamat -->
<div id="addModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm">
    <div class="flex h-full items-start justify-center p-4 pt-28 sm:pt-32 pb-8">
        <div class="bg-white dark:bg-darkcard rounded-3xl border border-slate-200 dark:border-white/10 shadow-2xl w-full max-w-lg flex flex-col overflow-hidden" style="max-height: calc(100dvh - 8rem);">
            <div class="flex items-center justify-between p-6 pb-4 border-b border-slate-100 dark:border-white/5 shrink-0">
                <h3 class="text-lg font-serif font-bold text-slate-900 dark:text-white">Add New Address</h3>
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-zinc-200 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6 pt-4 overflow-y-auto flex-1">
                <form action="{{ route('addresses.store') }}" method="POST" class="space-y-4">
                    @csrf
                    @include('addresses._form', ['address' => null, 'provinces' => $provinces])
                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="flex-1 py-3 bg-slate-950 dark:bg-amber-500 text-white dark:text-black rounded-xl font-semibold text-xs uppercase tracking-widest hover:bg-amber-500 dark:hover:bg-amber-400 transition-all">Save</button>
                        <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="flex-1 py-3 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-zinc-300 rounded-xl font-semibold text-xs uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-zinc-800 transition-all">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Alamat -->
<div id="editModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm">
    <div class="flex h-full items-start justify-center p-4 pt-28 sm:pt-32 pb-8">
        <div class="bg-white dark:bg-darkcard rounded-3xl border border-slate-200 dark:border-white/10 shadow-2xl w-full max-w-lg flex flex-col overflow-hidden" style="max-height: calc(100dvh - 8rem);">
            <div class="flex items-center justify-between p-6 pb-4 border-b border-slate-100 dark:border-white/5 shrink-0">
                <h3 class="text-lg font-serif font-bold text-slate-900 dark:text-white">Edit Address</h3>
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-zinc-200 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6 pt-4 overflow-y-auto flex-1">
                <form id="editForm" action="" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    @include('addresses._form', ['address' => null, 'provinces' => $provinces, 'prefix' => 'edit_'])
                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="flex-1 py-3 bg-slate-950 dark:bg-amber-500 text-white dark:text-black rounded-xl font-semibold text-xs uppercase tracking-widest hover:bg-amber-500 dark:hover:bg-amber-400 transition-all">Update</button>
                        <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="flex-1 py-3 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-zinc-300 rounded-xl font-semibold text-xs uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-zinc-800 transition-all">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const provinces = @json($provinces);

function openEditModal(id) {
    const btn = document.querySelector(`[onclick="openEditModal(${id})"]`);
    const addr = JSON.parse(btn.getAttribute('data-addr'));
    
    document.getElementById('editForm').action = `/profile/addresses/${id}`;
    
    const f = document.getElementById('editModal');
    f.querySelector('[name="edit_label"]').value = addr.label || '';
    f.querySelector('[name="edit_first_name"]').value = addr.first_name || '';
    f.querySelector('[name="edit_last_name"]').value = addr.last_name || '';
    f.querySelector('[name="edit_phone"]').value = addr.phone || '';
    f.querySelector('[name="edit_address"]').value = addr.address || '';
    f.querySelector('[name="edit_postal_code"]').value = addr.postal_code || '';
    f.querySelector('[name="edit_is_default"]').checked = addr.is_default == 1;

    const provSel = f.querySelector('[name="edit_province_id"]');
    provSel.value = addr.province_id || '';

    const citySel = f.querySelector('[name="edit_city_id"]');
    if (addr.province_id) {
        citySel.innerHTML = '<option>Loading...</option>';
        fetch(`/api/cities/${addr.province_id}`)
            .then(r => r.json())
            .then(cities => {
                citySel.innerHTML = '<option value="">-- Select City --</option>';
                cities.forEach(c => {
                    citySel.innerHTML += `<option value="${c.id}" ${c.id == addr.city_id ? 'selected' : ''}>${c.name}</option>`;
                });
                f.querySelector('[name="edit_city"]').value = addr.city || '';
            });
    } else {
        citySel.innerHTML = '<option value="">-- Pilih Kota --</option>';
    }

    document.getElementById('editModal').classList.remove('hidden');
}

// Province → City live fetch (add modal)
document.querySelector('[name="province_id"]')?.addEventListener('change', function() {
    loadCities(this.value, document.querySelector('[name="city_id"]'), document.querySelector('[name="city"]'));
});

document.querySelector('[name="edit_province_id"]')?.addEventListener('change', function() {
    loadCities(this.value, document.querySelector('[name="edit_city_id"]'), document.querySelector('[name="edit_city"]'));
});

function loadCities(provinceId, citySelect, cityHidden) {
    if (!provinceId) { citySelect.innerHTML = '<option value="">-- Select City --</option>'; return; }
    citySelect.innerHTML = '<option>Loading...</option>';
    fetch(`/api/cities/${provinceId}`)
        .then(r => r.json())
        .then(cities => {
            citySelect.innerHTML = '<option value="">-- Select City/District --</option>';
            cities.forEach(c => {
                citySelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
            });
            citySelect.addEventListener('change', function() {
                if (cityHidden) cityHidden.value = this.options[this.selectedIndex]?.text || '';
            });
        });
}

// Close modals on backdrop click
['addModal','editModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});
</script>
@endsection
