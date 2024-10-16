@extends('layouts.app')

@section('title', 'Absensi')

@section('header')
    Absensi
@endsection

@section('content')
<div class="container my-5 mx-auto">
    <h1 class="mb-4">Absensi</h1>

    <div class="alert alert-info">
        <strong>Nama:</strong> {{ Auth::user()->name }}
    </div>

    <form action="{{ route('kehadirans.checkin') }}" method="POST" class="mb-4">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="shift" class="form-label">Shift:</label>
                <select name="shift" id="shift" class="form-select" required>
                    <option value="pagi">Pagi</option>
                    <option value="sore">Sore</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="location" class="form-label">Lokasi:</label>
                <input type="text" name="location" id="location" class="form-control" readonly required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-2">Absen Masuk</button>
    </form>

    <hr>
    <div class="card">
        <h5 class="card-header">Riwayat Absen</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Shift</th>
                        <th class="text-center">Masuk</th>
                        <th class="text-center">Pulang</th>
                        <th class="text-center">Lokasi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($kehadirans as $kehadiran)
                    <tr class="text-center">
                        <td>{{ $kehadiran->user->name }}</td>
                        <td>{{ $kehadiran->date }}</td>
                        <td>{{ $kehadiran->shift }}</td>
                        <td>{{ $kehadiran->check_in ? $kehadiran->check_in->format('H:i') : '-' }}</td>
                        <td>{{ $kehadiran->check_out ? $kehadiran->check_out->format('H:i') : '-' }}</td>
                        <td>{{ $kehadiran->location }}</td>
                        <td>
                            @if (!$kehadiran->check_out)
                            <form action="{{ route('kehadirans.checkout', $kehadiran->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="text" name="location" value="{{ $kehadiran->location }}" class="form-control" readonly required style="display: inline; width: auto;">
                                <button type="submit" class="btn btn-danger btn-sm">Absen Pulang</button>
                            </form>
                            @else
                            <span class="badge bg-success">Sudah Pulang</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    
    

<script>
    // Mendapatkan lokasi secara otomatis
    function getLocation(inputId) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                document.getElementById(inputId).value = `${latitude}, ${longitude}`;
            }, function(error) {
                alert('Gagal mendapatkan lokasi. Periksa pengaturan izin lokasi.');
            });
        } else {
            alert('Geolocation tidak didukung di browser ini.');
        }
    }

    // Mendapatkan waktu lokal sesuai WIB
    function getWIBTime() {
        const options = {
            timeZone: 'Asia/Jakarta', // Zona waktu untuk WIB
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false // Format 24 jam
        };
        const now = new Date().toLocaleString('id-ID', options); // Menggunakan format lokal Indonesia
        return now;
    }

    // Panggil fungsi saat halaman dimuat untuk input absen masuk
    window.onload = function() {
        getLocation('location'); // Untuk absen masuk

        // Mengisi input dengan waktu saat ini
        document.getElementById('current-time').value = getWIBTime();
    };

    // Dapatkan lokasi otomatis saat checkout jika tombol checkout ada
    document.addEventListener('DOMContentLoaded', function() {
        const checkoutInput = document.getElementById('checkout-location');
        if (checkoutInput) {
            getLocation('checkout-location'); // Untuk absen pulang
        }
    });
</script>
@endsection
