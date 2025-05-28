@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="mb-3 text-start">
            <a href="{{ route('jadwal_piket.create') }}" class="btn btn-danger">
                Tambah Jadwal Piket
            </a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <h5 class="mb-3">Daftar Jadwal Piket</h5>
                @if (session('success'))
                    <div class="alert alert-success dimissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 20%;">Tanggal</th>
                            <th>Instansi</th>
                            <th style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwalPikets as $jadwal)
                        <tr>
                            <td>{{ $jadwal->tanggal }}</td>
                            <td>
                                @foreach($jadwal->instansis as $instansi)
                                    <span class="badge bg-info mb-1">{{ $instansi->nama_instansi }}</span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('jadwal_piket.edit', $jadwal->id) }}" class="btn btn-warning btn-sm me-1">
                                    Edit
                                </a>
                                <form action="{{ route('jadwal_piket.destroy', $jadwal->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
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
