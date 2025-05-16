<?php

namespace App\Http\Controllers\Mentor;

use App\Models\TimSosmed;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;

class TimSosmedMentorController extends Controller
{
    public function index()
    {
    //jika ingin menampilkan data tim sosmed berdasarkan mentor yang sedang login
        $mentorId = Auth::id();

        $userIds = User::where('mentor_id', $mentorId)->pluck('id');

        $tim_sosmeds = TimSosmed::with('user')
            ->whereIn('user_id', $userIds)
            ->get();

    // Jika ingin menampilkan semua data tim sosmed tanpa filter
        // $tim_sosmeds = TimSosmed::with('user')->get();  
        return view('mentor.tim_sosmeds.index', compact('tim_sosmeds'));
    }

    public function edit(TimSosmed $tim_sosmed)
    {
       
        return view('mentor.tim_sosmeds.edit', compact('tim_sosmed'));
    }

    public function update(Request $request, TimSosmed $tim_sosmed)
    {
        // Validasi input data tanpa 'nama'
        $request->validate([
            'pekerjaan_hari_ini' => 'required|string',
            'keterangan' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        // Update data tanpa menerima input manual untuk 'nama'
        $tim_sosmed->update([
            'pekerjaan_hari_ini' => $request->pekerjaan_hari_ini,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('mentor.tim_sosmeds.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy(TimSosmed $tim_sosmed)
    {
        // Menghapus data tim sosmed
        $tim_sosmed->delete();

        return redirect()->route('mentor.tim_sosmeds.index')->with('success', 'Data berhasil dihapus!');
    }
}
