@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')

    <style>
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }

        .pagination .page-link {
            color: #F44335;
            background-color: white;
            border: 1px solid #F44335;
        }

        .pagination .page-link:hover {
            background-color: #c62828;
            color: white;
        }

        .pagination .page-item.active .page-link {
            background-color: #F44335;
            color: white;
            border: 1px solid #F44335;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: white;
            border: 1px solid #dee2e6;
        }

        .badge {
            font-size: 1.2em;
            padding: 0.5em;
        }

        /* Tambahan untuk mengubah ukuran tombol */
        .btn-small {
            padding: 0.2em 0.5em;
            font-size: 0.8em;
            line-height: 1.5;
        }
    </style>

    <div class="container py-5">
        <h2>Daftar Manajemen Pengguna</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Tambah Pengguna</a>

        <!-- Form Pencarian -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="row mb-4">
            <div class="col-md-3 mb-2">
                <input type="text" name="searchName" class="form-control" placeholder="Cari Nama" value="{{ request('searchName') }}">
            </div>
            <div class="col-md-3 mb-2">
                <input type="text" name="searchEmail" class="form-control" placeholder="Cari Email" value="{{ request('searchEmail') }}">
            </div>
            <div class="col-md-3 mb-2">
                <select name="searchPenugasan" class="form-select">
                    <option value="">Semua Penugasan</option>
                    @foreach ($penugasans as $penugasan)
                        <option value="{{ $penugasan->id }}" {{ request('searchPenugasan') == $penugasan->id ? 'selected' : '' }}>
                            {{ $penugasan->nama_unit_bisnis }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <select name="searchStatus" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Aktif" {{ request('searchStatus') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Belum Masuk" {{ request('searchStatus') == 'Belum Masuk' ? 'selected' : '' }}>Belum Masuk</option>
                    <option value="Selesai" {{ request('searchStatus') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="col-md-6 d-flex gap-2 mb-2">
                <input type="date" name="startDate" class="form-control" value="{{ request('startDate') }}">
                <input type="date" name="endDate" class="form-control" value="{{ request('endDate') }}">
                <button type="submit" class="btn btn-primary">Cari</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table id="usersTable" class="table table-striped table-bordered nowrap" style="width:100%">
                <thead>
                    <tr>
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
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->instansi->nama_instansi ?? 'N/A' }}</td>
                            <td>{{ $user->penugasan->nama_unit_bisnis ?? 'N/A' }}</td>
                            <td>{{ $user->mentor->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($user->start_date)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($user->end_date)->format('d-m-Y') }}</td>
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
                            <td class="d-flex flex-column">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-small mb-2">Edit</a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">Hapus</button>
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

        {{ $users->links() }} <!-- Pagination links -->
    </div>

    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/Indonesian.json' // Ganti dengan bahasa sesuai kebutuhan
                }
            });
            
            // Inisialisasi tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

@endsection
