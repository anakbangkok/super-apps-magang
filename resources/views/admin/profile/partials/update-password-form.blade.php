<div class="tab-pane fade" id="password" role="tabpanel">
    <h1 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Perbarui Kata Sandi</h1>

    <div class="alert alert-info" style="background-color: #e9ecef; color: #0c5460;">
        <strong>Info:</strong> Pastikan kata sandi Anda kuat dan unik untuk keamanan akun.
    </div>

    <form method="POST" action="{{ route('admin.password.update') }}" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Current Password -->
            <div class="col-md-6 mb-3">
                <label for="current_password" class="form-label">KATA SANDI SAAT INI</label>
                <input 
                    type="password" 
                    class="form-control @error('current_password') is-invalid @enderror"
                    id="current_password" 
                    name="current_password" 
                    required
                >
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- New Password -->
            <div class="col-md-6 mb-3">
                <label for="new_password" class="form-label">KATA SANDI BARU</label>
                <input 
                    type="password" 
                    class="form-control @error('new_password') is-invalid @enderror"
                    id="new_password" 
                    name="new_password" 
                    required
                >
                @error('new_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="col-md-12 mb-3">
                <label for="new_password_confirmation" class="form-label">KONFIRMASI KATA SANDI BARU</label>
                <input 
                    type="password" 
                    class="form-control @error('new_password_confirmation') is-invalid @enderror"
                    id="new_password_confirmation" 
                    name="new_password_confirmation" 
                    required
                >
                @error('new_password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-3">Ganti Password</button>

        @if (session('status') === 'password-updated')
            <div class="alert alert-success mt-3" style="background-color: #d4edda; color: #155724;">
                Password berhasil diperbarui!
            </div>
        @endif
    </form>
</div>
