<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Absensi') }}
        </h2>
    </x-slot>
    <div class="container my-5 mx-3">
        <h1 class="mb-4">Absensi</h1>
    
        <div class="alert alert-info">
            <strong>Nama:</strong> {{ Auth::user()->name }}
        </div>
    
        <form action="{{ route('kehadirans.checkin') }}" method="POST" class="mb-4">
            @csrf
            <div class="form-group">
                <label for="shift">Shift:</label>
                <select name="shift" id="shift" class="form-control" required>
                    <option value="pagi">Pagi</option>
                    <option value="sore">Sore</option>
                </select>
            </div>
    
            <div class="form-group">
                <label for="location">Lokasi:</label>
                <input type="text" name="location" id="location" class="form-control" readonly required>
            </div>
            <div>
                <button type="submit" class="btn btn-primary mt-3">Absen Masuk</button>
            </div>
            
        </form>
    
        <hr>
    
        <h2>Riwayat Absen</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Shift</th>
                    <th>Masuk</th>
                    <th>Pulang</th>
                    <th>Lokasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kehadirans as $kehadiran)
                    <tr>
                        <td>{{ $kehadiran->user->name }}</td>
                        <td>{{ $kehadiran->date }}</td>
                        <td>{{ $kehadiran->shift }}</td>
                        <td>{{ $kehadiran->check_in ?? '-' }}</td>
                        <td>{{ $kehadiran->check_out ?? '-' }}</td>
                        <td>{{ $kehadiran->location }}</td>
                        <td>
                            @if (!$kehadiran->check_out)
                                <form action="{{ route('kehadirans.checkout', $kehadiran->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    <input type="text" name="location" id="checkout-location" class="form-control" readonly required>
                                    <button type="submit" class="btn btn-danger mt-2">Absen Pulang</button>
                                </form>
                            @else
                                Sudah Pulang
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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

        // Panggil fungsi saat halaman dimuat untuk input absen masuk
        window.onload = function() {
            getLocation('location'); // Untuk absen masuk
        };

        // Dapatkan lokasi otomatis saat checkout jika tombol checkout ada
        document.addEventListener('DOMContentLoaded', function() {
            const checkoutInput = document.getElementById('checkout-location');
            if (checkoutInput) {
                getLocation('checkout-location'); // Untuk absen pulang
            }
        });
    </script>
</x-app-layout>
