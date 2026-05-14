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
                    <a href="#" class="list-group-item list-group-item-action active bg-dark border-dark text-white py-3 px-4">
                        <i class="far fa-user me-3"></i>Profil Saya
                    </a>
                    <a href="#" class="list-group-item list-group-item-action py-3 px-4">
                        <i class="fas fa-shopping-bag me-3 text-muted"></i>Riwayat Pesanan
                    </a>
                    <a href="#" class="list-group-item list-group-item-action py-3 px-4">
                        <i class="far fa-heart me-3 text-muted"></i>Wishlist Tersimpan
                    </a>
                    <a href="#" class="list-group-item list-group-item-action py-3 px-4">
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
            <div class="card border-0 shadow-sm rounded-4">
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
        </div>
    </div>
</div>
@endsection