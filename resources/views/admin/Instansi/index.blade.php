@extends('admin.layouts.app')

@section('title', 'Daftar Instansi')

@section('content')

    <div class="container my-5 mx-auto">
        <h2 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Daftar Instansi</h2>
        <a href="{{ route('instansi.create') }}" class="btn btn-primary mb-3">Tambah Instansi</a>

        @if (session('success'))
            <div class="alert alert-success" style="background-color: #d4edda; color: #155724;">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow">
            <h5 class="card-header text-right">Instansi Terdaftar</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama Instansi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @php $no = 1; @endphp <!-- Inisialisasi nomor urut -->
                        @foreach ($instansis as $instansi)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td> <!-- Menampilkan nomor urut di tengah -->
                                <td>{{ $instansi->nama_instansi }}</td>
                                <td class="text-center">
                                    <a href="{{ route('instansi.edit', $instansi) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form id="deleteInstansiForm" action="{{ route('instansi.destroy', $instansi) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#confirmDeleteModal">
                                            Hapus
                                        </button>

                                    </form>
                                    <!-- Modal Konfirmasi Penghapusan -->
                                    <div class="modal fade" id="confirmDeleteModal" tabindex="-1"
                                        aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content shadow">
                                                <div class="modal-header"
                                                    style="background-color: #f8d7da; color: #721c24;">
                                                    <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi
                                                        Penghapusan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus data ini?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <!-- Tombol untuk mengirim form penghapusan -->
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="document.getElementById('deleteInstansiForm').submit();">Ya, Hapus</button>
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
