<?php

namespace App\Http\Controllers;

use App\Models\TimSosmed;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TimSosmedUserController extends Controller
{
    public function index()
    {
        // Mengambil data tim sosmed yang terkait dengan user yang sedang login
        $tim_sosmeds = TimSosmed::where('user_id', Auth::id())->get();
        
         // Relasi dengan tabel user untuk mendapatkan nama user
        $tim_sosmeds = TimSosmed::with('user') 
        ->where('user_id', Auth::id())
        ->get()
        ->map(function ($item) {
            $item->formatted_tanggal = Carbon::parse($item->tanggal)->translatedFormat('d F Y');
            return $item;
        });
        return view('tim_sosmeds.index', compact('tim_sosmeds'));
    }

    public function create()
    {
        // Menampilkan halaman untuk menambah data tim sosmed
        return view('tim_sosmeds.create');
    }

    public function store(Request $request)
    {
        // Validasi input data tanpa 'nama'
        $request->validate([
            'pekerjaan_hari_ini' => 'required|string',
            'keterangan' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        // Menyimpan data tim sosmed dengan user_id yang sedang login
        TimSosmed::create([
            'pekerjaan_hari_ini' => $request->pekerjaan_hari_ini,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
            'user_id' => Auth::id(), // Menyimpan ID user yang sedang login
        ]);

        return redirect()->route('tim_sosmeds.index')->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit(TimSosmed $tim_sosmed)
    {
        // Menampilkan halaman untuk mengedit data tim sosmed
        return view('tim_sosmeds.edit', compact('tim_sosmed'));
    }

    public function update(Request $request, TimSosmed $tim_sosmed)
    {
        // Validasi input data tanpa 'nama'
        $request->validate([
            'pekerjaan_hari_ini' => 'required|string',
            'keterangan' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        // Update data tanpa menerima input manual untuk 'nama'
        $tim_sosmed->update([
            'pekerjaan_hari_ini' => $request->pekerjaan_hari_ini,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('tim_sosmeds.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy(TimSosmed $tim_sosmed)
    {
        // Menghapus data tim sosmed
        $tim_sosmed->delete();

        return redirect()->route('tim_sosmeds.index')->with('success', 'Data berhasil dihapus!');
    }
}
