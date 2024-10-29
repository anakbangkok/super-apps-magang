@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <h1 class="mb-4">Daftar Aktivitas Harian</h1>

        <a href="{{ route('journals.create') }}" class="btn btn-primary mb-3">Tambah Aktivitas</a>

        <div class="table-responsive">
            <table id="journalsTable" class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($journals as $journal)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($journal->date)->format('d-m-Y') }}</td>
                            <td>{{ $journal->name }}</td>
                            <td>{{ $journal->start_time }}</td>
                            <td>{{ $journal->end_time }}</td>
                            <td>{{ $journal->activity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#journalsTable').DataTable({
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ entri per halaman",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total entri)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "pagingType": "simple_numbers",
                "order": [[0, "desc"], [1, "asc"]]
            });
        });
    </script>
@endsection
