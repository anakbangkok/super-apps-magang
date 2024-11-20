@extends('admin.layouts.app')

@section('title', 'Daftar Mentor')

@section('content')
<div class="container my-5 mx-auto">
    <h1 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Daftar Mentor</h1>
    <a href="{{ route('mentors.create') }}" class="btn btn-primary mb-3">Tambah Mentor</a>
    
    <div class="card shadow">
        <h5 class="card-header text-right">Mentor Terdaftar</h5>
        <div class="table-responsive text-nowrap">
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
                <tbody class="table-border-bottom-0">
                    @foreach ($mentors as $index => $mentor)
                        <tr>
                            <td>{{ $index + 1 }}</td> 
                            <td>{{ \Carbon\Carbon::parse($mentor->date)->translatedFormat('d F Y') }}</td>
                            <td>{{ $mentor->name }}</td>
                            <td>{{ $mentor->email }}</td>
                            <td>{{ $mentor->nik }}</td>
                            <td>{{ $mentor->jabatan }}</td>
                            <td class="text-center">
                                <a href="{{ route('mentors.edit', $mentor->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form id="deleteMentorForm{{ $mentor->id }}" action="{{ route('mentors.destroy', $mentor->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteModal{{ $mentor->id }}">
                                        Hapus
                                    </button>
                                </form>
                                <!-- Modal Konfirmasi Penghapusan -->
                                <div class="modal fade" id="confirmDeleteModal{{ $mentor->id }}" tabindex="-1"
                                    aria-labelledby="confirmDeleteModalLabel{{ $mentor->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content shadow">
                                            <div class="modal-header" style="background-color: #f8d7da; color: #721c24;">
                                                <h5 class="modal-title" id="confirmDeleteModalLabel{{ $mentor->id }}">Konfirmasi Penghapusan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus mentor ini?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    onclick="document.getElementById('deleteMentorForm{{ $mentor->id }}').submit();">Ya, Hapus</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
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
