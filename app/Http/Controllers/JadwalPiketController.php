<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalPiket;
use App\Models\Instansi;

class JadwalPiketController extends Controller
{
    // Menampilkan daftar jadwal piket
    public function index()
    {
        $jadwalPikets = JadwalPiket::with('instansis')->get();
        
        return view('admin.jadwal_piket.index', compact('jadwalPikets'));
    }

    // Menampilkan form tambah jadwal piket
    public function create()
    {
        $instansis = Instansi::all();
        return view('admin.jadwal_piket.create', compact('instansis'));
    }

    // Menyimpan jadwal piket ke database
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'instansi_id' => 'required|array', // Pastikan instansi_id adalah array
            'instansi_id.*' => 'exists:instansis,id' // Validasi setiap instansi yang dipilih
        ]);

        $jadwalPiket = JadwalPiket::create([
            'tanggal' => $request->tanggal
        ]);

        $jadwalPiket->instansis()->attach($request->instansi_id);

        return redirect()->route('jadwal_piket.index')->with('success', 'Jadwal piket berhasil ditambahkan!');
    }

    // Menampilkan form edit jadwal piket
    public function edit($id)
    {
        $jadwalPiket = JadwalPiket::with('instansis')->findOrFail($id);
        $instansis = Instansi::all();
        return view('admin.jadwal_piket.edit', compact('jadwalPiket', 'instansis'));
    }

    // Mengupdate jadwal piket di database
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'instansi_id' => 'required|array',
            'instansi_id.*' => 'exists:instansis,id'
        ]);

        $jadwalPiket = JadwalPiket::findOrFail($id);
        $jadwalPiket->update(['tanggal' => $request->tanggal]);
        $jadwalPiket->instansis()->sync($request->instansi_id); // Update instansi yang dipilih

        return redirect()->route('jadwal_piket.index')->with('success', 'Jadwal piket berhasil diperbarui!');
    }

    // Menghapus jadwal piket
    public function destroy($id)
    {
        $jadwalPiket = JadwalPiket::findOrFail($id);
        $jadwalPiket->instansis()->detach(); // Hapus relasi di pivot table
        $jadwalPiket->delete();

        return redirect()->route('jadwal_piket.index')->with('success', 'Jadwal piket berhasil dihapus!');
    }

    public function userJadwal()
    {
        $jadwalPikets = JadwalPiket::with('instansis')->get();
        $jadwalPikets = JadwalPiket::with('instansis')->paginate(10);
    
        return view('piket_user.index', compact('jadwalPikets'));
    }

}
