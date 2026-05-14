<!-- resources/views/auth/register.blade.php -->
@extends('base.base')

@section('content')
<div class="container py-5 my-md-4">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0 flex-md-row-reverse">
                    <!-- Sisi Gambar (Hidden di mobile) - Gambar dibalik untuk variasi -->
                    <div class="col-md-6 d-none d-md-block" style="background: url('https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&q=80&w=800') center/cover no-repeat;">
                        <div class="h-100 d-flex flex-column justify-content-center p-5" style="background-color: rgba(0,0,0,0.5);">
                            <h2 class="text-white fw-light mb-3">Bergabung dengan Klub.</h2>
                            <p class="text-white opacity-75 lead">Akses koleksi eksklusif, lacak pesanan Anda, dan simpan wishlist parfum impian.</p>
                        </div>
                    </div>
                    
                    <!-- Sisi Formulir -->
                    <div class="col-md-6 p-4 p-md-5 bg-white">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold mb-1">Buat Akun</h3>
                            <p class="text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="text-dark fw-bold text-decoration-none border-bottom border-dark">Masuk di sini</a></p>
                        </div>

                        <!-- Form Register -->
                        <form method="POST" action="{{ route('register.auth') }}">
                            @csrf

                            <!-- Nama Lengkap Input -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" required autofocus>
                                <label for="name" class="text-muted"><i class="far fa-user me-2"></i>Nama Lengkap</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Input -->
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required>
                                <label for="email" class="text-muted"><i class="far fa-envelope me-2"></i>Alamat Email</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Input -->
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required>
                                <label for="password" class="text-muted"><i class="fas fa-lock me-2"></i>Kata Sandi</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password Input -->
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" required>
                                <label for="password_confirmation" class="text-muted"><i class="fas fa-check-double me-2"></i>Konfirmasi Kata Sandi</label>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-dark btn-lg rounded-pill py-3 fw-bold">Daftar Sekarang</button>
                            </div>
                            
                            <p class="text-center text-muted small mb-0">Dengan mendaftar, Anda menyetujui Syarat & Ketentuan serta Kebijakan Privasi Scentify.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection