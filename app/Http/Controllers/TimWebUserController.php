<?php

namespace App\Http\Controllers;

use App\Models\TimWeb;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TimWebUserController extends Controller
{
    // Menampilkan data TimWeb dengan filter nama dan jumlah artikel/kata hari ini
    public function index(Request $request)
    {
        // Mendapatkan tanggal hari ini
        $today = Carbon::today()->toDateString();
    
        // Menghitung jumlah artikel dan kata hari ini berdasarkan tanggal input
        $jumlahArtikel = TimWeb::where('user_id', Auth::id())
            ->where('tanggal', $today)
            ->sum('jumlah_artikel');
    
        $jumlahKata = TimWeb::where('user_id', Auth::id())
            ->where('tanggal', $today)
            ->sum('jumlah_kata');
    
        // Menghitung total jumlah artikel dan kata keseluruhan tanpa batasan tanggal
        $totalJumlahArtikel = TimWeb::where('user_id', Auth::id())->sum('jumlah_artikel');
        $totalJumlahKata = TimWeb::where('user_id', Auth::id())->sum('jumlah_kata');
    
        // Mengambil semua data untuk ditampilkan di DataTables
        $tim_webs = TimWeb::with('user') // Relasi dengan tabel user untuk mendapatkan nama user
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($item) {
                $item->formatted_tanggal = Carbon::parse($item->tanggal)->translatedFormat('d F Y');
                return $item;
            });
    
        return view('tim_webs.index', compact('tim_webs', 'jumlahArtikel', 'jumlahKata', 'totalJumlahArtikel', 'totalJumlahKata'));
    }


    // Menampilkan form untuk membuat data TimWeb
    public function create()
    {
        return view('tim_webs.create');
    }

    // Menyimpan data TimWeb baru
    public function store(Request $request)
    {
        // Validasi data termasuk kolom tanggal dan nama
        $request->validate([
            'name' => 'required|string|max:255',
            'jumlah_artikel' => 'required|integer',
            'jumlah_kata' => 'required|integer',
            'keterangan' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Simpan data ke database
        // Simpan data ke database
        TimWeb::create($data);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('tim_webs.index')->with('success', 'Data berhasil ditambahkan!');
    }

    // Menampilkan form untuk mengedit data TimWeb
    public function edit(TimWeb $tim_web)
    {
        return view('tim_webs.edit', compact('tim_web'));
    }

    // Memperbarui data TimWeb
    public function update(Request $request, TimWeb $tim_web)
    {
        // Validasi data termasuk kolom tanggal dan nama
        $request->validate([
            'name' => 'required|string|max:255',
            'jumlah_artikel' => 'required|integer',
            'jumlah_kata' => 'required|integer',
            'keterangan' => 'required|string',
            'tanggal' => 'required|date',  // Pastikan format tanggal valid
        ]);

        // Update data di database
        $tim_web->update($request->all());

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('tim_webs.index')->with('success', 'Data berhasil diperbarui!');
    }

    // Menghapus data TimWeb
    public function destroy(TimWeb $tim_web)
    {
        // Hapus data
        $tim_web->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('tim_webs.index')->with('success', 'Data berhasil dihapus!');
    }
}
