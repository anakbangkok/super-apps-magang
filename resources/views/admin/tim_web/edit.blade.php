@extends('admin.layouts.app')

@section('title', 'Edit Tim Web')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Edit Data Tim Web</h2>

    <!-- Form Edit Tim Web -->
    <form action="{{ route('tim_web.update', $tim_web->id) }}" method="POST" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="jumlah_artikel" class="form-label">Jumlah Artikel</label>
            <input type="number" name="jumlah_artikel" value="{{ $tim_web->jumlah_artikel }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="jumlah_kata" class="form-label">Jumlah Kata</label>
            <input type="number" name="jumlah_kata" value="{{ $tim_web->jumlah_kata }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" required>{{ $tim_web->keterangan }}</textarea>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" value="{{ $tim_web->tanggal }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('tim_web.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
