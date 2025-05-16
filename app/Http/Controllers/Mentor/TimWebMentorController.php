<?php

namespace App\Http\Controllers\Mentor;

use App\Models\TimWeb;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;

class  TimWebMentorController extends Controller
{
    // Menampilkan data TimWeb dengan filter nama dan jumlah artikel/kata hari ini


    public function index(Request $request)
    {
        $mentorId = Auth::id();
        $userIds = User::where('mentor_id', $mentorId)->pluck('id');

        // Menghitung jumlah artikel hari ini dari user yang dibimbing
        $jumlahArtikel = TimWeb::whereDate('created_at', Carbon::today())
            ->whereIn('user_id', $userIds)
            ->sum('jumlah_artikel');

        // Menghitung jumlah kata hari ini dari user yang dibimbing
        $jumlahKata = TimWeb::whereDate('created_at', Carbon::today())
            ->whereIn('user_id', $userIds)
            ->sum('jumlah_kata');

        // Query dasar: hanya data user yang dibimbing
        $query = TimWeb::whereIn('user_id', $userIds);

        // Filter tanggal
        if ($request->has('min_date') && $request->has('max_date') && $request->min_date != '' && $request->max_date != '') {
            $minDate = Carbon::parse($request->min_date)->startOfDay();
            $maxDate = Carbon::parse($request->max_date)->endOfDay();
            $query->whereBetween('created_at', [$minDate, $maxDate]);
        }

        // Filter nama
        if ($request->has('nama_filter') && $request->nama_filter != '') {
            $query->where('nama', 'like', '%' . $request->nama_filter . '%');
        }

        // Ambil data akhir
        $tim_webs = $query->get();

        // Hitung total berdasarkan filter
        $totalJumlahArtikel = $tim_webs->sum('jumlah_artikel');
        $totalJumlahKata = $tim_webs->sum('jumlah_kata');

        return view('mentor.tim_webs.index', compact('tim_webs', 'jumlahArtikel', 'jumlahKata', 'totalJumlahKata', 'totalJumlahArtikel'));
    }
    
    
    // Menampilkan form untuk mengedit data TimWeb
    public function edit(TimWeb $tim_web)
    {
        return view('mentor.tim_webs.edit', compact('tim_web'));
    }

    // Memperbarui data TimWeb
    public function update(Request $request, TimWeb $tim_web)
    {
        // Validasi data termasuk kolom tanggal dan nama
        $request->validate([
            'jumlah_artikel' => 'required|integer',
            'jumlah_kata' => 'required|integer',
            'keterangan' => 'required|string',
            'tanggal' => 'required|date', // Validasi tanggal
        ]);

        // Update data di database
        $tim_web->update([
            'jumlah_artikel' => $request->jumlah_artikel,
            'jumlah_kata' => $request->jumlah_kata,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('mentor.tim_webs.index')->with('success', 'Data berhasil diperbarui!');
    }

    // Menghapus data TimWeb
    public function destroy(TimWeb $tim_web)
    {
        // Hapus data
        $tim_web->delete();

        return redirect()->route('mentor.tim_webs.index')->with('success', 'Data berhasil dihapus!');
    }
    public function filterData(Request $request)
    {
        $minDate = $request->input('min_date');
        $maxDate = $request->input('max_date');

        $script = "<script>console.log('Min Date: {$minDate}, Max Date: {$maxDate}');</script>";
        echo $script;

        // Proses lainnya
    }
}
