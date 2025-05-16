@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h3>Edit Acara</h3>
    <form action="{{ route('admin.acara.update', $acara->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Judul Acara</label>
            <input type="text" name="judul" class="form-control" value="{{ $acara->judul }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" required>{{ $acara->deskripsi }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $acara->tanggal }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Lokasi</label>
            <input type="text" name="lokasi" class="form-control" value="{{ $acara->lokasi }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar</label>
            <input type="file" name="gambar" class="form-control">
            @if($acara->gambar)
                <img src="{{ asset('storage/' . $acara->gambar) }}" class="img-fluid mt-2" style="max-height: 150px;">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
