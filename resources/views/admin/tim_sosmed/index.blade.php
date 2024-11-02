@extends('admin.layouts.app')

@section('title', 'Daftar Tim Sosmed')

@section('content')
<div class="container">
    <h2>Data Tim Sosmed</h2>

    <!-- Tombol tambah data -->
    <a href="{{ route('tim_sosmed.create') }}" class="btn btn-primary mb-3">Tambah Data Tim Sosmed</a>
    
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

    <!-- Tabel daftar tim sosmed -->
    <table id="tim-sosmed-table" class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Pekerjaan Hari Ini</th>
                <th>Keterangan</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tim_sosmeds as $tim_sosmed)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $tim_sosmed->pekerjaan_hari_ini }}</td>
                <td>{{ $tim_sosmed->keterangan }}</td>
                <td>{{  \Carbon\Carbon::parse($tim_sosmed->tanggal)->translatedFormat('d F Y')  }}</td>
                <td>
                    <a href="{{ route('tim_sosmed.edit', $tim_sosmed->id) }}" class="btn btn-warning btn-sm" title="Edit">Edit</a>
                    <form action="{{ route('tim_sosmed.destroy', $tim_sosmed->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.3.0/js/dataTables.dateTime.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.3.0/css/dataTables.dateTime.min.css">

<script>
    $(document).ready(function () {
        var table = $('#tim-sosmed-table').DataTable();

        var minDate, maxDate;

        $('#min-date, #max-date').on('change', function () {
            minDate = $('#min-date').val();
            maxDate = $('#max-date').val();
            table.draw();
        });

        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            var date = data[3]; // Kolom tanggal (index dimulai dari 0)
            if (
                (minDate === null || minDate === '' || date >= minDate) &&
                (maxDate === null || maxDate === '' || date <= maxDate)
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
