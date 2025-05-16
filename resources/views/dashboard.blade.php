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
        style="max-width: 80%; width: auto; position: fixed; top: 20px; right: 20px; z-index: 9999;">
        <div class="d-flex justify-content-between">
            <span>Selamat datang di dashboard <b>{{ auth()->user()->name }}</b></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>

    <div class="container my-4">
        <h3 class="text-center">Peringkat SEO Teratas</h3>
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
                    <div class="card border-0 p-3 shadow-lg"
                        style="border-top: 5px solid {{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : '#cd7f32') }};">
                        <div class="d-flex align-items-center">
                            <!-- Foto Profil dengan Border -->
                            <div class="position-relative">
                                <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('assets/img/avatars/default.jpg') }}"
                                    alt="Foto Profil"
                                    style="width: 75px; height: 75px; object-fit: cover; border-radius: 50%; border: 4px solid 
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
                                    <strong>{{ $user->total_kata }}</strong>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Kolom Pengumuman -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100 p-3">
                    <h5 class="text-center">Pengumuman</h5>

                    @if ($acara->isEmpty())
                        <p class="text-muted text-center">Belum Ada Pengumuman Terbaru</p>
                    @else
                        @foreach ($acara as $event)
                            <div class="card mb-3 p-2 d-flex flex-row align-items-center gap-3" data-bs-toggle="modal"
                                data-bs-target="#eventModal{{ $event->id }}" style="cursor: pointer;">

                                <div class="card-body p-0" style="flex: 1; min-height: 100px;">
                                    <h6 class="mb-1">{{ $event->judul }}</h6>
                                    <p class="mb-1 text-muted">
                                        {{ Str::words($event->deskripsi, 6, '...') }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Tanggal:</strong>
                                        {{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }}
                                    </p>
                                    <p class="mb-0">
                                        <strong>Lokasi:</strong> {{ $event->lokasi ?? 'Tidak ada lokasi' }}
                                    </p>
                                </div>

                                @if ($event->gambar)
                                    <img src="{{ asset('storage/' . $event->gambar) }}" class="img-fluid"
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
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body d-flex flex-column align-items-center text-center">
                                            @if ($event->gambar)
                                                <img src="{{ asset('storage/' . $event->gambar) }}" class="img-fluid mb-3"
                                                    style="max-height: 300px; border-radius: 8px;" alt="Gambar Acara">
                                            @endif
                                            <p class="mb-2">{{ $event->deskripsi }}</p>
                                            <p><strong>Tanggal:</strong>
                                                {{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }}</p>
                                            <p><strong>Lokasi:</strong> {{ $event->lokasi ?? 'Tidak ada lokasi' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- <div class="col-md-6 mb-4">
                <div class="card h-100 p-3">
                    jika ingin menambahkan grafik
                </div>
            </div> --}}
        </div>
    </div>

@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var notification = document.getElementById('notification');
        setTimeout(function() {
            notification.classList.add('fade-out');
        }, 3000);

        setTimeout(function() {
            notification.style.display = 'none';
        }, 3500);
    });
</script>
