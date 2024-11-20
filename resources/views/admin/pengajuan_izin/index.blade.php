@extends('admin.layouts.app')

@section('title', 'Daftar Pengajuan Izin')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

@section('content')
    <div class="container mt-4">
        <h2>Kelola Pengajuan Izin</h2>

        <div class="card shadow mt-4">
            <form method="GET" action="{{ route('admin.pengajuan_izin.index') }}" class="mb-4 mt-4" id="filter-form">
                <h5 style="margin-left:25px">Filter Data</h5>
                <div class="container row mt-4">
                    {{-- Filter Status dan Tanggal --}}
                    <div class="col-sm-12 col-md-4 mb-3">
                        <label for="tanggal_mulai">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control"
                            value="{{ request('tanggal_mulai') }}">
                    </div>
                    <div class="col-sm-12 col-md-4 mb-3">
                        <label for="tanggal_selesai">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control"
                            value="{{ request('tanggal_selesai') }}">
                    </div>
                    <div class="col-sm-12 col-md-2 mb-3">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui
                            </option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-2 mb-4 mt-4 d-flex justify-content-between">
                        <button type="reset" class="btn btn-secondary d-flex align-items-center" id="reset-button">Reset</button>
                        <button type="button" class="btn btn-success d-flex align-items-center ms-3" data-bs-toggle="modal" data-bs-target="#exportModal">Export</button>
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
                                <td>{{ $izin->tanggal_selesai ? \Carbon\Carbon::parse($izin->tanggal_selesai)->translatedFormat('d F Y') : '-' }}</td>
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
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada pengajuan izin ditemukan</td>
                            </tr>
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
                            {{-- <div class="mb-3">
                                <label for="min_date" class="form-label">Tanggal Mulai:</label>
                                <input type="date" id="min_date" name="min_date" class="form-control"
                                    value="{{ old('min_date') }}">
                            </div>
                            <div class="mb-3">
                                <label for="max_date" class="form-label">Tanggal Akhir:</label>
                                <input type="date" id="max_date" name="max_date" class="form-control"
                                    value="{{ old('max_date') }}">
                            </div>
                            <div class="mb-3">
                                <label for="user" class="form-label">Nama Pengguna:</label>
                                <select name="user" id="user" class="form-control">
                                    <option value="">Semua</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="shift" class="form-label">Shift:</label>
                                <select name="shift" id="shift" class="form-control">
                                    <option value="">Semua</option>
                                    <option value="pagi">Pagi</option>
                                    <option value="sore">Sore</option>
                                </select>
                             </div> --}}
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
            $(document).ready(function () {
                // Fungsi untuk reset form filter
                $('#reset-button').on('click', function () {
                    // Reset semua nilai input di form
                    $('#filter-form')[0].reset();
        
                    // Hapus query parameter dari URL dan reload halaman
                    window.location.href = "{{ route('admin.pengajuan_izin.index') }}";
                });
        
                // Submit otomatis saat filter diubah
                $('#tanggal_mulai, #tanggal_selesai, #status').on('change', function () {
                    $('#filter-form').submit();
                });
        
                // Inisialisasi DataTables
                $('#pengajuan-izin-table').DataTable({
                    responsive: true,
                    autoWidth: false,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
                    }
                });
            });
        </script>
        
        

        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

    @endsection
