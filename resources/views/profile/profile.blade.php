<!-- resources/views/profile/index.blade.php -->
@extends('base.base')

@section('content')
<div class="container py-5 mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-dark text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Akun Saya</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Sidebar Navigasi Akun -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body text-center p-4">
                    <!-- Avatar (Menggunakan Inisial Nama) -->
                    @php
                        $name = auth()->user()->name ?? 'User Name';
                        $initials = collect(explode(' ', $name))->map(fn($n) => substr($n, 0, 1))->take(2)->implode('');
                    @endphp
                    <div class="bg-dark text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2rem; font-weight: 300;">
                        {{ strtoupper($initials) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ auth()->user()->name ?? 'Nama Pengguna' }}</h5>
                    <p class="text-muted small mb-0">{{ auth()->user()->email ?? 'email@example.com' }}</p>
                    
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <span class="badge bg-warning text-dark mt-2 rounded-pill px-3">Administrator</span>
                    @endif
                </div>
                
                <div class="list-group list-group-flush border-top">
                    <a href="#" class="list-group-item list-group-item-action active bg-dark border-dark text-white py-3 px-4 nav-profile-link" data-tab="profile">
                        <i class="far fa-user me-3"></i>Profil Saya
                    </a>
                    <a href="#" class="list-group-item list-group-item-action py-3 px-4 nav-profile-link" data-tab="orders">
                        <i class="fas fa-shopping-bag me-3 text-muted"></i>Riwayat Pesanan
                    </a>
                    <a href="#" class="list-group-item list-group-item-action py-3 px-4 nav-profile-link" data-tab="wishlist">
                        <i class="far fa-heart me-3 text-muted"></i>Wishlist Tersimpan
                    </a>
                    <a href="#" class="list-group-item list-group-item-action py-3 px-4 nav-profile-link" data-tab="addresses">
                        <i class="fas fa-map-marker-alt me-3 text-muted"></i>Alamat Pengiriman
                    </a>
                    
                    <!-- Form Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action py-3 px-4 text-danger w-100 text-start border-bottom-0 rounded-bottom-4">
                            <i class="fas fa-sign-out-alt me-3"></i>Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Konten Utama Profil -->
        <div class="col-lg-8">
            <!-- Tab: Profil -->
            <div class="card border-0 shadow-sm rounded-4 tab-content" id="profile-tab">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bold mb-4 border-bottom pb-3">Informasi Pribadi</h4>
                    
                    <!-- Form Update Profil -->
                    <form action="#" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label text-muted small">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" value="{{ auth()->user()->name ?? '' }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted small">Alamat Email</label>
                            <input type="email" class="form-control bg-light" value="{{ auth()->user()->email ?? '' }}" readonly disabled>
                            <div class="form-text">Email tidak dapat diubah untuk alasan keamanan.</div>
                        </div>
                        <button type="submit" class="btn btn-dark rounded-pill px-4">Simpan Perubahan</button>
                    </form>

                    <h4 class="fw-bold mb-4 border-bottom pb-3 mt-5">Ubah Kata Sandi</h4>
                    
                    <!-- Form Ubah Password -->
                    <form action="#" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label text-muted small">Kata Sandi Saat Ini</label>
                            <input type="password" class="form-control" name="current_password" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Kata Sandi Baru</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label text-muted small">Konfirmasi Sandi Baru</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-dark rounded-pill px-4">Perbarui Sandi</button>
                    </form>
                </div>
            </div>

            <!-- Tab: Alamat Pengiriman -->
            <div class="card border-0 shadow-sm rounded-4 tab-content" id="addresses-tab" style="display: none;">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <h4 class="fw-bold mb-0">Alamat Pengiriman Saya</h4>
                        <button class="btn btn-sm btn-dark rounded-pill px-3" id="addAddressBtn">
                            <i class="fas fa-plus me-2"></i>Tambah Alamat
                        </button>
                    </div>

                    <!-- Daftar Alamat -->
                    <div id="addressesList">
                        @forelse(auth()->user()->addresses as $address)
                            <div class="card border-0 bg-light rounded-3 mb-3 address-card">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <h6 class="fw-bold mb-0">{{ $address->first_name }} {{ $address->last_name }}</h6>
                                                @if($address->label)
                                                    <span class="badge bg-dark ms-2 rounded-pill px-2 py-1 small">{{ $address->label }}</span>
                                                @endif
                                                @if($address->is_default)
                                                    <span class="badge bg-primary ms-2 rounded-pill px-2 py-1 small">Alamat Utama</span>
                                                @endif
                                            </div>
                                            <p class="text-muted small mb-1">{{ $address->phone }}</p>
                                            <p class="text-muted small mb-0">{{ $address->address }}, {{ $address->city }} {{ $address->postal_code }}</p>
                                        </div>
                                        <div class="dropdown ms-3">
                                            <button class="btn btn-sm btn-outline-secondary rounded-circle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#" onclick="editAddress({{ $address->id }})"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteAddress({{ $address->id }})"><i class="fas fa-trash me-2"></i>Hapus</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-map-marker-alt fa-2x mb-3 d-block"></i>
                                <p>Anda belum memiliki alamat pengiriman</p>
                                <button class="btn btn-sm btn-dark rounded-pill px-3" id="addAddressBtn2">
                                    <i class="fas fa-plus me-2"></i>Tambah Alamat Pertama
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Tab: Riwayat Pesanan -->
            <div class="card border-0 shadow-sm rounded-4 tab-content" id="orders-tab" style="display: none;">
                <div class="card-body p-4 p-md-5 text-center py-5">
                    <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                    <p class="text-muted">Fitur riwayat pesanan sedang dalam pengembangan</p>
                </div>
            </div>

            <!-- Tab: Wishlist -->
            <div class="card border-0 shadow-sm rounded-4 tab-content" id="wishlist-tab" style="display: none;">
                <div class="card-body p-4 p-md-5 text-center py-5">
                    <i class="far fa-heart fa-2x text-muted mb-3"></i>
                    <p class="text-muted">Fitur wishlist sedang dalam pengembangan</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Buka modal tambah alamat
    function openAddressModal(data = null) {
        const modalEl = document.getElementById('addressModal');
        const modal = new bootstrap.Modal(modalEl);

        // reset
        modalEl.querySelector('form').reset();
        modalEl.querySelector('#address_id').value = '';

        if (data) {
            modalEl.querySelector('#address_id').value = data.id;
            modalEl.querySelector('#label').value = data.label || '';
            modalEl.querySelector('#first_name').value = data.first_name || '';
            modalEl.querySelector('#last_name').value = data.last_name || '';
            modalEl.querySelector('#phone').value = data.phone || '';
            modalEl.querySelector('#address_field').value = data.address || '';
            modalEl.querySelector('#city').value = data.city || '';
            modalEl.querySelector('#postal_code').value = data.postal_code || '';
            modalEl.querySelector('#is_default').checked = data.is_default ? true : false;
            modalEl.querySelector('form').action = `{{ route('addresses.update', ['address' => 'ADDRESS_ID']) }}`.replace('ADDRESS_ID', data.id);
            modalEl.querySelector('form').method = 'POST';
            modalEl.querySelector('#_method').value = 'PUT';
        } else {
            modalEl.querySelector('form').action = `{{ route('addresses.store') }}`;
            modalEl.querySelector('form').method = 'POST';
            modalEl.querySelector('#_method').value = '';
        }

        modal.show();
    }

    document.getElementById('addAddressBtn')?.addEventListener('click', function(){ openAddressModal(); });
    document.getElementById('addAddressBtn2')?.addEventListener('click', function(){ openAddressModal(); });

    function editAddress(id) {
        // Find address data from DOM (simple approach: fetch endpoint could be added)
        // For now, extract from existing cards
        const card = document.querySelector(`.address-card [onclick="editAddress(${id})"]`)?.closest('.address-card');
        if (!card) return;
        const name = card.querySelector('h6')?.textContent.trim() || '';
        const parts = name.split(' ');
        const first = parts.shift() || '';
        const last = parts.join(' ');
        const label = card.querySelector('.badge')?.textContent.trim() || '';
        const phone = card.querySelectorAll('p.text-muted')[0]?.textContent.trim() || '';
        const addressLine = card.querySelectorAll('p.text-muted')[1]?.textContent.trim() || '';

        // crude parsing of addressLine to city/postal
        openAddressModal({ id, first_name: first, last_name: last, label, phone, address: addressLine, city: '', postal_code: '' });
    }

    function deleteAddress(id) {
        if (!confirm('Hapus alamat ini?')) return;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('/profile/addresses') }}/${id}`;
        form.innerHTML = `@csrf <input type="hidden" name="_method" value="DELETE">`;
        document.body.appendChild(form);
        form.submit();
    }
</script>

<!-- Modal Add/Edit Address -->
<div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title">Tambah / Edit Alamat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                @csrf
                <input type="hidden" id="address_id" name="address_id" value="">
                <input type="hidden" id="_method" name="_method" value="">
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label small">Label (mis. Rumah, Kantor)</label>
                        <input id="label" name="label" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Nama Depan</label>
                            <input id="first_name" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Nama Belakang</label>
                            <input id="last_name" name="last_name" class="form-control">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Nomor Telepon</label>
                        <input id="phone" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Alamat Lengkap</label>
                        <textarea id="address_field" name="address" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Kota</label>
                            <input id="city" name="city" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Kode Pos</label>
                            <input id="postal_code" name="postal_code" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-check mt-2">
                        <input id="is_default" name="is_default" class="form-check-input" type="checkbox">
                        <label class="form-check-label small">Jadikan alamat utama</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark rounded-pill">Simpan Alamat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection