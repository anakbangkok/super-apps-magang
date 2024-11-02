@extends('layouts.app')

@section('title', 'Absensi')

@section('header')
    Absensi
@endsection

@section('content')
    @yield('scripts')
    <div class="container my-5 mx-auto">
        <h1 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Absensi</h1>

        <!-- Menampilkan Pesan -->
        @if (session('success'))
            <div class="alert alert-success show" id="successMessage">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger show" id="errorMessage">
                {{ session('error') }}
            </div>
        @endif

        @if (session('message'))
            <div class="alert alert-info show" id="infoMessage">
                {{ session('message') }}
            </div>
        @endif

        <form action="{{ route('kehadirans.checkin') }}" method="POST" class="mb-4 p-4 border rounded shadow-sm"
            style="background-color: #f9f9f9;">
            @csrf
            <div class="row">
                <div class="user-name" style="font-size: 1.2rem; color: #566a7f; margin-bottom: 1rem;">
                    <strong>Nama:</strong> {{ Auth::user()->name }}
                </div>
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

            @if (!$hasCheckedIn)
                <button type="submit" class="btn btn-primary w-100 mt-2">Absen Masuk</button>
            @else
                <button type="button" class="btn btn-secondary w-100 mt-2" disabled>Anda telah absen hari ini</button>
            @endif
        </form>

        <hr>
        <div class="card shadow">
            <h5 class="card-header text-right">Riwayat Absen</h5>
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
                                <td>{{ $kehadiran->date->translatedFormat('d F Y') }}</td>
                                <td>{{ $kehadiran->shift }}</td>
                                <td>{{ $kehadiran->check_in ? $kehadiran->check_in->format('H:i') : '-' }}</td>
                                <td>{{ $kehadiran->check_out ? $kehadiran->check_out->format('H:i') : '-' }}</td>
                                <td class="text-center">
                                    @if ($kehadiran->location === 'kantor')
                                        <span class="badge bg-success">Kantor</span>
                                    @else
                                        <span class="badge bg-danger">Luar Kantor</span>
                                    @endif
                                </td>
                                <td>
                                    @if (!$kehadiran->check_out)
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#checkoutModal{{ $kehadiran->id }}">Absen Pulang</button>
                                    @else
                                        <span class="badge bg-success">Sudah Pulang</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Modal untuk Absen Pulang -->
                            <div class="modal fade" id="checkoutModal{{ $kehadiran->id }}" tabindex="-1"
                                aria-labelledby="checkoutModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="checkoutModalLabel">Absen Pulang</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('kehadirans.checkout', $kehadiran->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <input type="text" name="location" value="{{ $kehadiran->location }}"
                                                    class="form-control" readonly required>
                                                <button type="submit" class="btn btn-primary mt-2">Konfirmasi Absen
                                                    Pulang</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <style>
            .alert {
                opacity: 0;
                transition: opacity 0.5s ease-in-out;
            }

            .alert.show {
                opacity: 1;
            }
        </style>

        <script>
            // Koordinat target lokasi baru
            const TARGET_LAT = -7.845969;
            const TARGET_LNG = 110.362230;

            // Radius dalam meter
            const RADIUS = 1000;

            // Fungsi untuk menghitung jarak menggunakan rumus Haversine
            function calculateDistance(lat1, lon1, lat2, lon2) {
                const R = 6371000;
                const dLat = toRad(lat2 - lat1);
                const dLon = toRad(lon2 - lon1);
                const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c;
            }

            // Konversi derajat ke radian
            function toRad(value) {
                return value * Math.PI / 180;
            }

            // Dapatkan lokasi pengguna dan cek apakah dekat dengan target
            function getLocationWithCheck(inputId) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;
                        const distance = calculateDistance(userLat, userLng, TARGET_LAT, TARGET_LNG);

                        console.log(`Jarak ke lokasi target: ${distance.toFixed(2)} meter`);

                        if (distance <= RADIUS) {
                            document.getElementById(inputId).value = 'kantor';
                        } else {
                            document.getElementById(inputId).value = 'luar kantor';
                            alert('Anda tidak berada di sekitar lokasi target.');
                        }
                    }, function(error) {
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                alert('Izin lokasi ditolak. Silakan aktifkan izin lokasi di pengaturan perangkat.');
                                break;
                            case error.POSITION_UNAVAILABLE:
                                alert('Lokasi tidak tersedia. Silakan coba lagi.');
                                break;
                            case error.TIMEOUT:
                                alert('Permintaan lokasi telah timeout. Silakan coba lagi.');
                                break;
                            case error.UNKNOWN_ERROR:
                                alert('Terjadi kesalahan tidak dikenal. Silakan coba lagi.');
                                break;
                        }
                    });
                } else {
                    alert('Geolocation tidak didukung di browser ini.');
                }
            }

            // Panggil fungsi saat halaman dimuat
            window.onload = function() {
                getLocationWithCheck('location');
            };

            // Menghilangkan pesan setelah 5 detik
            setTimeout(function() {
                const successMessage = document.getElementById('successMessage');
                const errorMessage = document.getElementById('errorMessage');
                const infoMessage = document.getElementById('infoMessage');
                if (successMessage) successMessage.classList.remove('show');
                if (errorMessage) errorMessage.classList.remove('show');
                if (infoMessage) infoMessage.classList.remove('show');
            }, 5000);

            // Mendapatkan waktu saat ini
            const now = new Date();
            const hours = now.getHours();
            const minutes = now.getMinutes();

            // Menghitung total menit dari jam saat ini
            const totalMinutes = hours * 60 + minutes;

            // Membandingkan dengan waktu yang ditentukan
            const pagiStart = 7 * 60 + 55; // 07:55
            const pagiEnd = 14 * 60 + 45; // 14:45
            const soreStart = 14 * 60 + 50; // 14:50
            const soreEnd = 21 * 60; // 21:00

            // Mengatur nilai pada select berdasarkan waktu
            const shiftSelect = document.getElementById('shift');
            if (totalMinutes >= pagiStart && totalMinutes <= pagiEnd) {
                shiftSelect.value = 'pagi';
            } else if (totalMinutes >= soreStart && totalMinutes <= soreEnd) {
                shiftSelect.value = 'sore';
            }
        </script>
    </div>
@endsection
