@extends('admin.layouts.app')

@section('title', 'Tambah Penugasan')

@section('content')
<div class="container my-5 mx-auto">
    <h2 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Tambah Penugasan</h2>
    <form action="{{ route('penugasan.store') }}" method="POST" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf
        <div class="mb-3">
            <label for="nama_unit_bisnis" class="form-label">Nama Unit Bisnis</label>
            <input type="text" name="nama_unit_bisnis" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('penugasan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
