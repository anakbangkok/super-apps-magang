<?php

namespace App\Http\Controllers;

use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KehadiranController extends Controller
{
    public function index()
    {
        $kehadirans = Kehadiran::with('user')
            ->where('user_id', Auth::id())
            ->orderBy('date', 'desc') // Urutkan berdasarkan tanggal terbaru
            ->get()
            ->map(function ($kehadiran) {
                // Ubah waktu check_in menjadi objek Carbon dan set timezone
                $kehadiran->check_in = $kehadiran->check_in ? Carbon::parse($kehadiran->check_in)->setTimezone('Asia/Jakarta') : null;
                $kehadiran->check_out = $kehadiran->check_out ? Carbon::parse($kehadiran->check_out)->setTimezone('Asia/Jakarta') : null; // Ubah check_out juga
                return $kehadiran;
            });
    
        return view('kehadiran.index', compact('kehadirans'));
    }

    public function adminIndex()
    {
        // Mengambil semua kehadiran dari database
        $kehadirans = Kehadiran::with('user')->get()->map(function ($kehadiran) {
            // Ubah waktu check_in dan check_out menjadi objek Carbon dan set timezone
            $kehadiran->check_in = $kehadiran->check_in ? Carbon::parse($kehadiran->check_in)->setTimezone('Asia/Jakarta') : null;
            $kehadiran->check_out = $kehadiran->check_out ? Carbon::parse($kehadiran->check_out)->setTimezone('Asia/Jakarta') : null;
            return $kehadiran;
        });

        return view('admin.kehadiran.index', compact('kehadirans')); // Ganti 'admin.kehadiran.index' sesuai dengan view Anda
    }

    public function checkIn(Request $request)
    {
        Kehadiran::create([
            'user_id' => Auth::id(),
            'shift' => $request->shift, // 'pagi' atau 'sore'
            'date' => now()->toDateString(),
            'check_in' => now(),
            'location' => $request->location,
        ]);

        return redirect()->back()->with('success', 'Berhasil absen masuk!');
    }

    public function checkOut(Request $request, $id)
    {
        $kehadiran = Kehadiran::findOrFail($id);
        $kehadiran->update([
            'check_out' => now(), 
            'location' => $request->location,
        ]);

        return redirect()->back()->with('success', 'Berhasil absen pulang!');
    }

    public function destroy($id)
    {
        $kehadiran = Kehadiran::findOrFail($id);
        $kehadiran->delete();

        return redirect()->back()->with('success', 'Kehadiran berhasil dihapus!');
    }

}
