@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>➕ Tambah Acara Baru</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.acara.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-3">
            <label for="judul" class="form-label">Judul Acara</label>
            <input type="text" class="form-control" id="judul" name="judul" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi Acara</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal Acara</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>

        <div class="mb-3">
            <label for="lokasi" class="form-label">Lokasi Acara</label>
            <input type="text" class="form-control" id="lokasi" name="lokasi">
        </div>

        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar Acara</label>
            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Acara</button>
        <a href="{{ route('admin.acara.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
