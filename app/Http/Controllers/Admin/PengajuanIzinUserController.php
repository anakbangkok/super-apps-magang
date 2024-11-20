<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PengajuanIzinExport;
use App\Models\PengajuanIzin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class PengajuanIzinUserController extends Controller
{
    // app/Http/Controllers/PengajuanIzinController.php

    public function index(Request $request)
    {
        // Ambil input status dan tanggal dari request
        $status = $request->get('status');
        $tanggalMulai = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');

        // Konversi tanggal dari input (jika ada)
        $tanggalMulai = $tanggalMulai ? Carbon::parse($tanggalMulai)->startOfDay() : null;
        $tanggalSelesai = $tanggalSelesai ? Carbon::parse($tanggalSelesai)->endOfDay() : null;

        // Query data izin dengan filter dinamis
        $pengajuan = PengajuanIzin::with('user')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($tanggalMulai && $tanggalSelesai, function ($query) use ($tanggalMulai, $tanggalSelesai) {
                return $query->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai]);
            })
            ->get();

        // Hitung jumlah pengajuan izin yang statusnya 'menunggu'
        $pendingCount = PengajuanIzin::where('status', 'menunggu')->count();

        // Ambil semua data pengguna
        $users = User::all();

        // Pastikan untuk mengirimkan variabel pengajuan, pendingCount, dan users ke view
        return view('admin.pengajuan_izin.index', compact('pengajuan', 'pendingCount', 'users'));
    }


    public function export(Request $request)
    {
        // Menyaring atau mengambil semua data
        $pengajuanIzin = PengajuanIzin::all(); // Ambil semua pengajuan izin tanpa filter

        // Jika Anda ingin mengekspor ke file Excel
        return Excel::download(new PengajuanIzinExport($pengajuanIzin), 'pengajuan_izin.xlsx');
    }


    public function approve(PengajuanIzin $pengajuanIzin)
    {
        $pengajuanIzin->status = 'disetujui';
        $pengajuanIzin->save();

        // Memperbarui cache setelah persetujuan
        Cache::forget('pending_count'); // Menghapus cache agar dihitung ulang

        return redirect()->back()->with('success', 'Pengajuan izin berhasil disetujui!');
    }

    public function reject(PengajuanIzin $pengajuanIzin)
    {
        $pengajuanIzin->status = 'ditolak';
        $pengajuanIzin->save();

        // Memperbarui cache setelah penolakan
        Cache::forget('pending_count'); // Menghapus cache agar dihitung ulang

        return redirect()->back()->with('success', 'Pengajuan izin berhasil ditolak!');
    }


    public function checkNotifications()
    {
        $count = \App\Models\PengajuanIzin::where('status', 'menunggu')->count();
        return response()->json(['count' => $count]);
    }
}
