@extends('admin.layouts.app')

@section('content')
<div class="container my-5">
    <h1>Aktivitas Harian Peserta Magang</h1>

    <div class="table-responsive text-nowrap">
        <table id="journalsTable" class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Kegiatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($journals as $index => $journal)
                    <tr>
                        <td>{{ $index + 1 }}</td> 
                        <td>{{ \Carbon\Carbon::parse($journal->date)->translatedFormat('d F Y') }}</td>
                        <td>{{ $journal->user->name }}</td>
                        <td>{{ $journal->start_time }}</td>
                        <td>{{ $journal->end_time }}</td>
                        <td>{{ $journal->activity }}</td>
                        <td>
                            <form method="POST" action="{{ route('journal.admin.destroy', $journal->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<style>
    /* Tambahkan styling tambahan di sini */
    table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
    }
    th, td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }
    th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
</style>
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
            }
        });
    });
</script>
@endsection
@endsection
