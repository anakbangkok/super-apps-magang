@extends('admin.layouts.app')

@section('title', 'Tambah Admin')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Tambah Admin</h1>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.store') }}" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf

        <!-- Name Field -->
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password Field (Default value "password") -->
        <div class="mb-3">
            <label for="password" class="form-label">Kata Sandi</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" value="password" required>
                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                    <i class="fa fa-eye-slash" id="passwordIcon"></i>
                </button>
            </div>
        </div>

        <!-- Password Confirmation Field (Default value "password") -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="password" required>
                <button type="button" class="btn btn-outline-secondary" id="togglePasswordConfirmation">
                    <i class="fa fa-eye-slash" id="passwordConfirmationIcon"></i>
                </button>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

@endsection

@section('scripts')
    <!-- Include jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to toggle password visibility
            function toggleVisibility(inputId, iconId) {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);

                // Toggle input type between 'password' and 'text'
                const isPassword = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPassword ? 'text' : 'password');
                icon.classList.toggle('fa-eye-slash', !isPassword);
                icon.classList.toggle('fa-eye', isPassword);
                
                // Re-enable readonly after toggle
                if (isPassword) {
                    setTimeout(() => {
                        input.setAttribute('readonly', true);
                    }, 100);
                }
            }

            // Add event listener for password toggle
            document.getElementById('togglePassword').addEventListener('click', function() {
                toggleVisibility('password', 'passwordIcon');
            });

            // Add event listener for password confirmation toggle
            document.getElementById('togglePasswordConfirmation').addEventListener('click', function() {
                toggleVisibility('password_confirmation', 'passwordConfirmationIcon');
            });
        });
    </script>
@endsection
