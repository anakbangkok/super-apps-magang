@extends('admin.layouts.app')

@section('title', 'Absensi')

@section('header')
    Absensi
@endsection

@section('content')
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Page Content -->
        <div class="container">
            <h1>Riwayat Absensi User</h1>
            <div class="card">
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Pengguna</th>
                                <th>Tanggal</th>
                                <th>Shift</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Lokasi</th>
                                <th>Aksi</th> <!-- Add Action Column -->
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($kehadirans as $kehadiran)
                            <tr>
                                <td>{{ $kehadiran->user->name }}</td>
                                <td>{{ $kehadiran->date }}</td>
                                <td>{{ $kehadiran->shift }}</td>
                                <td>{{ $kehadiran->check_in ?? '-' }}</td>
                                <td>{{ $kehadiran->check_out ?? '-' }}</td>
                                <td>{{ $kehadiran->location }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <form action="{{ route('kehadirans.destroy', $kehadiran->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE') <!-- Use DELETE method -->
                                                <button type="submit" class="dropdown-item" onclick="return confirm('Apakah Anda yakin ingin menghapus?');">
                                                    <i class="bx bx-trash me-1"></i> Hapus
                                                </button>
                                            </form>
                                            <a class="dropdown-item" href="javascript:void(0);">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection
