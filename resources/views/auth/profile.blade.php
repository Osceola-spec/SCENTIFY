@extends('base.base')

@section('content')
    <div class="min-h-screen pt-32 pb-16 px-4 sm:px-6 relative overflow-hidden transition-colors duration-500">

        <div
            class="absolute top-0 right-[10%] w-[300px] h-[300px] sm:w-[400px] sm:h-[400px] bg-amber-500/10 dark:bg-amber-500/5 rounded-full animate-float pointer-events-none filter blur-[80px]">
        </div>
        <div class="absolute bottom-10 left-[10%] w-[300px] h-[300px] sm:w-[400px] sm:h-[400px] bg-purple-500/10 dark:bg-purple-900/5 rounded-full animate-float pointer-events-none filter blur-[80px]"
            style="animation-delay: 2s;"></div>

        <div class="max-w-4xl mx-auto relative z-10 reveal">

            <nav class="mb-8">
                <ol
                    class="flex items-center space-x-2 text-xs font-mono uppercase tracking-wider text-slate-400 dark:text-zinc-500">
                    <li><a href="{{ route('home') }}" class="hover:text-amber-500 transition-colors">Home</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-amber-500 font-semibold">My Profile</li>
                </ol>
            </nav>

            @if ($errors->any())
                <div
                    class="mb-6 p-4 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 rounded-2xl flex items-start gap-3 text-rose-600 dark:text-rose-400 text-sm">
                    <i class="fas fa-exclamation-circle mt-1"></i>
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div
                    class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 rounded-2xl flex items-center gap-3 text-emerald-600 dark:text-emerald-400 text-sm font-medium shadow-sm">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div
                class="glass-card rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-2xl overflow-hidden bg-white/70 dark:bg-darkcard/70">
                <div
                    class="h-32 sm:h-40 bg-gradient-to-r from-slate-900 to-slate-800 dark:from-zinc-900 dark:to-black relative">
                    <div
                        class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff0a_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0a_1px,transparent_1px)] bg-[size:20px_20px] opacity-20">
                    </div>
                </div>

                <div class="px-6 sm:px-12 pb-12 relative">
                    <div
                        class="flex flex-col md:flex-row items-center md:items-end gap-6 sm:gap-8 -mt-16 sm:-mt-20 mb-8 sm:mb-12">

                        <div class="relative group shrink-0">
                            <div
                                class="w-32 h-32 sm:w-40 sm:h-40 rounded-full border-4 border-white dark:border-darkcard shadow-xl overflow-hidden bg-slate-100 dark:bg-zinc-800 flex items-center justify-center relative z-10">
                                @if ($user->profile_picture)
                                    <img src="{{ asset('images/' . $user->profile_picture) }}" alt="{{ $user->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-user text-5xl text-slate-300 dark:text-zinc-600"></i>
                                @endif
                            </div>
                            <button onclick="openEditModal()"
                                class="absolute inset-0 z-20 flex items-center justify-center bg-black/50 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <i class="fas fa-camera text-xl"></i>
                            </button>
                        </div>

                        <div class="text-center md:text-left flex-grow min-w-0">
                            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4 mb-2">
                                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-950 dark:text-white truncate max-w-full"
                                    title="{{ $user->username }}">
                                    {{ $user->username }}
                                </h2>

                                <div class="shrink-0">
                                    @if ($user->role === 'admin')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-100 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 text-xs font-bold uppercase tracking-wider border border-rose-200 dark:border-rose-500/20">
                                            <i class="fas fa-shield-alt text-[10px]"></i> {{ $user->role }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $level['bg'] }} {{ $level['color'] }} {{ $level['border'] }} text-xs font-bold uppercase tracking-wider border dark:bg-opacity-10">
                                            <i class="fas {{ $level['icon'] }} text-[10px]"></i> {{ $level['name'] }}
                                            Member
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-sm font-mono text-slate-500 dark:text-zinc-400 truncate">{{ $user->email }}</p>
                        </div>

                        <div class="flex items-center gap-3 w-full md:w-auto mt-4 md:mt-0 shrink-0">
                            <button onclick="openEditModal()"
                                class="flex-1 md:flex-none px-6 py-2.5 rounded-xl font-semibold text-xs tracking-wider uppercase border border-slate-200 dark:border-white/10 text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-800 transition-all shadow-sm">
                                Edit Profil
                            </button>
                            <form action="{{ route('logout') }}" method="POST" class="flex-1 md:flex-none">
                                @csrf
                                <button type="submit"
                                    class="w-full px-6 py-2.5 rounded-xl font-semibold text-xs tracking-wider uppercase bg-rose-500 hover:bg-rose-600 text-white transition-all shadow-md shadow-rose-500/20">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>

                    <hr class="border-slate-200 dark:border-white/5 mb-8">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <h5 class="text-xs font-mono uppercase tracking-widest text-slate-400 mb-1">Nomor Telepon
                                </h5>
                                <p class="text-base font-medium text-slate-900 dark:text-white">
                                    @if ($user->phone)
                                        {{ $user->phone }}
                                    @else
                                        <span class="text-slate-400 italic font-normal text-sm">Belum ditambahkan</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <h5 class="text-xs font-mono uppercase tracking-widest text-slate-400 mb-1">Bergabung Sejak
                                </h5>
                                <p class="text-base font-medium text-slate-900 dark:text-white flex items-center gap-2">
                                    <i class="far fa-calendar-alt text-amber-500"></i>
                                    {{ $user->created_at ? $user->created_at->format('d F Y') : '-' }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <h5 class="text-xs font-mono uppercase tracking-widest text-slate-400 mb-3">Tentang Saya (Bio)
                            </h5>
                            <div
                                class="p-4 rounded-2xl bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-white/5 h-[120px] overflow-y-auto">
                                @if ($user->bio)
                                    <p class="text-sm text-slate-700 dark:text-zinc-300 leading-relaxed">
                                        {{ $user->bio }}</p>
                                @else
                                    <p class="text-sm text-slate-400 italic">Tuliskan sesuatu tentang preferensi aroma atau
                                        diri Anda di sini...</p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="editProfileModal"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300">
        <div
            class="modal-body-card bg-white dark:bg-darkcard border border-slate-200 dark:border-white/5 rounded-3xl w-full max-w-xl overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300 max-h-[90vh] flex flex-col">

            <div
                class="px-6 py-5 border-b border-slate-200 dark:border-white/5 flex items-center justify-between bg-slate-50 dark:bg-zinc-900/50">
                <h5 class="font-serif font-bold text-lg text-slate-900 dark:text-white">Edit Profil Anda</h5>
                <button type="button" onclick="closeEditModal()"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-500/10 transition-colors focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                class="flex-grow overflow-y-auto custom-scrollbar">
                @csrf
                @method('PUT')

                <div class="p-6 sm:p-8 space-y-5">

                    <div>
                        <label for="username"
                            class="block text-xs font-mono uppercase tracking-wider text-slate-500 dark:text-zinc-400 mb-2">Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}"
                            required maxlength="20"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-zinc-800/50 border @error('username') border-rose-500 ring-1 ring-rose-500 @else border-slate-200 dark:border-white/10 @enderror rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
                        <p class="text-[10px] text-slate-400 mt-1">Maksimal 20 karakter.</p>
                        @error('username')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name"
                                class="block text-xs font-mono uppercase tracking-wider text-slate-500 dark:text-zinc-400 mb-2">Nama
                                Depan</label>
                            <input type="text" id="first_name" name="first_name"
                                value="{{ old('first_name', $user->first_name) }}" required
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-zinc-800/50 border @error('first_name') border-rose-500 ring-1 ring-rose-500 @else border-slate-200 dark:border-white/10 @enderror rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
                            @error('first_name')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name"
                                class="block text-xs font-mono uppercase tracking-wider text-slate-500 dark:text-zinc-400 mb-2">Nama
                                Belakang</label>
                            <input type="text" id="last_name" name="last_name"
                                value="{{ old('last_name', $user->last_name) }}"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-zinc-800/50 border @error('last_name') border-rose-500 ring-1 ring-rose-500 @else border-slate-200 dark:border-white/10 @enderror rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
                            @error('last_name')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="phone"
                            class="block text-xs font-mono uppercase tracking-wider text-slate-500 dark:text-zinc-400 mb-2">Nomor
                            Telepon</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-zinc-800/50 border @error('phone') border-rose-500 ring-1 ring-rose-500 @else border-slate-200 dark:border-white/10 @enderror rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all">
                        @error('phone')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bio"
                            class="block text-xs font-mono uppercase tracking-wider text-slate-500 dark:text-zinc-400 mb-2">Bio
                            / Tentang Saya</label>
                        <textarea id="bio" name="bio" rows="3" placeholder="Ceritakan sedikit tentang diri Anda..."
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-zinc-800/50 border @error('bio') border-rose-500 ring-1 ring-rose-500 @else border-slate-200 dark:border-white/10 @enderror rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all resize-none">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label
                            class="block text-xs font-mono uppercase tracking-wider text-slate-500 dark:text-zinc-400 mb-2">Foto
                            Profil Baru</label>
                        <div class="relative">
                            <input type="file" id="profile_picture" name="profile_picture" accept="image/*"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-zinc-800/50 border @error('profile_picture') border-rose-500 @else border-slate-200 dark:border-white/10 @enderror rounded-xl text-slate-500 dark:text-zinc-400 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-all cursor-pointer">
                        </div>
                        @error('profile_picture')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @else
                            <p class="text-[10px] text-slate-400 mt-2 flex items-center gap-1.5"><i
                                    class="fas fa-info-circle"></i> Maks 2MB. Format: JPEG, PNG, JPG.</p>
                        @enderror
                    </div>

                </div>

                <div
                    class="px-6 py-5 border-t border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-zinc-900/50 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()"
                        class="px-6 py-2.5 rounded-xl font-semibold text-xs tracking-wider uppercase border border-slate-200 dark:border-white/10 text-slate-700 dark:text-zinc-300 hover:bg-white dark:hover:bg-zinc-800 transition-all focus:outline-none">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 rounded-xl font-semibold text-xs tracking-wider uppercase bg-slate-900 dark:bg-amber-400 text-white dark:text-black hover:bg-amber-500 dark:hover:bg-amber-300 shadow-md active:scale-95 transition-all focus:outline-none">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openEditModal() {
            const modal = document.getElementById('editProfileModal');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.querySelector('.modal-body-card').classList.remove('scale-95');
        }

        function closeEditModal() {
            const modal = document.getElementById('editProfileModal');
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.querySelector('.modal-body-card').classList.add('scale-95');
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('editProfileModal');
            if (e.target === modal) {
                closeEditModal();
            }
        });
    </script>
@endsection
