@extends('admin.layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Edit Pengguna</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <!-- Dropdown Nama Instansi -->
        <div class="mb-3">
            <label for="instansi" class="form-label">Nama Instansi</label>
            <select class="form-control select2" id="instansi" name="instansi" required>
                <option value="" disabled>Pilih Instansi</option>
                @foreach ($instansis as $instansi)
                    <option value="{{ $instansi->id }}" {{ $instansi->id == $user->instansi_id ? 'selected' : '' }}>
                        {{ $instansi->nama_instansi }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Dropdown Penugasan -->
        <div class="mb-3">
            <label for="penugasan" class="form-label">Pilih Penugasan</label>
            <select class="form-select select2" id="penugasan" name="penugasan" required>
                <option value="" disabled>Pilih Penugasan</option>
                @foreach ($penugasans as $penugasan)
                    <option value="{{ $penugasan->id }}" {{ $penugasan->id == $user->penugasan_id ? 'selected' : '' }}>
                        {{ $penugasan->nama_unit_bisnis }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Dropdown Mentor -->
        <div class="mb-3">
            <label for="mentor" class="form-label">Pilih Mentor</label>
            <select class="form-select select2" id="mentor" name="mentor" required>
                <option value="" disabled>Pilih Mentor</option>
                @foreach ($mentors as $mentor)
                    <option value="{{ $mentor->id }}" {{ $mentor->id == $user->mentor_id ? 'selected' : '' }}>{{ $mentor->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tanggal Mulai -->
        <div class="mb-3">
            <label for="start_date" class="form-label">Tanggal Mulai</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $user->start_date) }}" required>
        </div>

        <!-- Tanggal Selesai -->
        <div class="mb-3">
            <label for="end_date" class="form-label">Tanggal Selesai</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $user->end_date) }}" required>
        </div>

        <!-- Password (auto fill) -->
        <div class="mb-3">
            <label for="password" class="form-label">Kata Sandi</label>
            <input type="password" class="form-control" id="password" name="password" value="rumahmesin" readonly required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="rumahmesin" readonly required>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
