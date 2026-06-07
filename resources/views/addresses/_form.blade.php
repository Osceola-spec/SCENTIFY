@php $p = $prefix ?? ''; @endphp

<div>
    <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-1.5 font-bold">Label (Rumah, Kantor, dll)</label>
    <input type="text" name="{{ $p }}label" value="{{ old($p.'label', $address->label ?? '') }}" placeholder="cth: Rumah"
           class="w-full px-4 py-3 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-sm text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-1.5 font-bold">Nama Depan <span class="text-rose-500">*</span></label>
        <input type="text" name="{{ $p }}first_name" value="{{ old($p.'first_name', $address->first_name ?? '') }}" required
               class="w-full px-4 py-3 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-sm text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
    </div>
    <div>
        <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-1.5 font-bold">Nama Belakang</label>
        <input type="text" name="{{ $p }}last_name" value="{{ old($p.'last_name', $address->last_name ?? '') }}"
               class="w-full px-4 py-3 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-sm text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
    </div>
</div>

<div>
    <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-1.5 font-bold">Nomor WhatsApp <span class="text-rose-500">*</span></label>
    <input type="text" name="{{ $p }}phone" value="{{ old($p.'phone', $address->phone ?? '') }}" required placeholder="08123456789"
           inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
           class="w-full px-4 py-3 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-sm text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
</div>

<div>
    <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-1.5 font-bold">Alamat Lengkap <span class="text-rose-500">*</span></label>
    <textarea name="{{ $p }}address" rows="2" required placeholder="Jalan, No. Rumah, RT/RW"
              class="w-full px-4 py-3 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-sm text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all resize-none">{{ old($p.'address', $address->address ?? '') }}</textarea>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-1.5 font-bold">Provinsi <span class="text-rose-500">*</span></label>
        <select name="{{ $p }}province_id" required
                class="w-full px-4 py-3 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-sm text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
            <option value="">-- Pilih Provinsi --</option>
            @foreach($provinces as $prov)
                <option value="{{ $prov->id }}" {{ old($p.'province_id', $address->province_id ?? '') == $prov->id ? 'selected' : '' }}>{{ $prov->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-1.5 font-bold">Kota <span class="text-rose-500">*</span></label>
        <select name="{{ $p }}city_id" required
                class="w-full px-4 py-3 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-sm text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
            <option value="">-- Pilih Kota --</option>
        </select>
        <input type="hidden" name="{{ $p }}city" value="{{ old($p.'city', $address->city ?? '') }}">
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-1.5 font-bold">Kecamatan <span class="text-rose-500">*</span></label>
        <input type="text" name="{{ $p }}subdistrict" value="{{ old($p.'subdistrict', $address->subdistrict ?? '') }}" required placeholder="Kecamatan"
               class="w-full px-4 py-3 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-sm text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
    </div>
    <div>
        <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-1.5 font-bold">Kelurahan <span class="text-rose-500">*</span></label>
        <input type="text" name="{{ $p }}village" value="{{ old($p.'village', $address->village ?? '') }}" required placeholder="Kelurahan / Desa"
               class="w-full px-4 py-3 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-sm text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
    </div>
</div>

<div>
    <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-1.5 font-bold">Kode Pos <span class="text-rose-500">*</span></label>
    <input type="text" name="{{ $p }}postal_code" value="{{ old($p.'postal_code', $address->postal_code ?? '') }}" required placeholder="12345"
           inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
           class="w-full px-4 py-3 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-sm text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
</div>

<label class="flex items-center gap-3 cursor-pointer">
    <input type="checkbox" name="{{ $p }}is_default" value="1" {{ old($p.'is_default', $address->is_default ?? false) ? 'checked' : '' }}
           class="w-4 h-4 accent-amber-500 rounded">
    <span class="text-xs text-slate-600 dark:text-zinc-400 font-medium">Jadikan alamat utama</span>
</label>
