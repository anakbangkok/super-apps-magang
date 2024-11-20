@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">
<!-- Menambahkan Link CSS untuk DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

@section('content')

<div class="container py-5">
    <h2>Daftar Manajemen Pengguna</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Tambah Pengguna</a>

    <form method="GET" action="{{ route('admin.users.index') }}" class="row mb-4">
        <div class="col-md-3 mb-2">
            <input type="text" name="searchName" class="form-control" placeholder="Cari Nama"
                value="{{ request('searchName') }}">
        </div>
        <div class="col-md-3 mb-2">
            <input type="text" name="searchEmail" class="form-control" placeholder="Cari Email"
                value="{{ request('searchEmail') }}">
        </div>
        <div class="col-md-3 mb-2">
            <select name="searchPenugasan" class="form-select">
                <option value="">Semua Penugasan</option>
                @foreach ($penugasans as $penugasan)
                    <option value="{{ $penugasan->id }}"
                        {{ request('searchPenugasan') == $penugasan->id ? 'selected' : '' }}>
                        {{ $penugasan->nama_unit_bisnis }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 mb-2">
            <select name="searchStatus" class="form-select">
                <option value="">Semua Status</option>
                <option value="Aktif" {{ request('searchStatus') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Belum Masuk" {{ request('searchStatus') == 'Belum Masuk' ? 'selected' : '' }}>Belum Masuk
                </option>
                <option value="Selesai" {{ request('searchStatus') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        <div class="col-md-6 d-flex gap-2 mb-2">
            <input type="date" name="startDate" class="form-control" value="{{ request('startDate') }}">
            <input type="date" name="endDate" class="form-control" value="{{ request('endDate') }}">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
            <a href="javascript:void(0)" class="btn btn-success" data-bs-toggle="modal"
                data-bs-target="#exportModal">Export</a>
        </div>
    </form>

    <div class="card shadow">
        <h5 class="card-header text-right">Daftar Pengguna</h5>
        <div class="table-responsive text-nowrap">
            <table id="usersTable" class="table table-striped table-bordered nowrap" style="width:100%">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Instansi</th>
                        <th>Penugasan</th>
                        <th>Mentor</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($users as $user)
                        <tr class="text-center">
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->instansi->nama_instansi ?? 'N/A' }}</td>
                            <td>{{ $user->penugasan->nama_unit_bisnis ?? 'N/A' }}</td>
                            <td>{{ $user->mentor->name ?? 'N/A' }}</td>
                            <td>{{ $user->start_date ? \Carbon\Carbon::parse($user->start_date)->translatedFormat('d F Y') : 'N/A' }}</td>
                            <td>{{ $user->end_date ? \Carbon\Carbon::parse($user->end_date)->translatedFormat('d F Y') : 'N/A' }}</td>
                            <td>
                                @php $now = now()->toDateString(); @endphp
                                @if (!$user->start_date || !$user->end_date)
                                    <span data-bs-toggle="tooltip" title="Data tidak ditemukan" class="badge bg-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </span>
                                @elseif ($now < $user->start_date)
                                    <span data-bs-toggle="tooltip" title="Belum masuk" class="badge bg-secondary">
                                        <i class="fas fa-clock"></i>
                                    </span>
                                @elseif ($now >= $user->start_date && $now <= $user->end_date)
                                    <span data-bs-toggle="tooltip" title="Aktif" class="badge bg-success">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                @else
                                    <span data-bs-toggle="tooltip" title="Selesai" class="badge bg-danger">
                                        <i class="fas fa-flag-checkered"></i>
                                    </span>
                                @endif
                            </td>
                            <td class="d-flex justify-content-start gap-2">
                                <!-- Edit button -->
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm mb-2" style="padding: 0.375rem 0.75rem; height: 28px;">Edit</a>
                                
                                <!-- Delete button inside a form -->
                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#confirmUserDeletionModal{{ $user->id }}">
                                        Hapus
                                    </button>
                            
                                    <!-- Modal content for delete confirmation -->
                                    <div class="modal fade" id="confirmUserDeletionModal{{ $user->id }}" tabindex="-1"
                                         aria-labelledby="confirmUserDeletionModalLabel{{ $user->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content shadow">
                                                <div class="modal-header" style="background-color: #f8d7da; color: #721c24;">
                                                    <h5 class="modal-title" id="confirmUserDeletionModalLabel{{ $user->id }}">
                                                        Apakah Anda yakin ingin menghapus?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Tutup"></button>
                                                </div>
                            
                                                <div class="modal-body">
                                                    <p class="text-muted">
                                                        Setelah Anda hapus, semua data akan hilang secara permanen.
                                                    </p>
                                                </div>
                            
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </td>
                                                                             
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>

$(document).ready(function() {
    $('#usersTable').DataTable({
        responsive: true, // Agar tabel responsif pada perangkat mobile
        columnDefs: [
            { className: "text-center", targets: "_all" } // Semua kolom diatur ke text-center
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya"
            }
        },
        initComplete: function() {
            // Menambahkan gaya CSS setelah DataTable dimuat
            $('.table th, .table td').css({
                'padding': '10px',  // Mengatur padding tabel
                'height': '15px'    // Mengatur tinggi baris
            });
        }
    });
});
    
</script>
@endsection
