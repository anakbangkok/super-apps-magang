<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    // Method untuk menampilkan jurnal pengguna yang sedang login
    public function index()
    {
        // Ambil jurnal hanya untuk pengguna yang sedang login
        $journals = Journal::where('user_id', Auth::id())->get();

        return view('journal.index', compact('journals'));
    }

    // Method untuk menampilkan halaman form pembuatan jurnal
    public function create()
    {
        // Kembalikan tampilan untuk menambah jurnal
        return view('journal.create');
    }

    // Method untuk menyimpan data jurnal baru
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'name' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'activity' => 'required|string'
        ]);

        // Tambahkan ID pengguna saat membuat jurnal
        Journal::create(array_merge($request->all(), ['user_id' => Auth::id()]));

        return redirect()->route('journals.index')->with('success', 'Jurnal berhasil ditambahkan');
    }

    // Method untuk menampilkan semua jurnal ke halaman admin
    public function adminIndex()
    {
        // Pastikan hanya admin yang bisa mengakses
        if (!Auth::user() || !Auth::user()->is_admin) {}

        // Ambil semua jurnal bersama dengan data pengguna
        $journals = Journal::with('user')->get();
        return view('journal.admin', compact('journals'));
    }
    
    public function destroy($id)
    {
        // Pastikan hanya admin yang bisa menghapus jurnal
        if (!Auth::guard('admin')->check() || !Auth::guard('admin')->user()->is_admin) {}
    
        // Temukan jurnal berdasarkan ID
        $journal = Journal::findOrFail($id);
    
        // Hapus jurnal
        $journal->delete();
    
        return redirect()->route('journal.admin')->with('success', 'Jurnal berhasil dihapus');
    }
    
}
