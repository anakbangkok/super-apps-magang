@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Jadwal Piket</h2>
        <form action="{{ route('jadwal_piket.update', $jadwalPiket->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $jadwalPiket->tanggal }}" required>
            </div>
            <div class="mb-3">
                <label for="instansi_id" class="form-label">Pilih Instansi</label>
                <select name="instansi_id[]" class="form-control" multiple required>
                    @foreach($instansis as $instansi)
                        <option value="{{ $instansi->id }}" 
                            {{ $jadwalPiket->instansis->contains($instansi->id) ? 'selected' : '' }}>
                            {{ $instansi->nama_instansi }}
                        </option>
                    @endforeach
                </select>
                <small>Pilih lebih dari satu dengan menekan tombol Ctrl (Windows) atau Command (Mac)</small>
            </div>
            <button type="submit" class="btn btn-primary">Perbarui</button>
            <a href="{{ route('jadwal_piket.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
