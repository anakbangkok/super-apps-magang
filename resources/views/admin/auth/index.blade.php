@extends('admin.layouts.app')

@section('title', 'Daftar Admin')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-body">
            <h5 class="d-flex justify-content align-items-center">
                Kelola Admin
                <a href="{{ route('admin.create') }}" class="btn btn-white">
                    <i class="fas fa-plus" style="color: red;"></i>
                </a>                
            </h5>
            
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>
                                <!-- Tombol untuk membuka modal konfirmasi hapus -->
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteAdminModal{{ $admin->id }}">
                                    Hapus
                                </button>

                                <!-- Modal Konfirmasi Penghapusan -->
                                <div class="modal fade" id="confirmDeleteAdminModal{{ $admin->id }}" tabindex="-1" aria-labelledby="confirmDeleteAdminModalLabel{{ $admin->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content shadow">
                                            <div class="modal-header" style="background-color: #f8d7da; color: #721c24;">
                                                <h5 class="modal-title" id="confirmDeleteAdminModalLabel{{ $admin->id }}">
                                                    Konfirmasi Penghapusan
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus admin ini?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ route('admin.destroy', $admin->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination (optional) -->
            <div class="d-flex justify-content-center">
                {{ $admins->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
