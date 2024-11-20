@extends('layouts.app')

@section('title', 'Aktifitas Harian')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

@section('content')
    <div class="container my-5">
        <h1 class="mb-4">Daftar Aktivitas Harian</h1>

        <a href="{{ route('journals.create') }}" class="btn btn-primary mb-3">Tambah Aktivitas</a>

        <div class="row mb-4">
            <div class="col-md-4">
                <label for="min-date" class="form-label">Tanggal Mulai:</label>
                <input type="date" id="min-date" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="max-date" class="form-label">Tanggal Akhir:</label>
                <input type="date" id="max-date" class="form-control">
            </div>
        </div>

        <!-- Notifikasi sukses -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow">
            <h5 class="card-header text-right">Daftar Aktivitas Harian</h5>
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
                                <td>{{ $journal->start_time }}</td>
                                <td>{{ $journal->end_time }}</td>
                                <td>{{ $journal->activity }}</td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('journals.edit', $journal->id) }}" class="btn btn-warning btn-sm mx-1" title="Edit">Edit</a>
        
                                        <!-- Tombol Hapus -->
                                        <button type="button" class="btn btn-danger btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#confirmDeleteJournalModal{{ $journal->id }}" title="Hapus">Hapus</button>
        
                                        <!-- Modal Konfirmasi Penghapusan -->
                                        <div class="modal fade" id="confirmDeleteJournalModal{{ $journal->id }}" tabindex="-1" aria-labelledby="confirmDeleteJournalModalLabel{{ $journal->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content shadow">
                                                    <div class="modal-header" style="background-color: #f8d7da; color: #721c24;">
                                                        <h5 class="modal-title" id="confirmDeleteJournalModalLabel{{ $journal->id }}">Konfirmasi Penghapusan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus aktivitas ini?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="{{ route('journals.destroy', $journal->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                        </form>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
        $(document).ready(function () {
            var table = $('#journalstable').DataTable({
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
                    
                });
    
            var minDate, maxDate;
    
            // Menangani perubahan input tanggal
            $('#min-date, #max-date').on('change', function () {
                minDate = $('#min-date').val();
                maxDate = $('#max-date').val();
                table.draw();
            });
    
            // Menambahkan custom filter untuk DataTable
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                var date = data[1]; // Kolom tanggal (index dimulai dari 0)
    
                // Mengonversi tanggal di tabel ke format 'YYYY-MM-DD'
                var dateFormatted = moment(date, 'DD MMMM YYYY').format('YYYY-MM-DD');
    
                if (
                    (minDate === '' || dateFormatted >= minDate) &&
                    (maxDate === '' || dateFormatted <= maxDate)
                ) {
                    return true;
                }
                return false;
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
                allowOutsideClick: false,  // Mencegah klik di luar pop-up
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
