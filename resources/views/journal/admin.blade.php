@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Aktivitas Harian Peserta Magang</h1>

    <table class="table">
        <thead>
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
            <td>{{ \Carbon\Carbon::parse($journal->date)->format('d-m-Y') }}</td>
            <td>{{ $journal->user->name }}</td>
            <td>{{ $journal->start_time }}</td>
            <td>{{ $journal->end_time }}</td>
            <td>{{ $journal->activity }}</td>
                <td>
                    <form method="POST" action="{{ route('journal.admin.destroy', $journal->id) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-small btn-sm" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
