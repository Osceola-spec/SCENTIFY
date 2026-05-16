@extends('admin.layout')
@section('title', 'Manajemen & Showcase Brand')

@section('content')
    <div class="container-fluid px-0">
        <h4 class="fw-light mb-4">Manajemen Brand</h4>

        @if (session('success'))
            <div class="alert alert-success rounded-3 mb-4 border-0 shadow-sm">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 pb-2 border-bottom">Tambah Brand</h5>

                        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label text-muted small">Nama Brand <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="Contoh: Chanel, Dior, HMNS" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted small">Logo Brand (Opsional)</label>
                                <input type="file" name="logo_image"
                                    class="form-control @error('logo_image') is-invalid @enderror" accept="image/*">
                                <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Format: JPG, JPEG, PNG.
                                    Maksimal 2MB.</small>
                                @error('logo_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-dark rounded-pill py-2.5 fw-medium">
                                    <i class="fas fa-save me-2"></i> Simpan Brand
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 pb-2 border-bottom">Showcase Semua Brand ({{ $brands->count() }})</h5>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-muted fw-normal ps-3" style="width: 100px;">Logo</th>
                                        <th class="text-muted fw-normal">Nama Brand</th>
                                        <th class="text-muted fw-normal text-end pe-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($brands as $brand)
                                        <tr>
                                            <td class="ps-3">
                                                @if ($brand->logo_url)
                                                    <div class="bg-white border rounded p-1 d-flex align-items-center justify-content-center"
                                                        style="width: 50px; height: 50px; overflow: hidden;">
                                                        <img src="{{ asset('storage/' . $brand->logo_url) }}"
                                                            alt="{{ $brand->name }}"
                                                            style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                    </div>
                                                @else
                                                    <div class="bg-light text-muted rounded d-flex align-items-center justify-content-center border"
                                                        style="width: 50px; height: 50px; font-size: 1.2rem;">
                                                        {{ strtoupper(substr($brand->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold text-dark fs-6">{{ $brand->name }}</span>
                                            </td>
                                            <td class="text-end pe-3">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light text-muted rounded-circle"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul
                                                        class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                                        <li>
                                                            <button class="dropdown-item text-dark" type="button"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editModal{{ $brand->id }}">
                                                                <i class="fas fa-edit me-2 text-primary"></i> Edit
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.brands.destroy', $brand->id) }}"
                                                                method="POST" class="form-delete">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    class="dropdown-item text-danger btn-delete"
                                                                    data-name="{{ $brand->name }}">
                                                                    <i class="fas fa-trash-alt me-2"></i> Hapus
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="editModal{{ $brand->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow rounded-4">
                                                    <div class="modal-header border-bottom-0 pb-0">
                                                        <h5 class="modal-title fw-bold">Edit Brand</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.brands.update', $brand->id) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body p-4">
                                                            <div class="mb-3">
                                                                <label class="form-label text-muted small">Nama Brand <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="name" class="form-control"
                                                                    value="{{ $brand->name }}" required>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="form-label text-muted small">Ganti Logo Brand
                                                                    (Opsional)
                                                                </label>
                                                                <input type="file" name="logo_image"
                                                                    class="form-control" accept="image/*">
                                                                <small class="text-muted d-block mt-1"
                                                                    style="font-size: 0.75rem;">Biarkan kosong jika tidak
                                                                    ingin mengubah logo saat ini.</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top-0 pt-0">
                                                            <button type="button" class="btn btn-light rounded-pill px-4"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit"
                                                                class="btn btn-dark rounded-pill px-4">Simpan
                                                                Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-5 text-muted">
                                                <i class="fas fa-tag fa-2x mb-3 opacity-50"></i>
                                                <p class="mb-0">Belum ada brand di database. Gunakan form di sebelah kiri
                                                    untuk menambahkan.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Handle Konfirmasi Hapus dengan SweetAlert
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('.form-delete');
                const brandName = this.getAttribute('data-name');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Brand "${brandName}" akan dipindahkan ke tempat sampah!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1a1d20', // Warna hitam khas Scentify
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-4' // Agar matching dengan tema rounded Scentify
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Jalankan submit form jika klik Ya
                    }
                });
            });
        });

        // 2. Handle Notifikasi Sukses dari Session Laravel
        @if (session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#1a1d20',
                customClass: {
                    popup: 'rounded-4'
                }
            });
        @endif
    });
</script>
