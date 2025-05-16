<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Acara;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $belumMasuk = Kehadiran::where('status', 'belum_masuk')->count();
        $aktif = Kehadiran::where('status', 'aktif')->count();
        $selesai = Kehadiran::where('status', 'selesai')->count();
        $belumDiisi = Kehadiran::whereNull('status')->count();
        
        $acara = Acara::orderBy('tanggal', 'desc')->get();

        

        return view('admin.dashboard', compact('belumMasuk', 'aktif', 'selesai', 'belumDiisi', 'acara'));
    }


    
    
}
