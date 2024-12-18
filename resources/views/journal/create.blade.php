@extends('layouts.app')

@section('title', 'Tambah Jurnal Harian')

@section('content')
    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-header text-black">
                <h1 class="mb-1">Tambah Aktivitas Harian</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('journals.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" id="date" name="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ auth()->user()->name }}" required readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label">Jam Mulai</label>
                            <input type="time" id="start_time" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label">Jam Selesai</label>
                            <input type="time" id="end_time" name="end_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="activity" class="form-label">Aktivitas</label>
                        <textarea id="activity" name="activity" class="form-control" rows="3" placeholder="Deskripsikan aktivitas" required></textarea>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('journals.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
@endsection
 
