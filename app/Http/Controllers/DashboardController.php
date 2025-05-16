<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\TimWeb;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil daftar user dengan jumlah kata terbanyak
        $topUsers = TimWeb::select('user_id', DB::raw('SUM(jumlah_kata) as total_kata'))
            ->groupBy('user_id')
            ->orderByDesc('total_kata')
            ->get();

        // Ambil informasi pengguna hanya dalam satu query
        $userIds = $topUsers->pluck('user_id')->toArray();
        $users = User::whereIn('id', $userIds)->with('instansi')->get();

        // Mapping user data
        $topUsers = $topUsers->map(function ($item) use ($users) {
            $user = $users->where('id', $item->user_id)->first();
            $item->name = $user->name ?? 'Nama tidak ditemukan';
            $item->profile_photo_path = $user->profile_photo_path ?? null;
            $item->instansi_name = $user->instansi->nama_instansi ?? 'Tidak Ada Instansi';
            return $item;
        });

        // Ambil user yang sedang login
        $currentUser = Auth::user();

        // Hitung jumlah kata pengguna yang sedang login
        $currentUserTotalKata = TimWeb::where('user_id', $currentUser->id)->sum('jumlah_kata');

        // Tentukan peringkat user
        $userRankPosition = array_search($currentUser->id, array_column($topUsers->toArray(), 'user_id')) + 1;

        // Ambil daftar acara terbaru (misalnya berdasarkan tanggal)
        $acara = Acara::orderBy('tanggal', 'desc')->get();

        return view('dashboard', compact('topUsers', 'userRankPosition', 'currentUserTotalKata', 'acara'));
    }
}
