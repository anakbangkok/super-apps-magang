@extends('admin.layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Tambah Pengguna</h1>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Dropdown Nama Instansi -->
        <div class="mb-3">
            <label for="instansi" class="form-label">Nama Instansi</label>
            <select class="form-control select2" id="instansi" name="instansi" required>
                <option value="" disabled selected>Pilih Instansi</option>
                @foreach ($instansis as $instansi)
                    <option value="{{ $instansi->id }}">{{ $instansi->nama_instansi }}</option>
                @endforeach
            </select>
        </div>

        <!-- Dropdown Penugasan -->
        <div class="mb-3">
            <label for="penugasan" class="form-label">Pilih Penugasan</label>
            <select class="form-select select2" id="penugasan" name="penugasan" required>
                <option value="" disabled selected>Pilih Penugasan</option>
                @foreach ($penugasans as $penugasan)
                    <option value="{{ $penugasan->id }}">{{ $penugasan->nama_unit_bisnis }}</option>
                @endforeach
            </select>
        </div>

        <!-- Dropdown Mentor -->
        <div class="mb-3">
            <label for="mentor" class="form-label">Pilih Mentor</label>
            <select class="form-select select2" id="mentor" name="mentor" required>
                <option value="" disabled selected>Pilih Mentor</option>
                @foreach ($mentors as $mentor)
                    <option value="{{ $mentor->id }}">{{ $mentor->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tanggal Mulai -->
        <div class="mb-3">
            <label for="start_date" class="form-label">Tanggal Mulai</label>
            <input type="date" class="form-control" id="start_date" name="start_date" required>
        </div>

        <!-- Tanggal Selesai -->
        <div class="mb-3">
            <label for="end_date" class="form-label">Tanggal Selesai</label>
            <input type="date" class="form-control" id="end_date" name="end_date" required>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Kata Sandi</label>
            <input type="password" class="form-control" id="password" name="password" value="rumahmesin" readonly required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="rumahmesin" readonly required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection

@section('scripts')
<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Inisialisasi Select2
        $('.select2').select2({
            placeholder: "Pilih",
            allowClear: true
        });
    });
</script>
@endsection
