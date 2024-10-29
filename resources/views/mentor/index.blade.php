{{-- resources/views/mentors/index.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="container my-5 mx-auto">
    <h1 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Daftar Mentor</h1>
    <a href="{{ route('mentors.create') }}" class="btn btn-primary mb-3">Tambah Mentor</a>
    
    <div class="card shadow">
        <h5 class="card-header text-right">Mentor Terdaftar</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>NIK</th>
                        <th>Jabatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($mentors as $mentor)
                        <tr>
                            <td class="text-center">{{ $mentor->id }}</td>
                            <td>{{ $mentor->name }}</td>
                            <td>{{ $mentor->email }}</td>
                            <td>{{ $mentor->nik }}</td>
                            <td>{{ $mentor->jabatan }}</td>
                            <td class="text-center">
                                <a href="{{ route('mentors.edit', $mentor->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('mentors.destroy', $mentor->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
