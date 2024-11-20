@extends('admin.layouts.app')

@section('title', 'Daftar Tim Sosmed')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

@section('content')
<div class="container">
    <h2>Data Tim Sosmed</h2>

    <!-- Form pencarian dan filter berdasarkan nama dan tanggal -->
    <div class="row mb-4">
        <div class="col-md-4">
            <label for="min-date" class="form-label">Tanggal Mulai:</label>
            <input type="date" id="min-date" class="form-control">
        </div>
        <div class="col-md-4">
            <label for="max-date" class="form-label">Tanggal Akhir:</label>
            <input type="date" id="max-date" class="form-control">
        </div>
        <div class="col-md-4">
            <label for="nama-filter" class="form-label">Filter Nama:</label>
            <select id="nama-filter" class="form-control select2" placeholder="Cari Nama...">
                <option value="">Pilih Nama</option>
                @foreach ($tim_sosmeds as $tim_sosmed)
                    <option value="{{ $tim_sosmed->user->name }}">{{ $tim_sosmed->user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Notifikasi sukses -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <h5 class="card-header text-right">Daftar Tim Sosmed</h5>
        <div class="table-responsive text-nowrap">
            <table id="tim-sosmed-table" class="table">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama</th>
                        <th>Pekerjaan Hari Ini</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tim_sosmeds as $tim_sosmed)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $tim_sosmed->user->name }}</td>
                            <td>{{ $tim_sosmed->pekerjaan_hari_ini }}</td>
                            <td>{{ $tim_sosmed->keterangan }}</td>
                            <td>{{ \Carbon\Carbon::parse($tim_sosmed->tanggal)->translatedFormat('d F Y') }}</td>
                            <td>
                                
                                <a href="{{ route('tim_sosmed.edit', $tim_sosmed->id) }}" class="btn btn-warning btn-sm button-action me-2" title="Edit">Edit</a>
                                <form id="deleteTimSosmedForm{{ $tim_sosmed->id }}" action="{{ route('tim_sosmed.destroy', $tim_sosmed->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm button-action" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $tim_sosmed->id }}">Hapus</button>
                                </form>
    
                                <!-- Modal Konfirmasi Penghapusan -->
                                <div class="modal fade" id="confirmDeleteModal{{ $tim_sosmed->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel{{ $tim_sosmed->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content shadow">
                                            <div class="modal-header" style="background-color: #f8d7da; color: #721c24;">
                                                <h5 class="modal-title" id="confirmDeleteModalLabel{{ $tim_sosmed->id }}">Konfirmasi Penghapusan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus data ini?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteTimSosmedForm{{ $tim_sosmed->id }}').submit();">Ya, Hapus</button>
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
    

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.3.0/js/dataTables.dateTime.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />

<script>
    $(document).ready(function () {
        // Initialize Select2 for the Nama filter
        $('#nama-filter').select2({
            placeholder: 'Pilih Nama...',
            allowClear: true
        });

        var table = $('#tim-sosmed-table').DataTable({
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
        columnDefs: [
            { className: "text-center", targets: "_all" } // Semua kolom diatur ke text-center
        ]
    });

        // Event handler for changes in the date filters and name filter
        $('#min-date, #max-date, #nama-filter').on('change input', function () {
            table.draw(); // Redraw the table when the filter is applied
        });

        // Custom search function for filtering based on date and name
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            var date = data[4]; // Column date (index starts from 0)
            var nama = data[1].toLowerCase(); // Column name (index starts from 0)

            // Parsing date from table (assumed format: d F Y)
            var tableDate = moment(date, 'D MMMM YYYY').format('YYYY-MM-DD');

            var minDate = $('#min-date').val();
            var maxDate = $('#max-date').val();
            var namaFilter = $('#nama-filter').val().toLowerCase();

            // Filter based on date and name
            if (
                (minDate === '' || tableDate >= minDate) &&
                (maxDate === '' || tableDate <= maxDate) &&
                (namaFilter === '' || nama.includes(namaFilter))
            ) {
                return true;
            }
            return false;
        });
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
