@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="mb-3">
        <a href="{{ route('admin.acara.create') }}" class="btn btn-danger">Tambah Acara</a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <h5 class="mb-3">Daftar Pengumuman</h5>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Gambar</th>
                        <th style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($acara as $event)
                        <tr>
                            <td>{{ $event->judul }}</td>
                            <td>{{ $event->deskripsi }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }}</td>
                            <td>{{ $event->lokasi }}</td>
                            <td>
                                @if($event->gambar)
                                    <img src="{{ asset('storage/' . $event->gambar) }}" width="100" class="img-thumbnail">
                                @else
                                    <span class="text-muted">Tidak ada gambar</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.acara.edit', $event->id) }}" class="btn btn-warning btn-sm me-1">
                                    Edit
                                </a>
                                <form action="{{ route('admin.acara.destroy', $event->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus acara ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
