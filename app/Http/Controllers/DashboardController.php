<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimWeb; // Pastikan model TimWeb sudah dibuat
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard.
     *
     * @return \Illuminate\View\View
     */

    public function index()
    {
        $topUsers = DB::table('tim_web')
            ->select('user_id', DB::raw('SUM(jumlah_kata) as total_kata'))
            ->groupBy('user_id')
            ->orderByDesc('total_kata')
            ->get();

        // Lakukan eager loading untuk mengambil informasi pengguna
        $topUsers = $topUsers->map(function ($item) {
            $user = User::find($item->user_id);
            $item->name = $user ? $user->name : 'Nama tidak ditemukan';
            $item->profile_photo_path = $user ? $user->profile_photo_path : null; // Menambahkan foto profil
            $item->instansi_name = $user && $user->instansi ? $user->instansi->nama_instansi : 'Tidak Ada Instansi'; // Validasi null
            $item->profile_photo = $user ? $user->profile_photo : null;
            return $item;
        });
        // Mengambil data pengguna yang sedang login
        $currentUser = Auth::user();

        // Mendapatkan total kata dari pengguna yang sedang login
        $currentUserTotalKata = DB::table('tim_web')
            ->where('user_id', $currentUser->id)
            ->sum('jumlah_kata');

        // Menentukan peringkat pengguna yang sedang login
        $userRank = $topUsers->firstWhere('user_id', $currentUser->id);
        $userRankPosition = 0;

        if ($userRank) {
            $userRankPosition = $topUsers->search(function ($item) use ($userRank) {
                return $item->user_id == $userRank->user_id;
            }) + 1;
        }

        return view('dashboard', compact('topUsers', 'userRankPosition', 'currentUserTotalKata'));
    }
}
