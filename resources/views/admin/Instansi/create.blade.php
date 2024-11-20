@extends('admin.layouts.app')

@section('title', 'Tambah Instansi')

@section('content')
<div class="container my-5 mx-auto">
    <h2 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Tambah Instansi</h2>
    <form action="{{ route('instansi.store') }}" method="POST" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf
        <div class="mb-3">
            <label for="nama_instansi" class="form-label">Nama Instansi:</label>
            <input type="text" id="nama_instansi" name="nama_instansi" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('instansi.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
