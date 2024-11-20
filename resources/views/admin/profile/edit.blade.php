@extends('admin.layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="container my-5 mx-auto">
        <h1 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Edit Profil</h1>

        @if (session('status') === 'profile-updated')
            <div class="alert alert-success" style="background-color: #d4edda; color: #155724;">
                Profil berhasil diperbarui.
            </div>
        @endif

        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                    role="tab">
                    Perbarui Profil
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" role="tab">
                    Ganti Password
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="delete-tab" data-bs-toggle="tab" data-bs-target="#delete" role="tab">
                    Hapus Akun
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Profil -->
            <div class="tab-pane fade show active" id="profile" role="tabpanel">
                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data"
                    class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
                    @csrf
                    @method('PATCH')

                    <div class="text-center mb-4">
                        <label for="profile_photo" class="form-label">Foto Profil</label>
                        <div class="mt-2">
                            <img src="{{ asset(auth()->user()->profile_photo ? 'storage/' . auth()->user()->profile_photo : 'assets/img/avatars/default.jpg') }}"
                                alt="User Avatar" class="w-px-150 h-auto rounded-circle shadow" />
                        </div>
                        <input type="file" class="form-control mt-3" id="profile_photo" name="profile_photo"
                            accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ auth()->user()->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ auth()->user()->email }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">Simpan Perubahan</button>
                </form>
            </div>

            <!-- Ganti Password -->
            <div class="tab-pane fade" id="password" role="tabpanel">
                <form method="POST" action="{{ route('admin.password.update') }}" class="p-4 border rounded shadow-sm"
                    style="background-color: #f9f9f9;">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="current_password" name="current_password"
                                required>
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="togglePassword('current_password', this)">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">Kata Sandi Baru</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="togglePassword('new_password', this)">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password_confirmation"
                                name="new_password_confirmation" required>
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="togglePassword('new_password_confirmation', this)">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">Simpan Perubahan</button>
                </form>
            </div>

            <!-- Hapus Akun -->
            <div class="tab-pane fade" id="delete" role="tabpanel">
                <section class="container my-5 mx-auto" style="max-width: 600px;">
                    <h1 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Hapus Akun</h1>

                    <div class="alert alert-warning" style="background-color: #fff3cd; color: #856404;">
                        <strong>Peringatan:</strong> Setelah akun Anda dihapus, semua data akan hilang secara permanen. Pastikan Anda sudah mengunduh informasi yang ingin disimpan.
                    </div>

                    <!-- Trigger Button -->
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
                        Hapus Akun
                    </button>

                    <!-- Modal Konfirmasi Hapus Akun -->
                    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content shadow">
                                <form id="deleteAccountForm" action="{{ route('admin.profile.destroy') }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <div class="modal-header" style="background-color: #f8d7da; color: #721c24;">
                                        <h5 class="modal-title" id="confirmUserDeletionModalLabel">Apakah Anda yakin ingin menghapus akun?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>

                                    <div class="modal-body">
                                        <p class="text-muted">
                                            Setelah akun Anda dihapus, semua data akan hilang secara permanen.
                                        </p>

                                        {{-- <div class="mb-3">
                                            <label for="current_password" class="form-label">Kata Sandi</label>
                                            <input 
                                                type="password" 
                                                class="form-control @error('current_password') is-invalid @enderror" 
                                                id="current_password" 
                                                name="current_password" 
                                                placeholder="Kata Sandi" 
                                                required
                                            >
                                            @error('current_password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div> --}}
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger" form="deleteAccountForm">Hapus Akun</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, toggleButton) {
            const passwordInput = document.getElementById(inputId);
            const icon = toggleButton.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

    <!-- Tambahkan Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
