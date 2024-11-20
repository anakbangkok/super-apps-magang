<section class="container my-5 mx-auto">
    <h1 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Perbarui Kata Sandi</h1>

    <div class="alert alert-info" style="background-color: #e9ecef; color: #0c5460;">
        <strong>Info:</strong> Pastikan kata sandi Anda kuat dan unik untuk keamanan akun.
    </div>

    <form method="POST" action="{{ route('password.update') }}" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Current Password -->
            <div class="col-md-6 mb-3">
                <label for="update_password_current_password" class="form-label">KATA SANDI SAAT INI</label>
                <div class="input-group">
                    <input 
                        type="password" 
                        class="form-control @error('current_password') is-invalid @enderror"
                        id="update_password_current_password" 
                        name="current_password" 
                        autocomplete="current-password" 
                        required
                    >
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('update_password_current_password', this)">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- New Password -->
            <div class="col-md-6 mb-3">
                <label for="update_password_password" class="form-label">KATA SANDI BARU</label>
                <div class="input-group">
                    <input 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror"
                        id="update_password_password" 
                        name="password" 
                        autocomplete="new-password" 
                        required
                    >
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('update_password_password', this)">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="col-md-12 mb-3">
                <label for="update_password_password_confirmation" class="form-label">KONFIRMASI KATA SANDI BARU</label>
                <div class="input-group">
                    <input 
                        type="password" 
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        id="update_password_password_confirmation" 
                        name="password_confirmation" 
                        autocomplete="new-password" 
                        required
                    >
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('update_password_password_confirmation', this)">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-3">Simpan Perubahan</button>

        @if (session('status') === 'password-updated')
            <div class="alert alert-success mt-3" style="background-color: #d4edda; color: #155724;">
                Password successfully updated!
            </div>
        @endif
    </form>
</section>

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

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
