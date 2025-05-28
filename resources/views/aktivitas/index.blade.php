@extends('layouts.app')

@section('title', 'Aktifitas Harian')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


@section('content')
    <div class="container my-5">
        <h1 class="mb-4">Daftar Aktivitas Harian</h1>

        <a href="{{ route('aktivitas.create') }}" class="btn btn-primary mb-3">Tambah Aktivitas</a>
        <a href="#" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#importModal">Import Aktivitas</a>
        
        <!-- Notifikasi -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        

        <!-- Modal Import -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content shadow">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Aktivitas Harian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('journals.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <p class="text-muted">
                                Pastikan file Anda sesuai format berikut:
                            <ul>
                                <li>Kolom 1: <strong>Tanggal</strong> (format: dd-mm-yyyy)</li>
                                <li>Kolom 2: <strong>Nama</strong> (Pastikan Sesuai Dengan Nama pada Akun Anda)</li>
                                <li>Kolom 3: <strong>Jam Mulai</strong> (format: HH:mm Contoh 15:00)</li>
                                <li>Kolom 4: <strong>Jam Selesai</strong> (format: HH:mm Contoh 21:00)</li>
                                <li>Kolom 5: <strong>Aktivitas</strong></li>
                            </ul>
                            </p>
                            <a href="{{ route('journals.example') }}" class="btn btn-link">Unduh Contoh Format</a>
                            <div class="mb-3">
                                <label for="file" class="form-label">Pilih File Excel</label>
                                <input type="file" name="file" id="file" class="form-control"
                                    accept=".xlsx,.xls,.csv" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Import</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

            <div class="card shadow">

                <div class="d-flex justify-content-between align-items-center mt-4 me-3 mb-2">
                    <h4 class="card-header">Daftar Aktivitas Harian</h4>
                    <form action="{{ route('aktivitas.index') }}" method="GET" class="d-flex align-items-center">
                        <div class="me-3">
                            <label for="start_date" class="form-label mb-0">Tanggal Mulai</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" style="max-width: 200px;" value="{{ request()->get('start_date') }}">
                        </div>
                        <div class="me-3">
                            <label for="end_date" class="form-label mb-0">Tanggal Akhir</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" style="max-width: 200px;" value="{{ request()->get('end_date') }}">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3 me-2">Filter</button>
                        <a href="{{ route('aktivitas.index') }}" class="btn btn-secondary mt-3">Reset</a>
                    </form>
                </div>

                <div class="table-responsive text-nowrap">
                    <table id="journalsTable" class="table">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Aktivitas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($journals as $journal)
                                <tr class="text-center">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($journal->date)->translatedFormat('d F Y') }}</td>
                                    <td>{{ $journal->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($journal->start_time)->format('H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($journal->end_time)->format('H:i') }}</td>                                    
                                    <td>{{ $journal->activity }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('aktivitas.edit', $journal->id) }}"
                                                class="btn btn-warning btn-sm mx-1" title="Edit">Edit</a>

                                            <!-- Tombol Hapus -->
                                            <button type="button" class="btn btn-danger btn-sm mx-1" data-bs-toggle="modal"
                                                data-bs-target="#confirmDeleteJournalModal{{ $journal->id }}"
                                                title="Hapus">Hapus</button>

                                            <!-- Modal Konfirmasi Penghapusan -->
                                            <div class="modal fade" id="confirmDeleteJournalModal{{ $journal->id }}"
                                                tabindex="-1"
                                                aria-labelledby="confirmDeleteJournalModalLabel{{ $journal->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content shadow">
                                                        <div class="modal-header"
                                                            style="background-color: #f8d7da; color: #721c24;">
                                                            <h5 class="modal-title"
                                                                id="confirmDeleteJournalModalLabel{{ $journal->id }}">
                                                                Konfirmasi Penghapusan</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Tutup"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menghapus aktivitas ini?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form action="{{ route('aktivitas.destroy', $journal->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Ya,
                                                                    Hapus</button>
                                                            </form>
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


        <!-- Menambahkan link moment.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function() {
                var table = $('#journalsTable').DataTable({
                    responsive: true, // Enable responsive table
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

                });
            });
        </script>


        <script>
            function confirmDelete(url) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data ini akan dihapus secara permanen!",
                    customClass: {
                        popup: 'swal2-front-popup'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#secondary',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.action = url;
                        form.method = 'POST';
                        form.innerHTML = `@csrf @method('DELETE')`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
        </script>
    @endsection
