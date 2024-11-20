@extends('admin.layouts.app')

@section('title', 'Daftar Aktivitas Harian')

    <link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

@section('content')
    <div class="container my-5">
        <h1>Aktivitas Harian Peserta Magang</h1>

        <!-- Tombol Ekspor dengan jarak bawah -->
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
                        <form action="{{ route('journals.export') }}" method="GET">
                            @csrf
                            <!-- Filter berdasarkan Tanggal -->
                            <div class="mb-3">
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
                                    <option value="">Pilih Nama Peserta</option>
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
            <h5 class="card-header text-right">Daftar Aktivitas Harian</h5>
            <div class="table-responsive text-nowrap">
                <table id="journalsTable" class="table table-striped">
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
                                    <div class="d-flex justify-content-center">
                                        <form id="deleteJournalForm{{ $journal->id }}" action="{{ route('journals.admin.destroy', $journal->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $journal->id }}">Hapus</button>
                                        </form>
                                        <!-- Modal Konfirmasi Penghapusan -->
                                        <div class="modal fade" id="confirmDeleteModal{{ $journal->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel{{ $journal->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content shadow">
                                                    <div class="modal-header" style="background-color: #f8d7da; color: #721c24;">
                                                        <h5 class="modal-title" id="confirmDeleteModalLabel{{ $journal->id }}">Konfirmasi Penghapusan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus data ini?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteJournalForm{{ $journal->id }}').submit();">Ya, Hapus</button>
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
        
        

@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
@endsection


@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#journalsTable').DataTable({
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
                "responsive": true, // Ensures the table is responsive
                "pagingType": "full_numbers", // Pagination controls with numbers
                "autoWidth": false, // Prevent column resizing issues
                "order": [[1, 'desc']] // Order by the first column (date)
            });
        });
    </script>
@endsection


