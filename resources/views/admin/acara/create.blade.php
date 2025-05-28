@extends('admin.layouts.app')

@section('title', 'Tambah Acara')

@section('content')
<div class="container my-5 mx-auto">
    <h2 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Tambah Acara</h2>
    <form action="{{ route('admin.acara.store') }}" method="POST" enctype="multipart/form-data" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf

        <div class="mb-3">
            <label for="judul" class="form-label">Judul Acara:</label>
            <input type="text" class="form-control" id="judul" name="judul" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi Acara:</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal Acara:</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>

        <div class="mb-3">
            <label for="lokasi" class="form-label">Lokasi Acara:</label>
            <input type="text" class="form-control" id="lokasi" name="lokasi" required>
        </div>

        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar Acara:</label>
            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.acara.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
