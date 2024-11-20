@extends('layouts.app')

@section('title', 'Pengajuan Izin')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Ajukan Izin</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('pengajuan_izin.store') }}" method="POST" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf
        <div class="mb-3">
            <label for="durasi" class="form-label">Durasi</label>
            <select id="durasi" name="durasi" class="form-select" required onchange="toggleTanggalSelesai()">
                <option value="sehari">Sehari</option>
                <option value="setengah_hari">Setengah Hari</option>
                <option value="lebih_sehari">Lebih dari Sehari</option>
            </select>
        </div>
    
        <div class="mb-3">
            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" class="form-control" required>
        </div>

        <div class="mb-3" id="tanggalSelesaiDiv" style="display: none;">
            <label for="tanggal_selesai" class="form-label">Tanggal Selesai (Opsional)</label>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control">
        </div>
    
        <div class="mb-3">
            <label for="jenis_izin" class="form-label">Jenis Izin</label>
            <select name="jenis_izin" class="form-select" required>
                <option value="sakit">Sakit</option>
                <option value="keluarga">Keluarga</option>
                <option value="kegiatan_sekolah">Kegiatan Sekolah</option>
                <option value="lain-lain">Lain-lain</option>
            </select>
        </div>
    
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3" required></textarea>
        </div>
    
        <button type="submit" class="btn btn-primary">Ajukan Izin</button>
    </form>

    <h2 class="mt-5">Riwayat Pengajuan Izin</h2>
    <div class="row mt-3">
        @foreach ($pengajuan as $izin)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm border-light">
                    <div class="card-header bg-light">
                        <strong>Pengajuan Izin {{ $loop->iteration }}</strong>
                    </div>
                    <div class="card-body">
                        <p><strong>Durasi:</strong> {{ $izin->durasi }}</p>
                        <p><strong>Keterangan:</strong> {{ $izin->keterangan }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Status:</span>
                            @if ($izin->status == 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif ($izin->status == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @elseif ($izin->status == 'menunggu')
                                <span class="badge bg-warning">Menunggu Persetujuan</span>
                            @endif
                        </div>
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ $izin->status == 'disetujui' ? '100%' : ($izin->status == 'ditolak' ? '0%' : '50%') }};" 
                                 aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
@endsection

<script>
    function toggleTanggalSelesai() {
        const durasi = document.getElementById('durasi').value;
        const tanggalSelesaiDiv = document.getElementById('tanggalSelesaiDiv');

        // Tampilkan atau sembunyikan input tanggal selesai berdasarkan pilihan durasi
        if (durasi === 'lebih_sehari') {
            tanggalSelesaiDiv.style.display = 'block';
        } else {
            tanggalSelesaiDiv.style.display = 'none';
        }
    }
</script>
