@extends('admin.layouts.app')

@section('title', 'Edit Acara')

@section('content')
<div class="container my-5 mx-auto">
    <h2 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Edit Acara</h2>
    <form action="{{ route('admin.acara.update', $acara->id) }}" method="POST" enctype="multipart/form-data" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="judul" class="form-label">Judul Acara:</label>
            <input type="text" name="judul" id="judul" class="form-control" value="{{ $acara->judul }}" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi:</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control" required>{{ $acara->deskripsi }}</textarea>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal:</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $acara->tanggal }}" required>
        </div>

        <div class="mb-3">
            <label for="lokasi" class="form-label">Lokasi:</label>
            <input type="text" name="lokasi" id="lokasi" class="form-control" value="{{ $acara->lokasi }}">
        </div>

        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar:</label>
            <input type="file" name="gambar" id="gambar" class="form-control">
            @if($acara->gambar)
                <img src="{{ asset('storage/' . $acara->gambar) }}" class="img-fluid mt-2" style="max-height: 150px;">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('admin.acara.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
