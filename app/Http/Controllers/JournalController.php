<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index()
    {
        // Ambil jurnal hanya untuk pengguna yang sedang login
        $journals = Journal::where('user_id', auth()->id())->get(); 
        
        return view('journal.index', compact('journals'));
    }

    public function create()
    {
        // Tidak perlu mengambil jurnal di sini, cukup kembalikan tampilan untuk menambah jurnal
        return view('journal.create');
    }

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
        Journal::create(array_merge($request->all(), ['user_id' => auth()->id()]));

        return redirect()->route('journals.index')->with('success', 'Jurnal berhasil ditambahkan');
    }
}
