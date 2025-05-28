@extends('admin.layouts.app')

@section('title', 'Daftar Pengajuan Izin')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

@section('content')
    <div class="container mt-4">
        <h2>Kelola Pengajuan Izin</h2>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow mt-4">
           <form method="GET" action="{{ route('admin.pengajuan_izin.index') }}" class="mb-4 mt-4" id="filter-form">
                <h5 class="ms-3">Filter Data</h5>
                <div class="container">
                    <div class="row mt-3">
                        {{-- Filter Tanggal Mulai --}}
                        <div class="col-sm-12 col-md-4 mb-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control"
                                value="{{ request('tanggal_mulai') }}" placeholder="Pilih tanggal awal">
                        </div>

                        {{-- Filter Tanggal Selesai --}}
                        <div class="col-sm-12 col-md-4 mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control"
                                value="{{ request('tanggal_selesai') }}" placeholder="Pilih tanggal akhir">
                        </div>

                        {{-- Filter Status --}}
                        <div class="col-sm-12 col-md-4 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu
                                </option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui
                                </option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-start mt-2">
                            <button type="reset" class="btn btn-secondary me-2" id="reset-button">
                                Reset
                            </button>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#exportModal">
                                Ekspor
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        <div class="table-responsive text-nowrap">
            <table id="pengajuan-izin-table" class="table table-striped table-hover">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama Pengguna</th>
                        <th>Jenis Izin</th>
                        <th>Durasi</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($pengajuan as $izin)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ optional($izin->user)->name ?? 'Tidak Diketahui' }}</td>
                            <td>{{ $izin->jenis_izin }}</td>
                            <td>{{ $izin->durasi }}</td>
                            <td>{{ \Carbon\Carbon::parse($izin->tanggal_mulai)->translatedFormat('d F Y') }}</td>
                            <td>{{ $izin->tanggal_selesai ? \Carbon\Carbon::parse($izin->tanggal_selesai)->translatedFormat('d F Y') : '-' }}
                            </td>
                            <td>{{ $izin->keterangan }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $izin->status == 'menunggu' ? 'warning' : ($izin->status == 'disetujui' ? 'success' : 'danger') }}">
                                    {{ ucfirst($izin->status) }}
                                </span>
                            </td>
                            <td>
                                @if ($izin->status == 'menunggu')
                                    <form action="{{ route('admin.pengajuan_izin.approve', $izin) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                    </form>
                                    <form action="{{ route('admin.pengajuan_izin.reject', $izin) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                    </form>
                                @else
                                    <span>Tindakan selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>


    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Export Pengajuan Izin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <form method="GET" action="{{ route('admin.pengajuan_izin.export') }}">
                        <div class="mb-3">
                            <label for="user" class="form-label">Nama Pengguna:</label>
                            <select name="user" id="user" class="form-control">
                                <option value="">Semua</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Export</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Fungsi untuk reset form filter
            $('#reset-button').on('click', function() {
                // Reset semua nilai input di form
                $('#filter-form')[0].reset();

                // Hapus query parameter dari URL dan reload halaman
                window.location.href = "{{ route('admin.pengajuan_izin.index') }}";
            });

            // Submit otomatis saat filter diubah
            $('#tanggal_mulai, #tanggal_selesai, #status').on('change', function() {
                $('#filter-form').submit();
            });


        });
    </script>



    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- CSS DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

    <!-- JS jQuery dan DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#pengajuan-izin-table').DataTable({
                responsive: true, // Agar tabel responsif pada perangkat mobile
                columnDefs: [{
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
