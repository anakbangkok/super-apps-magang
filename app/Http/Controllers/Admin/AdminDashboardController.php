<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $belumMasuk = Kehadiran::where('status', 'belum_masuk')->count();
        $aktif = Kehadiran::where('status', 'aktif')->count();
        $selesai = Kehadiran::where('status', 'selesai')->count();
        $belumDiisi = Kehadiran::whereNull('status')->count();

        return view('admin.dashboard', compact('belumMasuk', 'aktif', 'selesai', 'belumDiisi'));
    }
}
