@extends('mentor.layouts.app')

@section('title', 'Rekap Pengguna')

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
</style>

<div class="container py-5" style="background-color: white; border-radius: 8px;">
    <h2 class="mb-4">Daftar Manajemen Pengguna</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
            <a href="{{ route('mentor.users.user') }}" class="btn btn-secondary">Reset</a>
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
                        <td>{{ \Carbon\Carbon::parse($user->start_date)->translatedFormat('d F Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($user->end_date)->translatedFormat('d F Y') }}</td>
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
                {{ $users->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
        
    </div>
</div>

@endsection
