<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Mentor;
use App\Models\Penugasan;
use App\Models\Instansi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
// Di dalam controller
public function index(Request $request)
{
    $searchName = $request->input('searchName');
    $searchEmail = $request->input('searchEmail');
    $searchPenugasan = $request->input('searchPenugasan');
    $searchStatus = $request->input('searchStatus');
    $startDate = $request->input('startDate');
    $endDate = $request->input('endDate');

    $query = User::with(['instansi', 'penugasan', 'mentor']);

    if ($searchName) {
        $query->where('name', 'like', '%' . $searchName . '%');
    }
    if ($searchEmail) {
        $query->where('email', 'like', '%' . $searchEmail . '%');
    }
    if ($searchPenugasan) {
        $query->where('penugasan_id', $searchPenugasan);
    }
    if ($searchStatus) {
        $now = now()->toDateString(); // Dapatkan tanggal sekarang

        // Filter pengguna berdasarkan status
        switch ($searchStatus) {
            case 'Aktif':
                $query->where('start_date', '<=', $now)->where('end_date', '>=', $now);
                break;
            case 'Belum Masuk':
                $query->where('start_date', '>', $now);
                break;
            case 'Selesai':
                $query->where('end_date', '<', $now);
                break;
        }
    }
    if ($startDate && $endDate) {
        $query->whereBetween('start_date', [$startDate, $endDate]);
    } elseif ($startDate) {
        $query->where('start_date', '>=', $startDate);
    } elseif ($endDate) {
        $query->where('end_date', '<=', $endDate);
    }

    $users = $query->paginate(10);
    $penugasans = Penugasan::all();

    return view('admin.users.index', compact('users', 'penugasans'));
}

    

    public function create()
    {
        // Ambil semua data terkait untuk dropdown
        $instansis = Instansi::all();
        $penugasans = Penugasan::all();
        $mentors = Mentor::all();

        return view('admin.users.create', compact('instansis', 'penugasans', 'mentors'));
    }

    public function store(Request $request)
    {
        // Validasi data permintaan yang masuk
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'instansi' => 'required|exists:instansis,id',
            'penugasan' => 'required|exists:penugasans,id',
            'mentor' => 'required|exists:mentors,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Buat pengguna baru dengan data yang divalidasi
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'instansi_id' => $validatedData['instansi'],
            'penugasan_id' => $validatedData['penugasan'],
            'mentor_id' => $validatedData['mentor'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dibuat!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        // Ambil data terkait untuk form edit
        $instansis = Instansi::all();
        $penugasans = Penugasan::all();
        $mentors = Mentor::all();

        return view('admin.users.edit', compact('user', 'instansis', 'penugasans', 'mentors'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi data permintaan yang masuk
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'instansi' => 'required|exists:instansis,id',
            'penugasan' => 'required|exists:penugasans,id',
            'mentor' => 'required|exists:mentors,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Perbarui pengguna dengan data yang divalidasi
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'instansi_id' => $request->instansi,
            'penugasan_id' => $request->penugasan,
            'mentor_id' => $request->mentor,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus!');
    }

    public function dashboard()
    {
        $belumMasuk = User::where('start_date', '>', now()->toDateString())->count();
        $aktif = User::where('start_date', '<=', now()->toDateString())
                     ->where('end_date', '>=', now()->toDateString())->count();
        $selesai = User::where('end_date', '<', now()->toDateString())->count();
        $belumDiisi = User::whereNull('start_date')->whereNull('end_date')->count();
        
        return view('admin.dashboard', compact('belumMasuk', 'aktif', 'selesai', 'belumDiisi'));
    }

    public function updatestatus()
    {
        $today = Carbon::today();
    
        if ($today < $this->start_date) {
            $this->status = 'Belum Masuk';
        } elseif ($today >= $this->start_date && $today <= $this->end_date) {
            $this->status = 'Aktif';
        } else {
            $this->status = 'Selesai';
        }
    
        $this->save();
    }


    
    

}
