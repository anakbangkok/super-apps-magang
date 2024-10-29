<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use App\Models\Mentor;
use App\Models\Penugasan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MentorController extends Controller
{
    // Menampilkan daftar mentor
    public function index()
    {
        $mentors = Mentor::all();
        return view('mentor.index', compact('mentors'));
    }

    // Menampilkan formulir untuk menambah mentor baru
    public function create()
    {
        return view('mentor.create'); // Pastikan view ini ada
    }

    // Menyimpan mentor baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:mentors',
            'password' => 'required|string|min:8|confirmed',
            'nik' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
        ]);

        Mentor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nik' => $request->nik,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('mentor.index')->with('success', 'Mentor berhasil dibuat!');
    }

    // Menampilkan detail mentor
    public function show($id)
    {
        $mentor = Mentor::findOrFail($id);
        return view('mentor.show', compact('mentor'));
    }

    // Menampilkan formulir untuk mengedit mentor
    public function edit($id)
    {
        $mentor = Mentor::findOrFail($id);
        return view('mentor.edit', compact('mentor'));
    }

    // Memperbarui data mentor
    public function update(Request $request, $id)
    {
        $mentor = Mentor::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:mentors,email,' . $mentor->id,
            'password' => 'required|string|min:8|confirmed',
            'nik' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
        ]);

        $mentor->update($request->all());
        return redirect()->route('mentor.index')->with('success', 'Mentor berhasil diperbarui!');
    }

    // Menghapus mentor
    public function destroy($id)
    {
        $mentor = Mentor::findOrFail($id);
        $mentor->delete();
        return redirect()->route('mentor.index')->with('success', 'Mentor berhasil dihapus!');
    }

    // Menampilkan daftar pengguna
    public function usersindex(Request $request)
    {
        $searchName = $request->input('searchName');
        $searchEmail = $request->input('searchEmail');
        $searchPenugasan = $request->input('searchPenugasan');
        $searchStatus = $request->input('searchStatus');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $query = User::with(['instansi', 'penugasan', 'mentor']); // Pastikan relasi sudah didefinisikan

        // Tambahkan kondisi pencarian
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
            // Logika untuk pencarian berdasarkan status jika diperlukan
        }
        if ($startDate) {
            $query->where('start_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('end_date', '<=', $endDate);
        }

        // Ambil data dengan pagination
        $users = $query->paginate(10); // Atur jumlah data per halaman di sini

        // Ambil semua penugasan untuk dropdown
        $penugasans = Penugasan::all();

        return view('mentor.users.user', compact('users', 'penugasans'));
    }

    

}


