@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Kelola Pengajuan Izin</h2>

    {{-- Filter Status dan Tanggal --}}
    <form method="GET" action="{{ route('admin.pengajuan_izin.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" 
                       value="{{ request('tanggal_mulai') }}">
            </div>
            <div class="col-md-4">
                <label for="tanggal_selesai">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" 
                       value="{{ request('tanggal_selesai') }}">
            </div>
            <div class="col-md-2">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>
    
    <div class="table-responsive">
        <table id="pengajuanIzinTable" class="table table-striped table-bordered nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pengguna</th>
                    <th>Jenis Izin</th>
                    <th>Durasi</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengajuan as $izin)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ optional($izin->user)->name ?? 'Tidak Diketahui' }}</td>
                        <td>{{ $izin->jenis_izin }}</td>
                        <td>{{ $izin->durasi }}</td>
                        <td>{{ $izin->tanggal_mulai }}</td>
                        <td>{{ $izin->tanggal_selesai ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $izin->status == 'menunggu' ? 'warning' : ($izin->status == 'disetujui' ? 'success' : 'danger') }}">
                                {{ ucfirst($izin->status) }}
                            </span>
                        </td>
                        <td>
                            @if ($izin->status == 'menunggu')
                                <form action="{{ route('admin.pengajuan_izin.approve', $izin) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                </form>
                                <form action="{{ route('admin.pengajuan_izin.reject', $izin) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                </form>
                            @else
                                <span>Tindakan selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada pengajuan izin ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

<script>
    $(document).ready(function () {
        $('#pengajuanIzinTable').DataTable();
    });
</script>

<style>
    .dataTables_filter {
        display: flex;
        align-items: center;
    }

    .dataTables_filter label {
        margin-right: 10px;
    }

    .dataTables_filter input {
        margin-left: 5px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        transition: border-color 0.3s;
    }

    .dataTables_filter input:focus {
        border-color: #007bff;
        outline: none;
    }

    .dataTables_length, .dataTables_info, .dataTables_paginate {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .dataTables_length label,
    .dataTables_info {
        margin-right: 10px;
    }

    .dataTables_length select {
        margin-left: 5px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        transition: border-color 0.3s;
    }

    .dataTables_paginate {
        margin-top: 10px;
    }

    .dataTables_paginate .paginate_button {
        margin: 0 2px;
        padding: 10px; 
        border-radius: 5px;
        border: 1px solid #007bff;
        background-color: #fff;
        color: #007bff;
    }

    .dataTables_paginate .paginate_button.current {
        background-color: #007bff;
        color: white;
    }

    .dataTables_paginate .paginate_button:hover {
        background-color: #0056b3;
        color: white;
    }
</style>
@endsection
