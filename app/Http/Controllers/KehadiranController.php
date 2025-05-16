<?php

namespace App\Http\Controllers;

use App\Exports\KehadiranExport;
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;


class KehadiranController extends Controller
{

    public function __construct()
    {
        // Set locale untuk Carbon (Bahasa Indonesia)
        \Carbon\Carbon::setLocale('id'); // Pengaturan bahasa Indonesia untuk Carbon
    }

    public function index()
    {
        // Set zona waktu ke Asia/Jakarta
        Carbon::setLocale('id'); // Set locale untuk bahasa Indonesia

        // Retrieve attendance records for the authenticated user
        $kehadirans = Kehadiran::with('user')
            ->where('user_id', Auth::id())
            ->orderBy('date', 'desc') // Sort by the most recent date
            ->get()
            ->map(function ($kehadiran) {
                // Pastikan tanggal dalam format Carbon dan zona waktu sudah benar
                $kehadiran->date = Carbon::parse($kehadiran->date)->setTimezone('Asia/Jakarta');
                $kehadiran->check_in = $kehadiran->check_in ? Carbon::parse($kehadiran->check_in)->setTimezone('Asia/Jakarta') : null;
                $kehadiran->check_out = $kehadiran->check_out ? Carbon::parse($kehadiran->check_out)->setTimezone('Asia/Jakarta') : null;
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
            $kehadiran->date = Carbon::parse($kehadiran->date); // Pastikan format tanggal disimpan sebagai Carbon
            $kehadiran->check_in = $kehadiran->check_in ? Carbon::parse($kehadiran->check_in)->setTimezone('Asia/Jakarta') : null;
            $kehadiran->check_out = $kehadiran->check_out ? Carbon::parse($kehadiran->check_out)->setTimezone('Asia/Jakarta') : null;

            // Format tanggal untuk tampilan
            $kehadiran->formatted_date = $kehadiran->date->translatedFormat('d F Y');

            return $kehadiran;
        });

        // Mengambil semua data pengguna
        $users = \App\Models\User::all();

        return view('admin.kehadiran.index', compact('kehadirans', 'users'));
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
    public function export(Request $request)
    {
        // Validasi input request
        $validated = $request->validate([
            'min_date' => 'nullable|date',
            'max_date' => 'nullable|date',
            'user' => 'nullable|exists:users,id',
            'shift' => 'nullable|in:pagi,sore',
            'lateness' => 'nullable|in:tepat_waktu,terlambat',
        ]);

        // Ambil parameter dari request
        $min_date = $validated['min_date'] ?? null;
        $max_date = $validated['max_date'] ?? null;
        $user_id = $validated['user'] ?? null;
        $shift = $validated['shift'] ?? null;
        $lateness = $validated['lateness'] ?? null;

        // Buat query untuk filter kehadiran
        $query = Kehadiran::query();

        // Filter berdasarkan rentang tanggal
        if ($min_date) {
            $query->whereDate('date', '>=', $min_date);
        }

        if ($max_date) {
            $query->whereDate('date', '<=', $max_date);
        }

        // Filter berdasarkan pengguna
        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        // Filter berdasarkan shift
        if ($shift) {
            $query->where('shift', $shift);
        }

        // Filter berdasarkan keterlambatan
        if ($lateness) {
            $query->where(function ($q) use ($lateness) {
                if ($lateness == 'tepat_waktu') {
                    // Tepat waktu untuk shift pagi dan sore
                    $q->where(function ($q) {
                        $q->where('shift', 'pagi')
                            ->where('check_in', '<=', '08:00:00');
                    })
                        ->orWhere(function ($q) {
                            $q->where('shift', 'sore')
                                ->where('check_in', '<=', '16:00:00');
                        });
                } elseif ($lateness == 'terlambat') {
                    // Terlambat untuk shift pagi dan sore
                    $q->where(function ($q) {
                        $q->where('shift', 'pagi')
                            ->where('check_in', '>', '08:00:00');
                    })
                        ->orWhere(function ($q) {
                            $q->where('shift', 'sore')
                                ->where('check_in', '>', '16:00:00');
                        });
                }
            });
        }

        // Ambil data yang telah difilter
        $kehadiran = $query->get();

        // Panggil export dan return file
        $data = new KehadiranExport($kehadiran);
        return Excel::download($data, 'kehadiran_filtered.xlsx');
    }
}
