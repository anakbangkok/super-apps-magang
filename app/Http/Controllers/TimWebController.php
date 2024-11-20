<?php

namespace App\Http\Controllers;

use App\Models\TimWeb;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TimWebController extends Controller
{
    // Menampilkan data TimWeb dengan filter nama dan jumlah artikel/kata hari ini
    public function index(Request $request)
    {
        // Menghitung jumlah artikel hari ini
        $jumlahArtikel = TimWeb::whereDate('created_at', Carbon::today())->sum('jumlah_artikel');
        
        // Menghitung jumlah kata hari ini
        $jumlahKata = TimWeb::whereDate('created_at', Carbon::today())->sum('jumlah_kata');

        // Filter berdasarkan nama jika ada
        $query = TimWeb::query();
        if ($request->has('nama_filter') && $request->nama_filter != '') {
            $query->where('nama', 'like', '%' . $request->nama_filter . '%');
        }

        // Mengambil semua data TimWeb sesuai dengan filter
        $tim_webs = $query->get();

        // Hitung total jumlah kata berdasarkan filter nama
        $totalJumlahArtikel = $tim_webs->sum('jumlah_artikel');
        $totalJumlahKata = $tim_webs->sum('jumlah_kata');

        // Kirimkan variabel ke view
        return view('admin.tim_web.index', compact('tim_webs', 'jumlahArtikel', 'jumlahKata', 'totalJumlahKata', 'totalJumlahArtikel'));
    }

    // Menampilkan form untuk menambah data TimWeb
    public function create()
    {
        return view('admin.tim_web.create');
    }

    // Menyimpan data TimWeb baru
    public function store(Request $request)
    {
        // Validasi data termasuk kolom tanggal dan nama
        $request->validate([
            'nama' => 'required|string|max:255', // Validasi untuk kolom nama
            'jumlah_artikel' => 'required|integer',
            'jumlah_kata' => 'required|integer',
            'keterangan' => 'required|string',
            'tanggal' => 'required|date', // Validasi tanggal
        ]);
        
        // Ambil user_id dari pengguna yang sedang login
        $user_id = Auth::id(); // Mengambil ID user yang sedang login
        
        // Simpan data ke database
        TimWeb::create([
            'user_id' => $user_id, // Menyimpan user_id yang sedang login
            'nama' => $request->nama, // Memastikan nama yang disimpan sesuai dengan yang diterima
            'jumlah_artikel' => $request->jumlah_artikel,
            'jumlah_kata' => $request->jumlah_kata,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('tim_web.index')->with('success', 'Data berhasil ditambahkan!');
    }

    // Menampilkan form untuk mengedit data TimWeb
    public function edit(TimWeb $tim_web)
    {
        return view('admin.tim_web.edit', compact('tim_web'));
    }

    // Memperbarui data TimWeb
    public function update(Request $request, TimWeb $tim_web)
    {
        // Validasi data termasuk kolom tanggal dan nama
        $request->validate([
            'nama' => 'required|string|max:255', // Validasi untuk kolom nama
            'jumlah_artikel' => 'required|integer',
            'jumlah_kata' => 'required|integer',
            'keterangan' => 'required|string',
            'tanggal' => 'required|date', // Validasi tanggal
        ]);

        // Update data di database
        $tim_web->update([
            'nama' => $request->nama, // Memastikan nama yang diperbarui sesuai dengan yang diterima
            'jumlah_artikel' => $request->jumlah_artikel,
            'jumlah_kata' => $request->jumlah_kata,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('tim_web.index')->with('success', 'Data berhasil diperbarui!');
    }

    // Menghapus data TimWeb
    public function destroy(TimWeb $tim_web)
    {
        // Hapus data
        $tim_web->delete();

        return redirect()->route('tim_web.index')->with('success', 'Data berhasil dihapus!');
    }
}
