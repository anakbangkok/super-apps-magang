<?php

namespace App\Http\Controllers\Admin;

use App\Exports\IzinExport;
use App\Models\PengajuanIzin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;


class PengajuanIzinUserController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $tanggalMulai = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');

        $tanggalMulai = $tanggalMulai ? Carbon::parse($tanggalMulai)->startOfDay() : null;
        $tanggalSelesai = $tanggalSelesai ? Carbon::parse($tanggalSelesai)->endOfDay() : null;

        $pengajuan = PengajuanIzin::with('user')
            
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
             })
            //sebelumnya
            // ->when($tanggalMulai && $tanggalSelesai, function ($query) use ($tanggalMulai, $tanggalSelesai) {
            //     return $query->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai]);
            // })
            // ->get();
            ->when(($tanggalMulai || $tanggalSelesai), function ($query) use ($tanggalMulai, $tanggalSelesai) {
            $query->where(function ($q) use ($tanggalMulai, $tanggalSelesai) {
                if ($tanggalMulai && $tanggalSelesai) {
                    // Cek overlap tanggal
                    $q->where(function ($sub) use ($tanggalMulai, $tanggalSelesai) {
                        $sub->where(function ($inner) use ($tanggalMulai, $tanggalSelesai) {
                            $inner->whereNotNull('tanggal_selesai')
                                ->where('tanggal_mulai', '<=', $tanggalSelesai)
                                ->where('tanggal_selesai', '>=', $tanggalMulai);
                        })
                        ->orWhere(function ($inner) use ($tanggalMulai, $tanggalSelesai) {
                            $inner->whereNull('tanggal_selesai')
                                ->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai]);
                        });
                    });
                } elseif ($tanggalMulai) {
                    $q->where(function ($sub) use ($tanggalMulai) {
                        $sub->where(function ($inner) use ($tanggalMulai) {
                            $inner->whereNotNull('tanggal_selesai')
                                ->where('tanggal_selesai', '>=', $tanggalMulai);
                        })
                        ->orWhere(function ($inner) use ($tanggalMulai) {
                            $inner->whereNull('tanggal_selesai')
                                ->where('tanggal_mulai', '>=', $tanggalMulai);
                        });
                    });
                } elseif ($tanggalSelesai) {
                    $q->where(function ($sub) use ($tanggalSelesai) {
                        $sub->where(function ($inner) use ($tanggalSelesai) {
                            $inner->whereNotNull('tanggal_selesai')
                                ->where('tanggal_mulai', '<=', $tanggalSelesai);
                        })
                        ->orWhere(function ($inner) use ($tanggalSelesai) {
                            $inner->whereNull('tanggal_selesai')
                                ->where('tanggal_mulai', '<=', $tanggalSelesai);
                        });
                    });
                }
            });
        })
        ->get();

        $pendingCount = PengajuanIzin::where('status', 'menunggu')->count();

        $users = User::all();

        return view('admin.pengajuan_izin.index', compact('pengajuan', 'pendingCount', 'users'));
    }


    public function export(Request $request)
    {
        $user = $request->input('user');

        // Ambil data pengajuan izin berdasarkan filter pengguna
        $query = PengajuanIzin::query();
        if ($user) {
            $query->where('user_id', $user);
        }

        // Ambil hanya data dengan status 'disetujui'
        $approvedData = $query->where('status', 'disetujui')->get();

        // Kelompokkan berdasarkan user dan jenis izin
        $groupedData = $approvedData->groupBy('user_id')->map(function ($userData) {
            // Hitung jumlah izin per jenis izin
            return [
                'sakit' => $userData->where('jenis_izin', 'sakit')->count(),
                'keluarga' => $userData->where('jenis_izin', 'keluarga')->count(),
                'kegiatan_sekolah' => $userData->where('jenis_izin', 'kegiatan sekolah')->count(),
                'lain-lain' => $userData->where('jenis_izin', 'lain-lain')->count(),
                
            ];
        });

        $exportData = [['Nama Pengguna', 'Izin Sakit', 'Izin Keluarga', 'Izin Kegiatan Sekolah', 'Izin Lain-lain']];

        foreach ($groupedData as $userId => $totals) {
            $userName = User::find($userId)->name;
            $exportData[] = [
                $userName,
                $totals['sakit'],
                $totals['keluarga'],
                $totals['kegiatan_sekolah'],
                $totals['lain-lain']
            ];
        }

        return Excel::download(new IzinExport($exportData), 'total_izin_per_user.xlsx');
    }


    public function approve(PengajuanIzin $pengajuanIzin)
    {
        $pengajuanIzin->status = 'disetujui';
        $pengajuanIzin->save();

        Cache::forget('pending_count');

        return redirect()->back()->with('success', 'Pengajuan izin berhasil disetujui!');
    }

    public function reject(PengajuanIzin $pengajuanIzin)
    {
        $pengajuanIzin->status = 'ditolak';
        $pengajuanIzin->save();

        Cache::forget('pending_count');

        return redirect()->back()->with('success', 'Pengajuan izin berhasil ditolak!');
    }


    public function checkNotifications()
    {
        $count = \App\Models\PengajuanIzin::where('status', 'menunggu')->count();
        return response()->json(['count' => $count]);
    }
}
