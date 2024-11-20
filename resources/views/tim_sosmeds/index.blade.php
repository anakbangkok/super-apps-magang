@extends('layouts.app')

@section('title', 'Daftar Tim Sosmed')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

@section('content')
    <div class="container">
        <h2>Data Tim Sosmed</h2>

        <!-- Tombol tambah data -->
        <a href="{{ route('tim_sosmeds.create') }}" class="btn btn-primary mb-3">Tambah Data Tim Sosmed</a>

        <!-- Filter Tanggal -->
        <div class="row mb-4">
            <div class="col-md-3 mb-2">
                <input type="date" name="startDate" class="form-control" placeholder="Tanggal Mulai">
            </div>
            <div class="col-md-3 mb-2">
                <input type="date" name="endDate" class="form-control" placeholder="Tanggal Akhir">
            </div>
        </div>

        <!-- Notifikasi sukses -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Tabel Data -->
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
                                <td>{{ $tim_sosmed->formatted_tanggal }}</td>
                                <td>
                                    <a href="{{ route('tim_sosmeds.edit', $tim_sosmed->id) }}"
                                        class="btn btn-warning btn-sm" title="Edit">Edit</a>
                                    <form id="deleteTimSosmedForm{{ $tim_sosmed->id }}"
                                        action="{{ route('tim_sosmeds.destroy', $tim_sosmed->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#confirmDeleteSosmedModal{{ $tim_sosmed->id }}">Hapus</button>
                                    </form>

                                    <!-- Modal Konfirmasi Penghapusan -->
                                    <div class="modal fade" id="confirmDeleteSosmedModal{{ $tim_sosmed->id }}"
                                        tabindex="-1" aria-labelledby="confirmDeleteSosmedModalLabel{{ $tim_sosmed->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content shadow">
                                                <div class="modal-header"
                                                    style="background-color: #f8d7da; color: #721c24;">
                                                    <h5 class="modal-title"
                                                        id="confirmDeleteSosmedModalLabel{{ $tim_sosmed->id }}">Konfirmasi
                                                        Penghapusan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus data ini?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="document.getElementById('deleteTimSosmedForm{{ $tim_sosmed->id }}').submit();">Ya,
                                                        Hapus</button>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
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

        <!-- Script -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                var table = $('#tim-sosmed-table').DataTable({
                    responsive: true,
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                        paginate: {
                            previous: "Sebelumnya",
                            next: "Berikutnya"
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
                        var dataDate = new Date(data[4]); // Kolom Tanggal (index 4)
                        var normalizedDataDate = new Date(dataDate.toDateString());

                        var normalizedStartDate = startDate ? new Date(new Date(startDate)
                        .toDateString()) : null;
                        var normalizedEndDate = endDate ? new Date(new Date(endDate).toDateString()) :
                            null;

                        if (
                            (!normalizedStartDate || normalizedDataDate >= normalizedStartDate) &&
                            (!normalizedEndDate || normalizedDataDate <= normalizedEndDate)
                        ) {
                            return true;
                        }
                        return false;
                    });

                    table.draw();
                    $.fn.dataTable.ext.search.pop();
                });
            });
        </script>
    @endsection
