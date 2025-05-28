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

        <form method="POST" action="{{ route('admin.store') }}" class="p-4 border rounded shadow-sm"
            style="background-color: #f9f9f9;">
            @csrf

            <!-- Name Field -->
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                    required>
            </div>

            <!-- Email Field -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                    required>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Field (Default value "password") -->
<!-- Password Field -->
<div class="mb-3">
    <label for="password" class="form-label">Kata Sandi</label>
    <div class="input-group">
        <input type="password" class="form-control" id="password" name="password" value="password" required>
        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', 'passwordIcon')">
            <i class="fa fa-eye-slash" id="passwordIcon"></i>
        </button>
    </div>
</div>

<!-- Password Confirmation Field -->
<div class="mb-3">
    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
    <div class="input-group">
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="password" required>
        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation', 'passwordConfirmationIcon')">
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
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }
}
</script>

@endsection
