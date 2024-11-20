@extends('layouts.app')

@section('title', 'Dashboard Pengguna')

@section('header')
    <h2 class="font-weight-bold text-dark">
        {{ __('Dashboard') }}
    </h2>
@endsection

<head>
    <!-- Tambahkan Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

@section('content')
    {{-- Notifikasi Selamat Datang --}}
    <div id="notification" class="alert alert-success alert-dismissible fade show"
        style="max-width: 80%; width: auto; position: fixed; top: 20px; right: 20px;">
        <div class="d-flex justify-content-between">
            <span>Selamat datang di dashboard <b>{{ auth()->user()->name }}</b></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>

    <div class="container my-4">
        <h3 class="text-center">
            Peringkat SEO Teratas
        </h3>

        {{-- Cek posisi peringkat --}}
        @if ($userRankPosition > 3)
            <div class="alert alert-info alert-dismissible fade show mt-4 text-center" role="alert">
                <strong>Pesan!</strong> Kamu berada di peringkat {{ $userRankPosition }}. Ayo lebih semangat lagi untuk
                mencapai 3 besar!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif ($userRankPosition <= 3)
            <div class="alert alert-info alert-dismissible fade show mt-4 text-center" role="alert">
                <strong>Selamat</strong> Kamu berada di peringkat <strong>{{ $userRankPosition }} ! Terus pertahankan posisi
                    ini!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            @foreach ($topUsers->take(3) as $index => $user)
                <div class="col-12 col-md-4 mb-4">
                    <div class="card border-{{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : 'brown') }} p-3 shadow-lg">
                        <div class="d-flex align-items-center">
                            <!-- Foto Profil dengan Border -->
                            <div class="position-relative">
                                <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('assets/img/avatars/default.jpg') }}"
                                    alt="Foto Profil"
                                    style="width: 75px; height: 75px; object-fit: cover; border-radius: 50%;  
                                           border: 4px solid 
                                           {{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : '#cd7f32') }};">

                                <!-- Ikon Mahkota di Atas Foto -->
                                <div class="position-absolute" style="top: -18px; left: 50%; transform: translateX(-50%);">
                                    @if ($index == 0)
                                        <i class="fas fa-crown" style="color: gold; font-size: 2em;"></i>
                                    @elseif ($index == 1)
                                        <i class="fas fa-crown" style="color: silver; font-size: 2em;"></i>
                                    @elseif ($index == 2)
                                        <i class="fas fa-crown" style="color: #cd7f32; font-size: 2em;"></i>
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
                                    <strong>{{ $user->total_kata }}</strong></h6>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>










        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
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
        </script>

        <style>
            /* Hover Card */
            .card:hover {
                transform: translateY(-10px);
                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
                transition: all 0.3s ease-in-out;
            }

            /* Rotasi Ikon */
            .card-body i {
                transition: transform 0.3s ease-in-out;
            }

            .card-body i:hover {
                transform: rotate(360deg);
            }

            /* Notifikasi */
            #notification {
                display: block;
                position: fixed;
                top: 20px;
                right: 20px;
                max-width: 1000px;
                width: auto;
                padding: 15px 40px 15px 15px;
                background-color: #28a745;
                color: #fff;
                border-radius: 5px;
                z-index: 1100;
                opacity: 1;
                animation: slideDown 0.5s forwards;
            }

            #notification .btn-close {
                position: absolute;
                top: 50%;
                right: 5px;
                transform: translateY(-50%);
                color: #fff;
            }

            /* Animasi Notifikasi */
            @keyframes slideDown {
                from {
                    top: -100px;
                }

                to {
                    top: 20px;
                }
            }

            /* Shadow besar */
            .shadow-lg {
                box-shadow: 0 0 40px rgba(0, 0, 0, 0.2) !important;
            }

            /* Flexbox dalam Card */
            .card-body {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            /* Margin kanan untuk ikon */
            .card-body .fas {
                margin-right: 15px;
            }

            /* Responsif untuk Notifikasi */
            @media (max-width: 576px) {
                #notification {
                    top: 10px;
                    right: 10px;
                    padding: 10px 20px;
                }
            }
        </style>
    @endsection
