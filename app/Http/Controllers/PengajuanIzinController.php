<?php

namespace App\Http\Controllers;

use App\Models\PengajuanIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PengajuanIzinController extends Controller
{
    // Menampilkan daftar pengajuan izin user
    public function index()
    {
        $pengajuan = PengajuanIzin::where('user_id', Auth::id())->latest()->get();
        return view('pengajuan_izin.index', compact('pengajuan'));
    }

    // Menampilkan form pengajuan izin
    public function create()
    {
        return view('pengajuan_izin.create');
    }

    // Menyimpan pengajuan izin baru
    public function store(Request $request)
    {
        $request->validate([
            'durasi' => 'required',
            'jenis_izin' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|max:255',
        ]);

        // Menghitung jumlah hari
        $mulai = Carbon::parse($request->tanggal_mulai);
        $selesai = $request->tanggal_selesai ? Carbon::parse($request->tanggal_selesai) : $mulai;
        $jumlah_hari = $mulai->diffInDays($selesai) + 1;

        PengajuanIzin::create([
            'user_id' => Auth::id(),
            'durasi' => $request->durasi,
            'jenis_izin' => $request->jenis_izin,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jumlah_hari' => $jumlah_hari,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('pengajuan_izin.index')->with('success', 'Pengajuan izin berhasil diajukan!');
    }   

}
