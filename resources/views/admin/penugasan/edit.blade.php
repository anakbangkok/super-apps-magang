@extends('admin.layouts.app')

@section('title', 'Edit Penugasan')

@section('content')
<div class="container my-5 mx-auto">
    <h2 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Edit Penugasan</h2>
    <form action="{{ route('penugasan.update', $penugasan) }}" method="POST" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nama_unit_bisnis" class="form-label">Nama Unit Bisnis</label>
            <input type="text" name="nama_unit_bisnis" class="form-control" value="{{ $penugasan->nama_unit_bisnis }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('penugasan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
