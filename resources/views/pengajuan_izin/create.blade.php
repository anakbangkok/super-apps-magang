@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Ajukan Izin</h2>
    <form action="{{ route('pengajuan_izin.store') }}" method="POST" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf
        <div class="mb-3">
            <label for="durasi" class="form-label">Durasi</label>
            <select id="durasi" name="durasi" class="form-control" required onchange="toggleTanggalSelesai()">
                <option value="sehari">Sehari</option>
                <option value="setengah_hari">Setengah Hari</option>
                <option value="lebih_sehari">Lebih dari Sehari</option>
            </select>
        </div>

        <div class="mb-3" id="tanggalSelesaiDiv" style="display: none;">
            <label for="tanggal_selesai" class="form-label">Tanggal Selesai (Opsional)</label>
            <input type="date" name="tanggal_selesai" class="form-control">
        </div>

        <div class="mb-3">
            <label for="jenis_izin" class="form-label">Jenis Izin</label>
            <select name="jenis_izin" class="form-control" required>
                <option value="sakit">Sakit</option>
                <option value="keluarga">Keluarga</option>
                <option value="kegiatan_sekolah">Kegiatan Sekolah</option>
                <option value="lain-lain">Lain-lain</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Ajukan Izin</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function toggleTanggalSelesai() {
        const durasi = document.getElementById('durasi').value;
        const tanggalSelesaiDiv = document.getElementById('tanggalSelesaiDiv');

        if (durasi === 'lebih_sehari') {
            tanggalSelesaiDiv.style.display = 'block';
        } else {
            tanggalSelesaiDiv.style.display = 'none';
        }
    }
</script>
@endsection
