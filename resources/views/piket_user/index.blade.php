@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

@section('content')
<div class="card shadow ms-4 me-4">
    <h5 class="card-header text-right">Daftar Jadwal Piket</h5>
    <div class="table-responsive text-nowrap">
        <table id="jadwal-piket-table" class="table table-hover">
            <thead class="table-light">
                <tr class="text-center">
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Tanggal</th>
                    <th style="width: 40%;">Instansi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwalPikets as $jadwal)
                    <tr class="text-center align-middle">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }}</td>
                        <td>
                            @if ($jadwal->instansis->isNotEmpty())
                                <div class="d-flex flex-wrap justify-content-center">
                                    @foreach ($jadwal->instansis as $instansi)
                                        <span class="badge bg-primary me-1 mb-1 px-2 py-1">
                                            {{ $instansi->nama_instansi }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-danger">Tidak ada instansi</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">Tidak ada jadwal piket tersedia</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
