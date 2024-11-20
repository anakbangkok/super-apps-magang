<section class="container my-5 mx-auto" style="max-width: 600px;">
    <h1 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Hapus Akun</h1>

    <div class="alert alert-warning" style="background-color: #fff3cd; color: #856404;">
        <strong>Peringatan:</strong> Setelah akun Anda dihapus, semua data akan hilang secara permanen. Pastikan Anda sudah mengunduh informasi yang ingin disimpan.
    </div>

    <!-- Trigger Button -->
    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        Hapus Akun
    </button>

    <!-- Modal -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header" style="background-color: #f8d7da; color: #721c24;">
                        <h5 class="modal-title" id="confirmUserDeletionModalLabel">
                            Apakah Anda yakin ingin menghapus akun?
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted">
                            Setelah akun Anda dihapus, semua data akan hilang secara permanen. Masukkan kata sandi Anda untuk mengonfirmasi penghapusan.
                        </p>

                        <div class="mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <input 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password" 
                                placeholder="Kata Sandi" 
                                required
                            >
                            @if ($errors->userDeletion->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->userDeletion->first('password') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger">
                            Hapus Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
