@extends('admin.layouts.app')

@section('title', 'Daftar Aktivitas Harian')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

<head>
    <style>
        #filterContainer {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 5px !important;
            margin-right: 15px;
        }

        #instansiFilter {
            max-width: 184px;
            max-height: 35px;
            border: 1px solid #AAAAAA;
            border-radius: 4px;
        }
    </style>
</head>

@section('content')
    <div class="container">
        <h1>Aktivitas Harian Peserta Magang</h1>

        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="fas fa-file-export"></i> Ekspor
        </button>

        <!-- Modal Filter -->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">Filter Data untuk Ekspor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('aktivitas.export') }}" method="GET">
                            @csrf
                            <!-- Filter berdasarkan Tanggal -->
                            <div>
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date">
                            </div>
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="end_date" name="end_date">
                            </div>

                            <!-- Filter berdasarkan Nama -->
                            <div class="mb-3">
                                <label for="user_name" class="form-label">Nama Peserta</label>
                                <select class="form-control" id="user_name" name="user_name">
                                    <option value="">Pilih Semua Peserta</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->name }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Ekspor</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="card shadow">
            <h5 class="card-header text-right mt-2">Daftar Aktivitas Harian</h5>

            <!-- Filter Instansi dan Search Field -->
            <div>
                <div id="filterContainer">
                    <select id="instansiFilter" class="form-select custom-select">
                        <option value="">Semua Instansi</option>
                        @foreach ($instansis as $instansi)
                            <option value="{{ $instansi->nama_instansi }}">{{ $instansi->nama_instansi }}</option>
                        @endforeach
                    </select>
                </div>                
            </div>

            <div class="table-responsive text-nowrap">
                <table id="journalsTable" class="table table-striped">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Instansi</th>
                            <th>Aktivitas</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($journals as $journal)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($journal->date)->translatedFormat('d F Y') }}</td>
                                <td>{{ $journal->name }}</td>
                                <td>{{ $journal->user->instansi->nama_instansi ?? '' }}</td>
                                <td>{{ $journal->activity }}</td>
                                <td>{{ $journal->start_time }}</td>
                                <td>{{ $journal->end_time }}</td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <form id="deleteJournalForm{{ $journal->id }}"
                                            action="{{ route('aktivitas.admin.destroy', $journal->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#confirmDeleteModal{{ $journal->id }}">Hapus</button>
                                        </form>
                                        <!-- Modal Konfirmasi Penghapusan -->
                                        <div class="modal fade" id="confirmDeleteModal{{ $journal->id }}" tabindex="-1"
                                            aria-labelledby="confirmDeleteModalLabel{{ $journal->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content shadow">
                                                    <div class="modal-header"
                                                        style="background-color: #f8d7da; color: #721c24;">
                                                        <h5 class="modal-title"
                                                            id="confirmDeleteModalLabel{{ $journal->id }}">
                                                            Konfirmasi Penghapusan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus data ini?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger"
                                                            onclick="document.getElementById('deleteJournalForm{{ $journal->id }}').submit();">Ya,
                                                            Hapus</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                    </div>
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


        <!-- Tambahkan JS DataTables -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>

        <script>
            $(document).ready(function() {
                // Inisialisasi DataTables
                var table = $('#journalsTable').DataTable({
                    "language": {
                        "lengthMenu": "Tampilkan _MENU_ entri",
                        "zeroRecords": "Tidak ditemukan data",
                        "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                        "infoEmpty": "Tidak ada data tersedia",
                        "infoFiltered": "(disaring dari _MAX_ total entri)",
                        "search": "Cari:",
                        "paginate": {
                            "first": "Pertama",
                            "last": "Terakhir",
                            "next": "Selanjutnya",
                            "previous": "Sebelumnya"
                        }
                    },
                    "responsive": true,
                    "pagingType": "simple_numbers",
                    "autoWidth": false,
                });

                // Event handler untuk dropdown filter instansi
                $('#instansiFilter').on('change', function() {
                    var selectedInstansi = $(this).val(); // Ambil nilai terpilih dari dropdown
                    if (selectedInstansi) {
                        // Filter DataTables berdasarkan kolom "Instansi" (indeks kolom ke-3)
                        table.column(3).search('^' + selectedInstansi + '$', true, false).draw();
                    } else {
                        // Hapus filter jika tidak ada instansi yang dipilih
                        table.column(3).search('').draw();
                    }
                });
            });
        </script>
    @endsection
