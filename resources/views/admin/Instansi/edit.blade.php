@extends('admin.layouts.app')

@section('title', 'Edit Instansi')

@section('content')
<div class="container my-5 mx-auto">
    <h2 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Edit Instansi</h2>
    <form action="{{ route('instansi.update', $instansi) }}" method="POST" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nama_instansi" class="form-label">Nama Instansi:</label>
            <input type="text" id="nama_instansi" name="nama_instansi" value="{{ $instansi->nama_instansi }}" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('instansi.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
