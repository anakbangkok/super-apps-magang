@extends('admin.layouts.app')

@section('title', 'Daftar Mentor')

@section('content')
    <div class="container mx-auto">
        <h1 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Daftar Mentor</h1>
        <a href="{{ route('mentors.create') }}" class="btn btn-primary mb-3">Tambah Mentor</a>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow">
    <div class="card-body">
        <h5 class="d-flex justify-content align-items-center">
            Mentor Terdaftar
        </h5>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>NIK</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mentors as $mentor)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($mentor->date)->translatedFormat('d F Y') }}</td>
                        <td>{{ $mentor->name }}</td>
                        <td>{{ $mentor->email }}</td>
                        <td>{{ $mentor->nik }}</td>
                        <td>{{ $mentor->jabatan }}</td>
                        <td>
                            <a href="{{ route('mentors.edit', $mentor->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <!-- Tombol untuk membuka modal konfirmasi hapus -->
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#confirmDeleteMentorModal{{ $mentor->id }}">
                                Hapus
                            </button>

                            <!-- Modal Konfirmasi Penghapusan -->
                            <div class="modal fade" id="confirmDeleteMentorModal{{ $mentor->id }}" tabindex="-1"
                                aria-labelledby="confirmDeleteMentorModalLabel{{ $mentor->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content shadow">
                                        <div class="modal-header" style="background-color: #f8d7da; color: #721c24;">
                                            <h5 class="modal-title" id="confirmDeleteMentorModalLabel{{ $mentor->id }}">
                                                Konfirmasi Penghapusan
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus mentor ini?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('mentors.destroy', $mentor->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                            </form>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Batal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
            </div>
        </div>
    </div>
@endsection
