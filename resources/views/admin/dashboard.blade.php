@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('header')
    Absensi
@endsection

@section('content')

    {{-- Notifikasi Selamat Datang --}}
    <div id="notification" class="alert alert-success alert-dismissible fade show"
        style="max-width: 80%; width: auto; position: fixed; top: 20px; right: 20px;">
        <div class="d-flex justify-content-between">
            <span>Selamat datang di dashboard Admin <b>{{ auth()->user()->name }}</b></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>

    <div class="container my-4">
        <h3 class="text-center">Peringkat SEO Teratas</h3>

        <div class="row">
            @if ($topUsers->isEmpty())
                <div class="col-12 text-center">
                    <p class="text-muted">Data SEO tidak ada</p>
                </div>
            @else
                @foreach ($topUsers->take(3) as $index => $user)
                    <div class="col-12 col-md-4 mb-4">
                        <div class="card border-{{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : ($index == 2 ? 'brown' : ($index == 3 ? 'black' : ''))) }} p-3 shadow-lg hover-effect"
                            style="{{ $index >= 3 ? 'pink' : '' }}">
                            <div class="d-flex align-items-center">
                                <!-- Foto Profil dengan Border -->
                                <div class="position-relative">
                                    <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('assets/img/avatars/default.jpg') }}"
                                        alt="Foto Profil"
                                        style="width: 75px; height: 75px; object-fit: cover; border-radius: 50%;  
                                            border: 4px solid 
                                            {{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : ($index == 2 ? '#cd7f32;' : '#436850')) }};">
                                    <!-- Ikon Mahkota di Atas Foto -->
                                    <div class="position-absolute"
                                        style="top: -18px; left: 50%; transform: translateX(-50%);">
                                        @if ($index == 0)
                                            <i class="fas fa-crown" style="color: gold; font-size: 2em;"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Peringkat 1"></i>
                                        @elseif ($index == 1)
                                            <i class="fas fa-crown" style="color: silver; font-size: 2em;"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Peringkat 2"></i>
                                        @elseif ($index == 2)
                                            <i class="fas fa-crown" style="color: #cd7f32; font-size: 2em;"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Peringkat 3"></i>
                                        @endif
                                    </div>
                                </div>
                                <div style="margin-left: 15px">
                                    <div class="d-flex align-items-center">
                                        <h5 class="card-title mb-2 font-weight-bold"
                                            style="margin-right: 10px; font-size: 14pt;">Peringkat {{ $index + 1 }}</h5>
                                        <div class="ml-2 mb-2"
                                            style="font-size: 1.2em; display: inline-block; vertical-align: middle;">
                                            @if ($index == 0)
                                                <i class="fas fa-trophy" style="color: gold;"></i>
                                            @elseif ($index == 1)
                                                <i class="fas fa-trophy" style="color: silver;"></i>
                                            @elseif ($index == 2)
                                                <i class="fas fa-trophy" style="color: #cd7f32;"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <h5 class="card-text mb-2"><strong>{{ $user->name }}</strong></h5>
                                    <p class="card-text text-muted" style="font-size: 11pt; margin-bottom: 5px;">
                                        {{ $user->instansi_name }}</p>
                                    <h6 class="card-text text-muted" style="margin-bottom: 0; font-size: 10pt">Total Kata:
                                        <strong>{{ $user->total_kata }}</strong>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>


      
    {{-- <div class="container-fluid py-4" id="userSummaryContainer">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h3>Ringkasan Data Pengguna</h3>
                        <div class="row align-items-stretch">
                            <div class="col-lg-6 d-flex justify-content-center">
                                <canvas id="dashboardDoughnutChart" width="300" height="300"
                                    class="chart-color"></canvas>
                            </div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="card border-left-danger h-100">
                                            <div class="card-body d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                        Total Belum Masuk
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        {{ $belumMasuk }}
                                                    </div>
                                                </div>
                                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="card border-left-success h-100">
                                            <div class="card-body d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                        Total Aktif
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        {{ $aktif }}
                                                    </div>
                                                </div>
                                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="card border-left-info h-100">
                                            <div class="card-body d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                        Total Selesai
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        {{ $selesai }}
                                                    </div>
                                                </div>
                                                <i class="fas fa-flag-checkered fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="card border-left-warning h-100">
                                            <div class="card-body d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                        Total Belum Diisi
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        {{ $belumDiisi }}
                                                    </div>
                                                </div>
                                                <i class="fas fa-pencil-alt fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    
    <div class="row mx-3 mt-4">
        <!-- Kolom Ringkasan Data Pengguna -->
        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h5 class="m-0 me-2">Ringkasan Data Pengguna</h5>
                            <small class="text-muted">
                                {{ $belumMasuk + $aktif + $selesai + $belumDiisi }} Total Pengguna
                            </small>
                        </div>
                        <div>
                            <canvas id="userStatisticsChart" width="100" height="100"></canvas>
                        </div>
                    </div>
                    <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-danger"><i class="fas fa-clock"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Belum Masuk</h6>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">{{ $belumMasuk }}</small>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-success"><i class="fas fa-check-circle"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Aktif</h6>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">{{ $aktif }}</small>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-info"><i class="fas fa-flag-checkered"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Selesai</h6>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">{{ $selesai}}</small>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-secondary"><i class="fas fa-pencil-alt"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Belum Diisi</h6>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">{{ $belumDiisi }}</small>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    
        <!-- Kolom Pengumuman -->
        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card h-100 p-3">
                <h4 class="text-center">Pengumuman</h4>
                
                @if ($acara->isEmpty())
                    <p class="text-muted text-center">Belum Ada Pengumuman Terbaru</p>
                @else
                    @foreach($acara as $event)
                        <div class="card mb-3 p-2 d-flex flex-row align-items-center gap-3"
                            data-bs-toggle="modal" data-bs-target="#eventModal{{ $event->id }}"
                            style="cursor: pointer;">
                            
                            <div class="card-body p-0" style="flex: 1; min-height: 100px;">
                                <h6 class="mb-1">{{ $event->judul }}</h6>
                                <p class="mb-1 text-muted">
                                    {{ Str::words($event->deskripsi, 6, '...') }}
                                </p>
                                <p class="mb-1">
                                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }}
                                </p>
                                <p class="mb-0">
                                    <strong>Lokasi:</strong> {{ $event->lokasi ?? 'Tidak ada lokasi' }}
                                </p>
                            </div>

                            @if ($event->gambar)
                                <img src="{{ asset('storage/' . $event->gambar) }}"
                                    class="img-fluid"
                                    style="object-fit: cover; width: 100px; height: 100px; border-radius: 8px;"
                                    alt="Acara">
                            @endif
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="eventModal{{ $event->id }}" tabindex="-1"
                            aria-labelledby="eventModalLabel{{ $event->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="eventModalLabel{{ $event->id }}">
                                            {{ $event->judul }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body d-flex flex-column align-items-center text-center">
                                        @if ($event->gambar)
                                            <img src="{{ asset('storage/' . $event->gambar) }}"
                                                class="img-fluid mb-3"
                                                style="max-height: 300px; border-radius: 8px;"
                                                alt="Gambar Acara">
                                        @endif
                                        <p class="mb-2">{{ $event->deskripsi }}</p>
                                        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }}</p>
                                        <p><strong>Lokasi:</strong> {{ $event->lokasi ?? 'Tidak ada lokasi' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    
      

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
          var ctx = document.getElementById('userStatisticsChart').getContext('2d');
          var userStatisticsChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Belum Masuk', 'Aktif', 'Selesai', 'Belum Diisi'],
              datasets: [{
                data: [{{ $belumMasuk }}, {{ $aktif }}, {{ $selesai }}, {{ $belumDiisi }}],
                backgroundColor: [
                'rgba(244, 67, 54, 0.9)',
                'rgba(76, 175, 80, 0.9)',
                'rgba(33, 150, 243, 0.9)',
                'rgba(158, 158, 158, 0.9)'
                ],
                borderWidth: 0
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: true,
              cutout: '50%', 
              plugins: {
                tooltip: {
                  callbacks: {
                    label: function(tooltipItem) {
                      return tooltipItem.label + ': ' + tooltipItem.raw;
                    }
                  }
                },
                legend: {
                  display: false
                }
              },
              layout: {
                padding: 1
              },
            }
          });
        });
      </script>
           
    {{-- <script>
        const ctx = document.getElementById('dashboardDoughnutChart').getContext('2d');
        const myDoughnutChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Belum Masuk', 'Aktif', 'Selesai', 'Belum Diisi'],
                datasets: [{
                    data: [{{ $belumMasuk }}, {{ $aktif }}, {{ $selesai }},
                        {{ $belumDiisi }}
                    ],
                    backgroundColor: [
                        'rgba(220, 53, 69, 1)', // Warna untuk Total Belum Masuk (merah)
                        'rgba(28, 200, 138, 1)', // Warna untuk Total Aktif (hijau)
                        'rgba(54, 185, 204, 1)', // Warna untuk Total Selesai (biru)
                        'rgba(246, 194, 62, 1)' // Warna untuk Total Belum Diisi (kuning)
                    ],
                    hoverBackgroundColor: [
                        'rgba(199, 35, 51, 1)', // Hover Warna untuk Total Belum Masuk (merah lebih gelap)
                        'rgba(23, 166, 115, 1)', // Hover Warna untuk Total Aktif (hijau lebih gelap)
                        'rgba(44, 159, 163, 1)', // Hover Warna untuk Total Selesai (biru lebih gelap)
                        'rgba(209, 166, 52, 1)' // Hover Warna untuk Total Belum Diisi (kuning lebih gelap)
                    ],
                    hoverBorderColor: 'rgba(234, 236, 244, 1)',
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            },
        });

        document.addEventListener('DOMContentLoaded', function() {
            var notification = document.getElementById('notification');

            // Tambahkan kelas fade-out setelah 3 detik
            setTimeout(function() {
                notification.classList.add('fade-out');
            }, 3000); // Tampilkan selama 3 detik sebelum mulai pudar

            // Hapus elemen setelah pemudaran selesai
            setTimeout(function() {
                notification.style.display = 'none';
            }, 3500); // Hapus setelah 3.5 detik
        });
        // Inisialisasi Tooltip untuk elemen dengan data-bs-toggle="tooltip"
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            var tooltip = new bootstrap.Tooltip(tooltipElements);
        });


    </script>
 --}}
    <style>
        /* #userStatisticsChart {
  width: 100%;
  max-width: 200px;
  height: 200px;
} */

        .card {
            border-radius: 10px;
        }

        .hover-effect:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }

        #dashboardDoughnutChart {
            max-height: 250px;
            margin: auto;
        }

        .border-left-danger {
            border-left: 4px solid #dc3545;
        }

        .border-left-success {
            border-left: 4px solid #1cc88a;
        }

        .border-left-info {
            border-left: 4px solid #36b9cc;
        }

        .border-left-warning {
            border-left: 4px solid #f6c23e;
        }

        @media (max-width: 768px) {
            #dashboardDoughnutChart {
                max-height: 200px;
            }
        }

        #notification {
            display: block;
            position: fixed;
            top: 20px;
            right: 20px;
            max-width: 80%;
            /* Membuat alert lebih fleksibel dan responsif */
            padding: 15px;
            background-color: #28a745;
            color: #fff;
            border-radius: 5px;
            z-index: 1100;
            opacity: 1;
            animation: slideDown 0.5s forwards;
        }

        #notification {
            display: block;
            position: fixed;
            top: 20px;
            right: 20px;
            max-width: 1000px;
            width: auto;
            padding: 15px 40px 15px 15px;
            /* Memberikan padding ekstra untuk ruang bagi tombol close */
            background-color: #28a745;
            color: #fff;
            border-radius: 5px;
            z-index: 1100;
            opacity: 1;
            animation: slideDown 0.5s forwards;
        }

        #notification .btn-close {
            position: absolute;
            /* Menempatkan tombol close di sudut kanan atas */
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            /* Menyelaraskan vertikal agar tombol berada di tengah */
        }

        @keyframes slideDown {
            from {
                top: -100px;
            }

            to {
                top: 20px;
            }
        }

        .fade-out {
            animation: fadeOut 0.5s forwards;
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }
    </style>
@endsection
