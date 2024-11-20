@extends('layouts.app')

@section('title', 'Edit Tim Sosmed')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Edit Data Tim Sosmed</h2>

    <form action="{{ route('tim_sosmeds.update', $tim_sosmed->id) }}" method="POST" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ $tim_sosmed->user->name }}" required readonly>
        </div>

        <div class="mb-3">
            <label for="pekerjaan_hari_ini" class="form-label">Pekerjaan Hari Ini</label>
            <input type="text" class="form-control" id="pekerjaan_hari_ini" name="pekerjaan_hari_ini" value="{{ $tim_sosmed->pekerjaan_hari_ini }}" required>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <input type="text" class="form-control" id="keterangan" name="keterangan" value="{{ $tim_sosmed->keterangan }}" required>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ $tim_sosmed->tanggal }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('tim_sosmeds.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
