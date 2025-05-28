@extends('layouts.app')

@section('title', 'Daftar Tim Web')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

@section('content')
    <div class="container">
        <h2>Data Tim Web</h2>
        <div class="d-flex justify-content-between">
            <div class="alert alert-info flex-grow-1 me-2">
                <h5>Data Hari Ini</h5>
                <strong>Jumlah Artikel Hari Ini:</strong> {{ $jumlahArtikel }} <br>
                <strong>Jumlah Kata Hari Ini:</strong> {{ $jumlahKata }}
            </div>
            <div class="alert alert-success flex-grow-1 ms-2">
                <h5>Total Keseluruhan</h5>
                <strong>Total Jumlah Artikel:</strong> {{ $totalJumlahArtikel }} <br>
                <strong>Total Jumlah Kata:</strong> {{ $totalJumlahKata }}
            </div>
        </div>

        {{-- <a href="{{ route('tim_webs.create') }}" class="btn btn-primary mb-3">Tambah Data Tim Web</a> --}}

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow">

            <div class="container-fluid mt-4 mb-4 px-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">

                    <a href="{{ route('tim_webs.create') }}" class="btn btn-primary">Tambah Data Tim Web</a>

                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <div style="width: 185px;">
                            <label for="min-date" class="form-label">Tanggal Mulai:</label>
                            <input type="date" id="min-date" class="form-control">
                        </div>
                        <div style="width: 185px;">
                            <label for="max-date" class="form-label">Tanggal Akhir:</label>
                            <input type="date" id="max-date" class="form-control">
                        </div>
                    </div>
                </div>
            </div>



            <div class="table-responsive text-nowrap">
                <table id="tim-web-table" class="table">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jumlah Artikel</th>
                            <th>Jumlah Kata</th>
                            <th>Keterangan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($tim_webs as $tim_web)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $tim_web->user->name }}</td>
                                <td>{{ $tim_web->jumlah_artikel }}</td>
                                <td>{{ $tim_web->jumlah_kata }}</td>
                                <td>{{ $tim_web->keterangan }}</td>
                                <td data-order="{{ \Carbon\Carbon::parse($tim_web->tanggal)->format('Y-m-d') }}">
                                    {{ \Carbon\Carbon::parse($tim_web->tanggal)->translatedFormat('d F Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('tim_webs.edit', $tim_web->id) }}" class="btn btn-warning btn-sm"
                                        title="Edit">Edit</a>
                                    <form id="deleteTimWebForm" action="{{ route('tim_webs.destroy', $tim_web->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#confirmDeleteModal">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Penghapusan -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-header" style="background-color: #f8d7da; color: #721c24;">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Penghapusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        onclick="document.getElementById('deleteTimWebForm').submit();">Ya, Hapus</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#tim-web-table').DataTable({
                responsive: true, // Enable responsive table
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ada hasil untuk pencarian Anda",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_ halaman",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(disaring dari _MAX_ total entri)",
                    "search": "Cari:",
                    "paginate": {
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    }
                },
                columnDefs: [{
                        className: "text-center",
                        targets: "_all"
                    } // Semua kolom diatur ke text-center
                ]

            });

            // Event listener hanya untuk filter tanggal
            $('#min-date, #max-date').on('change input', function() {
                table.draw(); // Redraw the table when the filter is applied
            });

            // Custom search function hanya untuk filter tanggal
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var tableRow = $(table.row(dataIndex).node());
                var date = tableRow.find('td:eq(5)').data(
                    'order'); // Ganti indeks sesuai posisi kolom tanggal

                var minDate = $('#min-date').val();
                var maxDate = $('#max-date').val();

                console.log("min:", minDate, "max:", maxDate, "date:", date);

                if (
                    (minDate === '' || date >= minDate) &&
                    (maxDate === '' || date <= maxDate)
                ) {
                    return true;
                }
                return false;
            });

            // Inisialisasi DataTable setelah filter
            var table = $('#tim-web-table').DataTable();


        });
    </script>
@endsection
