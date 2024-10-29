@extends('mentor.layouts.app')

@section('title', 'User Management')

@section('content')

<style>
    .pagination {
        justify-content: center; /* Menyelaraskan pagination di tengah */
        margin-top: 20px; /* Menambah jarak atas */
    }

    .pagination .page-link {
        color: #F44335; /* Ubah warna teks untuk link normal */
        background-color: white; /* Ubah warna latar untuk link normal */
        border: 1px solid #F44335; /* Ubah warna border */
    }

    .pagination .page-link:hover {
        background-color: #c62828; /* Ubah warna latar saat hover (lebih gelap) */
        color: white; /* Ubah warna teks saat hover */
    }

    .pagination .page-item.active .page-link {
        background-color: #F44335; /* Ubah warna latar untuk item aktif */
        color: white; /* Ubah warna teks untuk item aktif */
        border: 1px solid #F44335; /* Border item aktif */
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d; /* Warna untuk link yang dinonaktifkan */
        background-color: white; /* Warna latar untuk link yang dinonaktifkan */
        border: 1px solid #dee2e6; /* Border untuk link yang dinonaktifkan */
    }
</style>

<div class="container py-5" style="background-color: white; border-radius: 8px;">
    <h2 class="mb-4">Daftar Manajemen Pengguna</h2>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Form Pencarian -->
    <form method="GET" action="{{ route('mentor.users.user') }}" class="row mb-4">
        <div class="col-md-3 mb-2">
            <input type="text" name="searchName" class="form-control" placeholder="Cari Nama" value="{{ request('searchName') }}">
        </div>
        <div class="col-md-3 mb-2">
            <input type="text" name="searchEmail" class="form-control" placeholder="Cari Email" value="{{ request('searchEmail') }}">
        </div>
        <div class="col-md-3 mb-2">
            <select name="searchPenugasan" class="form-select">
                <option value="">Semua Penugasan</option>
                @foreach($penugasans as $penugasan)
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
        <div class="col-md-6 d-flex gap-2 mt-2">
            <input type="date" name="startDate" class="form-control" value="{{ request('startDate') }}">
            <input type="date" name="endDate" class="form-control" value="{{ request('endDate') }}">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="{{ route('mentor.users.user') }}" class="btn btn-secondary">Reset</a> <!-- Tombol Reset -->
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Instansi</th>
                    <th>Penugasan</th>
                    <th>Mentor</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->instansi->nama_instansi ?? 'N/A' }}</td>
                        <td>{{ $user->penugasan->nama_unit_bisnis ?? 'N/A' }}</td>
                        <td>{{ $user->mentor->name ?? 'N/A' }}</td>
                        <td>{{ $user->start_date }}</td>
                        <td>{{ $user->end_date }}</td>
                        <td>
                            @php $now = now()->toDateString(); @endphp
                            @if (!$user->start_date || !$user->end_date)
                                <span class="badge bg-warning">Data tidak ditemukan</span>
                            @elseif ($now < $user->start_date)
                                <span class="badge bg-secondary">Belum Masuk</span>
                            @elseif ($now >= $user->start_date && $now <= $user->end_date)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="text-muted">
                    Menampilkan {{ $users->firstItem() }} dari {{ $users->lastItem() }} dari {{ $users->total() }} hasil
                </span>
            </div>
            <div>
                {{ $users->links('vendor.pagination.bootstrap-5') }} <!-- Menampilkan pagination -->
            </div>
        </div>
        
    </div>
</div>

@endsection
