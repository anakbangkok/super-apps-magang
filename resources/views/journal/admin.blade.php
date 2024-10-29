@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Jurnal Admin</h1>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Kegiatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($journals as $journal)
            <tr>
                <td>{{ $journal->id }}</td>
                <td>{{ $journal->date }}</td>
                <td>{{ $journal->name }}</td>
                <td>{{ $journal->start_time }}</td>
                <td>{{ $journal->end_time }}</td>
                <td>{{ $journal->activity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
