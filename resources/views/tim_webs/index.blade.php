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

        <a href="{{ route('tim_webs.create') }}" class="btn btn-primary mb-3">Tambah Data Tim Web</a>

        <div class="row mb-4">
            <div class="col-md-3 mb-2">
                <input type="date" name="startDate" class="form-control" placeholder="Tanggal Mulai">
            </div>
            <div class="col-md-3 mb-2">
                <input type="date" name="endDate" class="form-control" placeholder="Tanggal Akhir">
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow">
            <h5 class="card-header text-right">Daftar Tim Web</h5>
            <div class="table-responsive text-nowrap">
                <table id="tim-web-table" class="table">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jumlah Artikel Hari Ini</th>
                            <th>Jumlah Kata Hari Ini</th>
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
                                <td>{{ $tim_web->formatted_tanggal }}</td>
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
                language: {
                    search: "Cari:", // Custom search label
                    lengthMenu: "Tampilkan _MENU_ data per halaman", // Page length options
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data", // Info about data range
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data", // Info when no data is found
                    paginate: {
                        previous: "Sebelumnya", // Custom text for previous button
                        next: "Berikutnya" // Custom text for next button
                    }
                },
                columnDefs: [{
                        className: "text-center",
                        targets: "_all"
                    } // Semua kolom diatur ke text-center
                ]

            });
            $('input[name="startDate"], input[name="endDate"]').on('change', function() {
                var startDate = $('input[name="startDate"]').val();
                var endDate = $('input[name="endDate"]').val();

                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    var dataDate = new Date(data[5]); // Kolom Tanggal (index 5)
                    var normalizedDataDate = new Date(dataDate.toDateString()); // Hanya tanggal

                    // Konversi input menjadi tanggal tanpa waktu
                    var normalizedStartDate = startDate ? new Date(new Date(startDate)
                        .toDateString()) : null;
                    var normalizedEndDate = endDate ? new Date(new Date(endDate).toDateString()) :
                        null;

                    // Filter logika
                    if (
                        (!normalizedStartDate || normalizedDataDate >= normalizedStartDate) &&
                        (!normalizedEndDate || normalizedDataDate <= normalizedEndDate)
                    ) {
                        return true;
                    }
                    return false;
                });

                table.draw();

                // Hapus filter sebelumnya untuk mencegah duplikasi
                $.fn.dataTable.ext.search.pop();
            });
        });
    </script>
@endsection
