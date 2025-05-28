@extends('mentor.layouts.app')

@section('title', 'Rekap Pengguna')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">
<!-- Menambahkan Link CSS untuk DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

@section('content')

    <div class="container py-5 mx-4" style="background-color: white; padding: 20px; border-radius: 10px;">
        <div class="card-shadow">
            <h2 class="mb-4">Daftar Manajemen Pengguna</h2>
            <button class="btn btn-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#filterPanel">Filter
                Pencarian</button>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="collapse" id="filterPanel">
                <div class="card card-body">
                    <div class="card-body">
                        <form method="GET" action="{{ route('mentor.users.user') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Nama</label>
                                    <input type="text" name="searchName" class="form-control" placeholder="Cari Nama"
                                        value="{{ request('searchName') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Email</label>
                                    <input type="text" name="searchEmail" class="form-control" placeholder="Cari Email"
                                        value="{{ request('searchEmail') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Penugasan</label>
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
                                <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select name="searchStatus" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="Aktif" {{ request('searchStatus') == 'Aktif' ? 'selected' : '' }}>
                                            Aktif
                                        </option>
                                        <option value="Belum Masuk"
                                            {{ request('searchStatus') == 'Belum Masuk' ? 'selected' : '' }}>Belum Masuk
                                        </option>
                                        <option value="Selesai"
                                            {{ request('searchStatus') == 'Selesai' ? 'selected' : '' }}>
                                            Selesai</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input type="date" name="startDate" class="form-control"
                                        value="{{ request('startDate') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Tanggal Selesai</label>
                                    <input type="date" name="endDate" class="form-control"
                                        value="{{ request('endDate') }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end gap-2">
                                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                                    <a href="{{ route('mentor.users.user') }}" class="btn btn-secondary w-100">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="table-responsive mt-3">
                <table id="userTable" class="table table-bordered table-striped" style="min-width: 900px;">
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
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->instansi->nama_instansi ?? 'N/A' }}</td>
                                <td>{{ $user->penugasan->nama_unit_bisnis ?? 'N/A' }}</td>
                                <td>{{ $user->mentor->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($user->start_date)->translatedFormat('d F Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($user->end_date)->translatedFormat('d F Y') }}</td>
                                <td>
                                    @if (!$user->status)
                                        <span class="badge bg-warning">Data tidak ditemukan</span>
                                    @elseif ($user->status == 'Belum Masuk')
                                        <span class="badge bg-secondary">Belum Masuk</span>
                                    @elseif ($user->status == 'Aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @elseif ($user->status == 'Selesai')
                                        <span class="badge bg-danger">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $user->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
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
            $('#userTable').DataTable({
                responsive: true,
                columnDefs: [{
                    scrollX: true,
                    className: "text-center",
                    targets: "_all"
                }],
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ada hasil untuk pencarian Anda",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_ halaman",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "search": "Cari:",
                    "paginate": {
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    }
                },
                dom: '<"d-flex justify-content-between align-items-center mb-2"lf>' +
                    // length + filter di atas
                    '<"table-responsive"t>' + // table scroll
                    '<"d-flex justify-content-between align-items-center mt-2"ip>', // info + pagination
                initComplete: function() {
                    $('.table th, .table td').css({
                        'padding': '10px',
                        'height': '15px'
                    });
                },
                initComplete: function() {
                    $('.table th, .table td').css({
                        'padding': '10px',
                        'height': '15px'
                    });
                }
            });
        });
    </script>

@endsection
