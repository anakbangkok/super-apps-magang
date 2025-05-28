@extends('mentor.layouts.app')

@section('title', 'Daftar Aktivitas Harian ')

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

        <div class="card shadow">
            <div class="d-flex justify-content-between align-items-center m-3">
                <h5 class="mb-0">Daftar Aktivitas Harian</h5>

                <!-- Filter Instansi -->
                <div style="width: 250px; margin-right: -69px;">
                    <select id="instansiFilter" class="form-select">
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
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($journals as $journal)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($journal->date)->translatedFormat('d F Y') }}</td>
                                <td>{{ $journal->user->name }}</td>
                                <td>{{ $journal->user->instansi->nama_instansi ?? '' }}</td>
                                <td>{{ $journal->activity }}</td>
                                <td>{{ $journal->start_time }}</td>
                                <td>{{ $journal->end_time }}</td>
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
                        "lengthMenu": "Tampilkan _MENU_ data per halaman",
                        "zeroRecords": "Tidak ada hasil untuk pencarian Anda",
                        "info": "Menampilkan halaman _PAGE_ dari _PAGES_ halaman",
                        "infoEmpty": "Tidak ada data tersedia",
                        "infoFiltered": "(disaring dari _MAX_ total entri)",
                        "search": "Cari:",
                        "paginate": {
                            "first": "Pertama",
                            "last": "Terakhir",
                            "next": "Berikutnya",
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
