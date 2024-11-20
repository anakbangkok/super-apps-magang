@extends('admin.layouts.app')

@section('title', 'Edit Mentor')

@section('content')

<div class="container my-5">
    <h2 class="mb-4" style="font-family: 'Arial', sans-serif;">Edit Mentor</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mentors.update', $mentor->id) }}" method="POST" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nik" class="form-label">NIK</label>
            <input type="text" class="form-control" id="nik" name="nik" value="{{ $mentor->nik }}" required>
        </div>

        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="name" value="{{ $mentor->name }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $mentor->email }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', this)">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation', this)">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="mb-3">
            <label for="jabatan" class="form-label">Jabatan</label>
            <select class="form-select" id="jabatan" name="jabatan" required>
                <option value="Manajer" {{ $mentor->jabatan == 'Manajer' ? 'selected' : '' }}>Manajer</option>
                <option value="SPV" {{ $mentor->jabatan == 'SPV' ? 'selected' : '' }}>SPV</option>
                <option value="Staff" {{ $mentor->jabatan == 'Staff' ? 'selected' : '' }}>Staff</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('mentor.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
    function togglePassword(inputId, toggleButton) {
        const passwordInput = document.getElementById(inputId);
        const icon = toggleButton.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

@endsection
