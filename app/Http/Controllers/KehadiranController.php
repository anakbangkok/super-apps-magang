<?php

namespace App\Http\Controllers;

use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KehadiranController extends Controller
{

    public function __construct()
    {
        // Set locale untuk Carbon
        \Carbon\Carbon::setLocale('id'); // Ubah ke 'id' untuk bahasa Indonesia
    }
    public function index()
    {
        // Set the locale to Indonesian
        Carbon::setLocale('id');
    
        // Retrieve attendance records for the authenticated user
        $kehadirans = Kehadiran::with('user')
            ->where('user_id', Auth::id())
            ->orderBy('date', 'desc') // Sort by the most recent date
            ->get()
            ->map(function ($kehadiran) {
                // Ensure the date is a Carbon instance for further operations
                $kehadiran->date = Carbon::parse($kehadiran->date); // Parse raw date
                $kehadiran->check_in = $kehadiran->check_in ? Carbon::parse($kehadiran->check_in)->setTimezone('Asia/Jakarta') : null;
                $kehadiran->check_out = $kehadiran->check_out ? Carbon::parse($kehadiran->check_out)->setTimezone('Asia/Jakarta') : null; // Convert check_out too
                return $kehadiran;
            });
    
        // Check if the user has checked in today
        $hasCheckedIn = $kehadirans->contains(function ($kehadiran) {
            return $kehadiran->date->isToday() && $kehadiran->check_in != null;
        });
    
        // Pass both kehadirans and hasCheckedIn to the view
        return view('kehadiran.index', compact('kehadirans', 'hasCheckedIn'));
    }
    


    public function adminIndex()
    {
        // Mengambil semua kehadiran dari database
        $kehadirans = Kehadiran::with('user')->get()->map(function ($kehadiran) {
            // Ubah waktu check_in dan check_out menjadi objek Carbon dan set timezone
            $kehadiran->date = Carbon::parse($kehadiran->date)->translatedFormat('d F Y'); // Modifikasi format tanggal
            $kehadiran->check_in = $kehadiran->check_in ? Carbon::parse($kehadiran->check_in)->setTimezone('Asia/Jakarta') : null;
            $kehadiran->check_out = $kehadiran->check_out ? Carbon::parse($kehadiran->check_out)->setTimezone('Asia/Jakarta') : null;
            return $kehadiran;
        });

        return view('admin.kehadiran.index', compact('kehadirans')); // Ganti 'admin.kehadiran.index' sesuai dengan view Anda
    }

    public function checkIn(Request $request)
    {
        // Cek apakah pengguna sudah absen pada hari ini
        $existingAttendance = Kehadiran::where('user_id', Auth::id())
            ->where('date', now()->toDateString())
            ->first();

        // Jika sudah ada absensi, berikan pesan
        if ($existingAttendance) {
            return redirect()->back()->with('message', 'Anda sudah absen hari ini!');
        }

        // Cek apakah lokasi tidak kosong
        if (empty($request->location)) {
            return redirect()->back()->with('error', 'Lokasi tidak boleh kosong!');
        }

        // Buat absensi baru
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
