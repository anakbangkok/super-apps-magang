@extends('admin.layouts.app')

@section('title', 'Tambah Tim Sosmed')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Tambah Data Tim Sosmed</h2>

    <form action="{{ route('tim_sosmed.store') }}" method="POST" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf

        <div class="mb-3">
            <label for="pekerjaan_hari_ini" class="form-label">Pekerjaan Hari Ini</label>
            <input type="text" class="form-control" id="pekerjaan_hari_ini" name="pekerjaan_hari_ini" required>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <input type="text" class="form-control" id="keterangan" name="keterangan" required>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('tim_sosmed.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
