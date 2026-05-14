<!-- resources/views/auth/login.blade.php -->
@extends('base.base')

@section('content')
<div class="container py-5 my-md-5">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0">
                    <!-- Sisi Gambar (Hidden di mobile) -->
                    <div class="col-md-6 d-none d-md-block" style="background: url('https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&q=80&w=800') center/cover no-repeat;">
                        <div class="h-100 d-flex flex-column justify-content-center p-5" style="background-color: rgba(0,0,0,0.4);">
                            <h2 class="text-white fw-light mb-3">Selamat Datang Kembali.</h2>
                            <p class="text-white opacity-75 lead">Lanjutkan perjalanan Anda menemukan aroma yang mendefinisikan karakter Anda.</p>
                        </div>
                    </div>
                    
                    <!-- Sisi Formulir -->
                    <div class="col-md-6 p-4 p-md-5 bg-white">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold mb-1">Masuk ke Scentify</h3>
                            <p class="text-muted">Belum punya akun? <a href="{{ route('register') }}" class="text-dark fw-bold text-decoration-none border-bottom border-dark">Daftar sekarang</a></p>
                        </div>

                        <!-- Form Login -->
                        <form method="POST" action="{{ route('login.auth') }}">
                            @csrf

                            <!-- Email Input -->
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
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

                            <!-- Remember Me & Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input bg-dark border-dark" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted small" for="remember">
                                        Ingat Saya
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-muted small text-decoration-none">Lupa Sandi?</a>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-dark btn-lg rounded-pill py-3 fw-bold">Masuk</button>
                            </div>

                            <!-- Social Login (Opsional sesuai skema DB sebelumnya) -->
                            <div class="position-relative mb-4">
                                <hr class="text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">
                                    Atau masuk dengan
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-6">
                                    <a href="#" class="btn btn-outline-dark w-100 rounded-pill py-2">
                                        <i class="fab fa-google text-danger me-2"></i> Google
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="#" class="btn btn-outline-dark w-100 rounded-pill py-2">
                                        <i class="fab fa-apple me-2"></i> Apple
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection