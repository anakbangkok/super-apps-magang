@extends('admin.layouts.app')

@section('title', 'Daftar Penugasan')

@section('content')
<div class="container my-5 mx-auto">
    <h2 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Daftar Penugasan</h2>
    <a href="{{ route('penugasan.create') }}" class="btn btn-primary mb-3">Tambah Penugasan</a>

    @if (session('success'))
        <div class="alert alert-success" style="background-color: #d4edda; color: #155724;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow">
        <h5 class="card-header text-right">Penugasan Terdaftar</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Unit Bisnis</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($penugasans as $penugasan)
                    <tr>
                        <td class="text-center">{{ $penugasan->id }}</td>
                        <td>{{ $penugasan->nama_unit_bisnis }}</td>
                        <td class="text-center">
                            <a href="{{ route('penugasan.edit', $penugasan) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('penugasan.destroy', $penugasan) }}" method="POST" class="d-inline">
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
