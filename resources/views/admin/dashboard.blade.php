@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('header')
    Absensi
@endsection

@section('content') 

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <p class="card-text">
                        Selamat datang di dashboard Admin <b>{{ auth()->user()->name }}</b>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    <div class="row text-center mb-4">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card text-black bg-light border border-secondary hover-shadow">
                <div class="card-header p-3 pt-2 bg-white text-white">
                    <div class="icon icon-lg icon-shape bg-none text-center border-radius-xl mt-n4 position-absolute">
                        <i class="fas fa-clock fa-2x text-secondary"></i>
                    </div>
                    <div class="text-center pt-4">
                        <h4 class="mb-0">{{ $belumMasuk }}</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3 bg-secondary">
                    <p class="mb-0"><span class="text-white text-sm font-weight-bolder">Total Belum Masuk</span></p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card text-black bg-light border border-success hover-shadow">
                <div class="card-header p-3 pt-2 bg-white text-white">
                    <div class="icon icon-lg icon-shape bg-none text-center border-radius-xl mt-n4 position-absolute">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <div class="text-center pt-4 ">
                        <h4 class="mb-0">{{ $aktif }}</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3 bg-success">
                    <p class="mb-0"><span class="text-white text-sm font-weight-bolder">Total Aktif</span></p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card text-black bg-light border border-primary hover-shadow">
                <div class="card-header p-3 pt-2 bg-white text-white">
                    <div class="icon icon-lg icon-shape bg-none text-center border-radius-xl mt-n4 position-absolute">
                        <i class="fas fa-flag-checkered fa-2x text-primary"></i>
                    </div>
                    <div class="text-center pt-4">
                        <h4 class="mb-0">{{ $selesai }}</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3 bg-primary">
                    <p class="mb-0"><span class="text-white bg-primary text-sm font-weight-bolder">Total Selesai</span></p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card text-black bg-light border border-warning hover-shadow">
                <div class="card-header p-3 pt-2 bg-white text-white">
                    <div class="icon icon-lg icon-shape bg-none text-center border-radius-xl mt-n4 position-absolute">
                        <i class="fas fa-pencil-alt fa-2x text-warning"></i>
                    </div>
                    <div class="text-center pt-4">
                        <h4 class="mb-0">{{ $belumDiisi }}</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0 ">
                <div class="card-footer p-3 bg-warning">
                    <p class="mb-0"><span class="text-white text-sm font-weight-bolder">Total Belum Diisi</span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    .card {
        border-radius: 10px;
    }
</style>
            
@endsection