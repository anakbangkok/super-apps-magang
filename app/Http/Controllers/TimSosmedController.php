<?php

namespace App\Http\Controllers;

use App\Models\TimSosmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Mengimpor Auth untuk mendapatkan ID pengguna yang sedang login

class TimSosmedController extends Controller
{
    public function index()
    {
        // Ambil semua data tim sosmed beserta nama user yang mengirim
        $tim_sosmeds = TimSosmed::with('user')->get();  // Menggunakan eager loading untuk relasi user
        return view('admin.tim_sosmed.index', compact('tim_sosmeds'));
    }

    public function create()
    {
        return view('admin.tim_sosmed.create');
    }

    public function store(Request $request)
    {
        // Validasi input data
        $request->validate([
            'pekerjaan_hari_ini' => 'required|string',
            'keterangan' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        // Menyimpan data tanpa kolom 'nama', kita gunakan 'user_id' untuk menyimpan ID pengguna yang sedang login
        TimSosmed::create([
            'user_id' => Auth::id(),  // Menyimpan ID pengguna yang sedang login
            'pekerjaan_hari_ini' => $request->pekerjaan_hari_ini,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('tim_sosmed.index')->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit(TimSosmed $tim_sosmed)
    {
        return view('admin.tim_sosmed.edit', compact('tim_sosmed'));
    }

    public function update(Request $request, TimSosmed $tim_sosmed)
    {
        // Validasi input data
        $request->validate([
            'pekerjaan_hari_ini' => 'required|string',
            'keterangan' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        // Update data tanpa kolom 'nama', kita tetap gunakan 'user_id' yang ada
        $tim_sosmed->update([
            'pekerjaan_hari_ini' => $request->pekerjaan_hari_ini,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('tim_sosmed.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy(TimSosmed $tim_sosmed)
    {
        $tim_sosmed->delete();

        return redirect()->route('tim_sosmed.index')->with('success', 'Data berhasil dihapus!');
    }
}
