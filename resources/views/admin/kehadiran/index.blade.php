@extends('admin.layouts.app')

@section('title', 'Kelola Absen Pengguna')

@section('header')
    Absensi
@endsection

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">


@section('content')

    <body class="font-sans antialiased">
        <div class="container mt-4">
            <h1 class="mb-4">Riwayat Absensi Pengguna</h1>

            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="user-filter" class="form-label">Nama Pengguna:</label>
                    <select id="user-filter" class="form-control select2">
                        <option value="">Semua</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->name }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="min-date" class="form-label">Tanggal Mulai:</label>
                    <input type="date" id="min-date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label for="max-date" class="form-label">Tanggal Akhir:</label>
                    <input type="date" id="max-date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label for="shift-filter" class="form-label">Pilih Shift:</label>
                    <select id="shift-filter" class="form-select">
                        <option value="">Semua</option>
                        <option value="pagi">Pagi</option>
                        <option value="sore">Sore</option>
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4 d-flex align-items-end">
                    <button id="reset-button" class="btn btn-secondary">Reset</button>
                    <button type="button" class="btn btn-success ms-3" data-bs-toggle="modal"
                        data-bs-target="#exportModal">
                        <i class="fas fa-file-export"></i> Export
                    </button>
                </div>
            </div>

            <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exportModalLabel">Export Riwayat Absensi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <form method="GET" action="{{ route('kehadiran.export') }}">
                                <div class="mb-3">
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

            <div class="card shadow">
                <h5 class="card-header text-right">Daftar Kehadiran</h5>
                <div class="table-responsive text-nowrap">
                    <table id="attendance-table" class="table table-striped table-bordered nowrap" style="width:100%">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>Shift</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Lokasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($kehadirans as $kehadiran)
                                <tr class="text-center">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $kehadiran->user->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($kehadiran->date)->translatedFormat('d F Y') }}</td>
                                    <td>{{ ucfirst($kehadiran->shift) }}</td>
                                    <td>
                                        @if ($kehadiran->check_in)
                                            <div class="d-flex justify-content-center align-items-center">
                                                <!-- Tampilkan Waktu Check-in -->
                                                <span class="me-2">{{ $kehadiran->check_in->format('H:i') }}</span>
                                    
                                                <!-- Tampilkan Ikon -->
                                                @if (($kehadiran->shift === 'pagi' && $kehadiran->check_in->format('H:i') > '08:01') || 
                                                     ($kehadiran->shift === 'sore' && $kehadiran->check_in->format('H:i') > '15:01'))
                                                    <div class="d-flex justify-content-center align-items-center bg-danger text-white rounded-circle" style="width: 20px; height: 20px; font-size: 12px;">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                    </div>
                                                @else
                                                    <div class="d-flex justify-content-center align-items-center bg-success text-white rounded-circle" style="width: 20px; height: 20px; font-size: 12px;">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    
                                    <td>{{ $kehadiran->check_out ? $kehadiran->check_out->format('H:i') : '-' }}</td>
                                    <td>
                                        @if ($kehadiran->location === 'kantor')
                                            <span class="badge bg-success">Kantor</span>
                                        @else
                                            <span class="badge bg-danger">Luar Kantor</span>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Tombol Hapus -->
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteAttendanceModal{{ $kehadiran->id }}" title="Hapus">Hapus</button>
            
                                        <!-- Modal Konfirmasi Penghapusan -->
                                        <div class="modal fade" id="confirmDeleteAttendanceModal{{ $kehadiran->id }}" tabindex="-1" aria-labelledby="confirmDeleteAttendanceModalLabel{{ $kehadiran->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content shadow">
                                                    <div class="modal-header" style="background-color: #f8d7da; color: #721c24;">
                                                        <h5 class="modal-title" id="confirmDeleteAttendanceModalLabel{{ $kehadiran->id }}">Konfirmasi Penghapusan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="text-muted">Setelah Anda hapus, data akan hilang secara permanen.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="{{ route('kehadirans.destroy', $kehadiran->id) }}" method="POST">
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
                </div>
            </div>
            

        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

        <script>
            $(document).ready(function() {
                var table = $('#attendance-table').DataTable({
                    "paging": true,
                    "searching": true,
                    "order": [
                        [2, 'desc']
                    ], // Sort by date by default
                    "responsive": true,
                    "processing": true,
                    "serverSide": false,
                    "columnDefs": [{
                        "targets": 0,
                        "orderable": false,
                    }],
                    "drawCallback": function(settings) {
                        var api = this.api();
                        api.column(0, {
                            page: 'current'
                        }).nodes().each(function(cell, i) {
                            cell.innerHTML = i + 1;
                        });
                    }
                });

                var minDate, maxDate;

                $('#min-date, #max-date').on('change', function() {
                    minDate = $('#min-date').val() ? new Date($('#min-date').val() + 'T00:00:00') : null;
                    maxDate = $('#max-date').val() ? new Date($('#max-date').val() + 'T23:59:59') : null;
                    table.draw();
                });

                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    var dateColumn = data[2];
                    var rowDate = new Date(dateColumn);

                    if (
                        (!minDate || rowDate >= minDate) &&
                        (!maxDate || rowDate <= maxDate)
                    ) {
                        return true;
                    }
                    return false;
                });

                $('#shift-filter').on('change', function() {
                    var selectedShift = $(this).val();
                    table.column(3).search(selectedShift).draw();
                });

                $('#user-filter').on('change', function() {
                    var selectedUser = $(this).val();
                    table.column(1).search(selectedUser).draw();
                });

                $('#user-filter').select2({
                    placeholder: "Cari Nama Pengguna",
                    allowClear: true
                });

                $('#reset-button').on('click', function() {
                    $('#min-date').val('');
                    $('#max-date').val('');
                    $('#shift-filter').val('');
                    $('#user-filter').val(null).trigger('change');
                    minDate = null;
                    maxDate = null;
                    table.columns().search('').draw();
                });
            });
        </script>
    </body>

@endsection
